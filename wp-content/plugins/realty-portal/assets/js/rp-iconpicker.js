(function ( a, b ) {
	a.ui = a.ui || {};
	var c, d = Math.max, e = Math.abs, f = Math.round, g = /left|center|right/, h = /top|center|bottom/,
	    i                                                                         = /[\+\-]\d+(\.[\d]+)?%?/, j                                            = /^\w+/, k                                = /%$/, l = a.fn.pos;

	function m( a, b, c ) {
		return [
			parseFloat(a[ 0 ]) * (k.test(a[ 0 ]) ? b / 100 : 1),
			parseFloat(a[ 1 ]) * (k.test(a[ 1 ]) ? c / 100 : 1)
		];
	}

	function n( b, c ) {
		return parseInt(a.css(b, c), 10) || 0;
	}

	function o( b ) {
		var c = b[ 0 ];
		if ( c.nodeType === 9 ) {
			return {
				width : b.width(),
				height: b.height(),
				offset: {
					top : 0,
					left: 0
				}
			};
		}
		if ( a.isWindow(c) ) {
			return {
				width : b.width(),
				height: b.height(),
				offset: {
					top : b.scrollTop(),
					left: b.scrollLeft()
				}
			};
		}
		if ( c.preventDefault ) {
			return {
				width : 0,
				height: 0,
				offset: {
					top : c.pageY,
					left: c.pageX
				}
			};
		}
		return {
			width : b.outerWidth(),
			height: b.outerHeight(),
			offset: b.offset()
		};
	}

	a.pos = {
		scrollbarWidth: function () {
			if ( c !== b ) {
				return c;
			}
			var d, e,
			    f = a("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
			    g = f.children()[ 0 ];
			a("body").append(f);
			d = g.offsetWidth;
			f.css("overflow", "scroll");
			e = g.offsetWidth;
			if ( d === e ) {
				e = f[ 0 ].clientWidth;
			}
			f.remove();
			return c = d - e;
		},
		getScrollInfo : function ( b ) {
			var c = b.isWindow || b.isDocument ? "" : b.element.css("overflow-x"),
			    d = b.isWindow || b.isDocument ? "" : b.element.css("overflow-y"),
			    e = c === "scroll" || c === "auto" && b.width < b.element[ 0 ].scrollWidth,
			    f = d === "scroll" || d === "auto" && b.height < b.element[ 0 ].scrollHeight;
			return {
				width : f ? a.pos.scrollbarWidth() : 0,
				height: e ? a.pos.scrollbarWidth() : 0
			};
		},
		getWithinInfo : function ( b ) {
			var c = a(b || window), d = a.isWindow(c[ 0 ]), e = !!c[ 0 ] && c[ 0 ].nodeType === 9;
			return {
				element   : c,
				isWindow  : d,
				isDocument: e,
				offset    : c.offset() || {
					left: 0,
					top : 0
				},
				scrollLeft: c.scrollLeft(),
				scrollTop : c.scrollTop(),
				width     : d ? c.width() : c.outerWidth(),
				height    : d ? c.height() : c.outerHeight()
			};
		}
	};
	a.fn.pos = function ( b ) {
		if ( !b || !b.of ) {
			return l.apply(this, arguments);
		}
		b = a.extend({}, b);
		var c, k, p, q, r, s, t = a(b.of), u = a.pos.getWithinInfo(b.within), v = a.pos.getScrollInfo(u),
		    w                                                                   = (b.collision || "flip").split(" "), x                           = {};
		s = o(t);
		if ( t[ 0 ].preventDefault ) {
			b.at = "left top";
		}
		k = s.width;
		p = s.height;
		q = s.offset;
		r = a.extend({}, q);
		a.each([
			"my",
			"at"
		], function () {
			var a = (b[ this ] || "").split(" "), c, d;
			if ( a.length === 1 ) {
				a = g.test(a[ 0 ]) ? a.concat([ "center" ]) : h.test(a[ 0 ]) ? [ "center" ].concat(a) : [
					"center",
					"center"
				];
			}
			a[ 0 ] = g.test(a[ 0 ]) ? a[ 0 ] : "center";
			a[ 1 ] = h.test(a[ 1 ]) ? a[ 1 ] : "center";
			c = i.exec(a[ 0 ]);
			d = i.exec(a[ 1 ]);
			x[ this ] = [
				c ? c[ 0 ] : 0,
				d ? d[ 0 ] : 0
			];
			b[ this ] = [
				j.exec(a[ 0 ])[ 0 ],
				j.exec(a[ 1 ])[ 0 ]
			];
		});
		if ( w.length === 1 ) {
			w[ 1 ] = w[ 0 ];
		}
		if ( b.at[ 0 ] === "right" ) {
			r.left += k;
		} else if ( b.at[ 0 ] === "center" ) {
			r.left += k / 2;
		}
		if ( b.at[ 1 ] === "bottom" ) {
			r.top += p;
		} else if ( b.at[ 1 ] === "center" ) {
			r.top += p / 2;
		}
		c = m(x.at, k, p);
		r.left += c[ 0 ];
		r.top += c[ 1 ];
		return this.each(function () {
			var g, h, i = a(this), j = i.outerWidth(), l = i.outerHeight(), o = n(this, "marginLeft"),
			    s                                                             = n(this, "marginTop"), y                                   = j + o + n(this, "marginRight") + v.width,
			    z                                                             = l + s + n(this, "marginBottom") + v.height, A = a.extend({}, r),
			    B                                                             = m(x.my, i.outerWidth(), i.outerHeight());
			if ( b.my[ 0 ] === "right" ) {
				A.left -= j;
			} else if ( b.my[ 0 ] === "center" ) {
				A.left -= j / 2;
			}
			if ( b.my[ 1 ] === "bottom" ) {
				A.top -= l;
			} else if ( b.my[ 1 ] === "center" ) {
				A.top -= l / 2;
			}
			A.left += B[ 0 ];
			A.top += B[ 1 ];
			if ( !a.support.offsetFractions ) {
				A.left = f(A.left);
				A.top = f(A.top);
			}
			g = {
				marginLeft: o,
				marginTop : s
			};
			a.each([
				"left",
				"top"
			], function ( d, e ) {
				if ( a.ui.pos[ w[ d ] ] ) {
					a.ui.pos[ w[ d ] ][ e ](A, {
						targetWidth      : k,
						targetHeight     : p,
						elemWidth        : j,
						elemHeight       : l,
						collisionPosition: g,
						collisionWidth   : y,
						collisionHeight  : z,
						offset           : [
							c[ 0 ] + B[ 0 ],
							c[ 1 ] + B[ 1 ]
						],
						my               : b.my,
						at               : b.at,
						within           : u,
						elem             : i
					});
				}
			});
			if ( b.using ) {
				h = function ( a ) {
					var c = q.left - A.left, f = c + k - j, g = q.top - A.top, h = g + p - l, m = {
						target    : {
							element: t,
							left   : q.left,
							top    : q.top,
							width  : k,
							height : p
						},
						element   : {
							element: i,
							left   : A.left,
							top    : A.top,
							width  : j,
							height : l
						},
						horizontal: f < 0 ? "left" : c > 0 ? "right" : "center",
						vertical  : h < 0 ? "top" : g > 0 ? "bottom" : "middle"
					};
					if ( k < j && e(c + f) < k ) {
						m.horizontal = "center";
					}
					if ( p < l && e(g + h) < p ) {
						m.vertical = "middle";
					}
					if ( d(e(c), e(f)) > d(e(g), e(h)) ) {
						m.important = "horizontal";
					} else {
						m.important = "vertical";
					}
					b.using.call(this, a, m);
				};
			}
			i.offset(a.extend(A, {
				using: h
			}));
		});
	};
	a.ui.pos = {
		_trigger: function ( a, b, c, d ) {
			if ( b.elem ) {
				b.elem.trigger({
					type        : c,
					position    : a,
					positionData: b,
					triggered   : d
				});
			}
		},
		fit     : {
			left: function ( b, c ) {
				a.ui.pos._trigger(b, c, "posCollide", "fitLeft");
				var e = c.within, f = e.isWindow ? e.scrollLeft : e.offset.left, g = e.width,
				    h                                                              = b.left - c.collisionPosition.marginLeft, i                 = f - h, j = h + c.collisionWidth - g - f, k;
				if ( c.collisionWidth > g ) {
					if ( i > 0 && j <= 0 ) {
						k = b.left + i + c.collisionWidth - g - f;
						b.left += i - k;
					} else if ( j > 0 && i <= 0 ) {
						b.left = f;
					} else {
						if ( i > j ) {
							b.left = f + g - c.collisionWidth;
						} else {
							b.left = f;
						}
					}
				} else if ( i > 0 ) {
					b.left += i;
				} else if ( j > 0 ) {
					b.left -= j;
				} else {
					b.left = d(b.left - h, b.left);
				}
				a.ui.pos._trigger(b, c, "posCollided", "fitLeft");
			},
			top : function ( b, c ) {
				a.ui.pos._trigger(b, c, "posCollide", "fitTop");
				var e = c.within, f = e.isWindow ? e.scrollTop : e.offset.top, g = c.within.height,
				    h                                                            = b.top - c.collisionPosition.marginTop, i = f - h, j = h + c.collisionHeight - g - f, k;
				if ( c.collisionHeight > g ) {
					if ( i > 0 && j <= 0 ) {
						k = b.top + i + c.collisionHeight - g - f;
						b.top += i - k;
					} else if ( j > 0 && i <= 0 ) {
						b.top = f;
					} else {
						if ( i > j ) {
							b.top = f + g - c.collisionHeight;
						} else {
							b.top = f;
						}
					}
				} else if ( i > 0 ) {
					b.top += i;
				} else if ( j > 0 ) {
					b.top -= j;
				} else {
					b.top = d(b.top - h, b.top);
				}
				a.ui.pos._trigger(b, c, "posCollided", "fitTop");
			}
		},
		flip    : {
			left: function ( b, c ) {
				a.ui.pos._trigger(b, c, "posCollide", "flipLeft");
				var d = c.within, f = d.offset.left + d.scrollLeft, g = d.width,
				    h                                                 = d.isWindow ? d.scrollLeft : d.offset.left, i  = b.left - c.collisionPosition.marginLeft,
				    j                                                 = i - h, k                                      = i + c.collisionWidth - g - h,
				    l                                                 = c.my[ 0 ] === "left" ? -c.elemWidth : c.my[ 0 ] === "right" ? c.elemWidth : 0,
				    m                                                 = c.at[ 0 ] === "left" ? c.targetWidth : c.at[ 0 ] === "right" ? -c.targetWidth : 0,
				    n                                                 = -2 * c.offset[ 0 ], o, p;
				if ( j < 0 ) {
					o = b.left + l + m + n + c.collisionWidth - g - f;
					if ( o < 0 || o < e(j) ) {
						b.left += l + m + n;
					}
				} else if ( k > 0 ) {
					p = b.left - c.collisionPosition.marginLeft + l + m + n - h;
					if ( p > 0 || e(p) < k ) {
						b.left += l + m + n;
					}
				}
				a.ui.pos._trigger(b, c, "posCollided", "flipLeft");
			},
			top : function ( b, c ) {
				a.ui.pos._trigger(b, c, "posCollide", "flipTop");
				var d                                                                                         = c.within, f = d.offset.top + d.scrollTop, g = d.height,
				    h = d.isWindow ? d.scrollTop : d.offset.top, i = b.top - c.collisionPosition.marginTop, j = i - h,
				    k                                                                                         = i + c.collisionHeight - g - h, l = c.my[ 1 ] === "top",
				    m                                                                                         = l ? -c.elemHeight : c.my[ 1 ] === "bottom" ? c.elemHeight : 0,
				    n                                                                                         = c.at[ 1 ] === "top" ? c.targetHeight : c.at[ 1 ] === "bottom" ? -c.targetHeight : 0,
				    o                                                                                         = -2 * c.offset[ 1 ], p, q;
				if ( j < 0 ) {
					q = b.top + m + n + o + c.collisionHeight - g - f;
					if ( b.top + m + n + o > j && (q < 0 || q < e(j)) ) {
						b.top += m + n + o;
					}
				} else if ( k > 0 ) {
					p = b.top - c.collisionPosition.marginTop + m + n + o - h;
					if ( b.top + m + n + o > k && (p > 0 || e(p) < k) ) {
						b.top += m + n + o;
					}
				}
				a.ui.pos._trigger(b, c, "posCollided", "flipTop");
			}
		},
		flipfit : {
			left: function () {
				a.ui.pos.flip.left.apply(this, arguments);
				a.ui.pos.fit.left.apply(this, arguments);
			},
			top : function () {
				a.ui.pos.flip.top.apply(this, arguments);
				a.ui.pos.fit.top.apply(this, arguments);
			}
		}
	};
	(function () {
		var b, c, d, e, f, g = document.getElementsByTagName("body")[ 0 ], h = document.createElement("div");
		b = document.createElement(g ? "div" : "body");
		d = {
			visibility: "hidden",
			width     : 0,
			height    : 0,
			border    : 0,
			margin    : 0,
			background: "none"
		};
		if ( g ) {
			a.extend(d, {
				position: "absolute",
				left    : "-1000px",
				top     : "-1000px"
			});
		}
		for ( f in d ) {
			b.style[ f ] = d[ f ];
		}
		b.appendChild(h);
		c = g || document.documentElement;
		c.insertBefore(b, c.firstChild);
		h.style.cssText = "position: absolute; left: 10.7432222px;";
		e = a(h).offset().left;
		a.support.offsetFractions = e > 10 && e < 11;
		b.innerHTML = "";
		c.removeChild(b);
	})();
})(jQuery);

(function ( a ) {
	"use strict";
	if ( typeof define === "function" && define.amd ) {
		define([ "jquery" ], a);
	} else if ( window.jQuery && !window.jQuery.fn.iconpicker ) {
		a(window.jQuery);
	}
})(function ( a ) {
	"use strict";
	var b = {
		isEmpty      : function ( a ) {
			return a === false || a === "" || a === null || a === undefined;
		},
		isEmptyObject: function ( a ) {
			return this.isEmpty(a) === true || a.length === 0;
		},
		isElement    : function ( b ) {
			return a(b).length > 0;
		},
		isString     : function ( a ) {
			return typeof a === "string" || a instanceof String;
		},
		isArray      : function ( b ) {
			return a.isArray(b);
		},
		inArray      : function ( b, c ) {
			return a.inArray(b, c) !== -1;
		},
		throwError   : function ( a ) {
			throw "Font Awesome Icon Picker Exception: " + a;
		}
	};
	var c = function ( d, e ) {
		this._id = c._idCounter++;
		this.element = a(d).addClass("iconpicker-element");
		this._trigger("iconpickerCreate");
		this.options = a.extend({}, c.defaultOptions, this.element.data(), e);
		this.options.templates = a.extend({}, c.defaultOptions.templates, this.options.templates);
		this.options.originalPlacement = this.options.placement;
		this.container = b.isElement(this.options.container) ? a(this.options.container) : false;
		if ( this.container === false ) {
			if ( this.element.is(".dropdown-toggle") ) {
				this.container = a("~ .dropdown-menu:first", this.element);
			} else {
				this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element;
			}
		}
		this.container.addClass("iconpicker-container");
		if ( this.isDropdownMenu() ) {
			this.options.templates.search = false;
			this.options.templates.buttons = false;
			this.options.placement = "inline";
		}
		this.input = this.element.is("input,textarea") ? this.element.addClass("iconpicker-input") : false;
		if ( this.input === false ) {
			this.input = this.container.find(this.options.input);
			if ( !this.input.is("input,textarea") ) {
				this.input = false;
			}
		}
		this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
		if ( this.component.length === 0 ) {
			this.component = false;
		} else {
			this.component.find("i").addClass("iconpicker-component");
		}
		this._createPopover();
		this._createIconpicker();
		if ( this.getAcceptButton().length === 0 ) {
			this.options.mustAccept = false;
		}
		if ( this.isInputGroup() ) {
			this.container.parent().append(this.popover);
		} else {
			this.container.append(this.popover);
		}
		this._bindElementEvents();
		this._bindWindowEvents();
		this.update(this.options.selected);
		if ( this.isInline() ) {
			this.show();
		}
		this._trigger("iconpickerCreated");
	};
	c._idCounter = 0;
	c.defaultOptions = {
		title              : false,
		selected           : false,
		defaultValue       : false,
		placement          : "bottom",
		collision          : "none",
		animation          : true,
		hideOnSelect       : false,
		showFooter         : false,
		searchInFooter     : false,
		mustAccept         : false,
		selectedCustomClass: "bg-primary",
		icons              : [],
		fullClassFormatter : function ( a ) {
			return "fa " + a;
		},
		input              : "input,.iconpicker-input",
		inputSearch        : false,
		container          : false,
		component          : ".input-group-addon,.iconpicker-component",
		templates          : {
			popover       : '<div class="iconpicker-popover popover"><div class="arrow"></div>' + '<div class="popover-title"></div><div class="popover-content"></div></div>',
			footer        : '<div class="popover-footer"></div>',
			buttons       : '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' + ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
			search        : '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
			iconpicker    : '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
			iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>'
		}
	};
	c.batch = function ( b, c ) {
		var d = Array.prototype.slice.call(arguments, 2);
		return a(b).each(function () {
			var b = a(this).data("iconpicker");
			if ( !!b ) {
				b[ c ].apply(b, d);
			}
		});
	};
	c.prototype = {
		constructor             : c,
		options                 : {},
		_id                     : 0,
		_trigger                : function ( b, c ) {
			c = c || {};
			this.element.trigger(a.extend({
				type              : b,
				iconpickerInstance: this
			}, c));
		},
		_createPopover          : function () {
			this.popover = a(this.options.templates.popover);
			var c = this.popover.find(".popover-title");
			if ( !!this.options.title ) {
				c.append(a('<div class="popover-title-text">' + this.options.title + "</div>"));
			}
			if ( this.hasSeparatedSearchInput() && !this.options.searchInFooter ) {
				c.append(this.options.templates.search);
			} else if ( !this.options.title ) {
				c.remove();
			}
			if ( this.options.showFooter && !b.isEmpty(this.options.templates.footer) ) {
				var d = a(this.options.templates.footer);
				if ( this.hasSeparatedSearchInput() && this.options.searchInFooter ) {
					d.append(a(this.options.templates.search));
				}
				if ( !b.isEmpty(this.options.templates.buttons) ) {
					d.append(a(this.options.templates.buttons));
				}
				this.popover.append(d);
			}
			if ( this.options.animation === true ) {
				this.popover.addClass("fade");
			}
			return this.popover;
		},
		_createIconpicker       : function () {
			var b = this;
			this.iconpicker = a(this.options.templates.iconpicker);
			var c = function ( c ) {
				var d = a(this);
				if ( d.is("i") ) {
					d = d.parent();
				}
				b._trigger("iconpickerSelect", {
					iconpickerItem : d,
					iconpickerValue: b.iconpickerValue
				});
				if ( b.options.mustAccept === false ) {
					b.update(d.data("iconpickerValue"));
					b._trigger("iconpickerSelected", {
						iconpickerItem : this,
						iconpickerValue: b.iconpickerValue
					});
				} else {
					b.update(d.data("iconpickerValue"), true);
				}
				if ( b.options.hideOnSelect && b.options.mustAccept === false ) {
					b.hide();
				}
				c.preventDefault();
				return false;
			};
			for ( var d in this.options.icons ) {
				if ( typeof this.options.icons[ d ] === "string" ) {
					var e = a(this.options.templates.iconpickerItem);
					e.find("i").addClass(this.options.fullClassFormatter(this.options.icons[ d ]));
					e.data("iconpickerValue", this.options.icons[ d ]).on("click.iconpicker", c);
					this.iconpicker.find(".iconpicker-items").append(e.attr("title", "." + this.options.icons[ d ]));
				}
			}
			this.popover.find(".popover-content").append(this.iconpicker);
			return this.iconpicker;
		},
		_isEventInsideIconpicker: function ( b ) {
			var c = a(b.target);
			if ( (!c.hasClass("iconpicker-element") || c.hasClass("iconpicker-element") && !c.is(this.element)) && c.parents(".iconpicker-popover").length === 0 ) {
				return false;
			}
			return true;
		},
		_bindElementEvents      : function () {
			var c = this;
			this.getSearchInput().on("keyup.iconpicker", function () {
				c.filter(a(this).val().toLowerCase());
			});
			this.getAcceptButton().on("click.iconpicker", function () {
				var a = c.iconpicker.find(".iconpicker-selected").get(0);
				c.update(c.iconpickerValue);
				c._trigger("iconpickerSelected", {
					iconpickerItem : a,
					iconpickerValue: c.iconpickerValue
				});
				if ( !c.isInline() ) {
					c.hide();
				}
			});
			this.getCancelButton().on("click.iconpicker", function () {
				if ( !c.isInline() ) {
					c.hide();
				}
			});
			this.element.on("focus.iconpicker", function ( a ) {
				c.show();
				a.stopPropagation();
			});
			if ( this.hasComponent() ) {
				this.component.on("click.iconpicker", function () {
					c.toggle();
				});
			}
			if ( this.hasInput() ) {
				this.input.on("keyup.iconpicker", function ( d ) {
					if ( !b.inArray(d.keyCode, [
							38,
							40,
							37,
							39,
							16,
							17,
							18,
							9,
							8,
							91,
							93,
							20,
							46,
							186,
							190,
							46,
							78,
							188,
							44,
							86
						]) ) {
						c.update();
					} else {
						c._updateFormGroupStatus(c.getValid(this.value) !== false);
					}
					if ( c.options.inputSearch === true ) {
						c.filter(a(this).val().toLowerCase());
					}
				});
			}
		},
		_bindWindowEvents       : function () {
			var b = a(window.document);
			var c = this;
			var d = ".iconpicker.inst" + this._id;
			a(window).on("resize.iconpicker" + d + " orientationchange.iconpicker" + d, function ( a ) {
				if ( c.popover.hasClass("in") ) {
					c.updatePlacement();
				}
			});
			if ( !c.isInline() ) {
				b.on("mouseup" + d, function ( a ) {
					if ( !c._isEventInsideIconpicker(a) && !c.isInline() ) {
						c.hide();
					}
					a.stopPropagation();
					a.preventDefault();
					return false;
				});
			}
			return false;
		},
		_unbindElementEvents    : function () {
			this.popover.off(".iconpicker");
			this.element.off(".iconpicker");
			if ( this.hasInput() ) {
				this.input.off(".iconpicker");
			}
			if ( this.hasComponent() ) {
				this.component.off(".iconpicker");
			}
			if ( this.hasContainer() ) {
				this.container.off(".iconpicker");
			}
		},
		_unbindWindowEvents     : function () {
			a(window).off(".iconpicker.inst" + this._id);
			a(window.document).off(".iconpicker.inst" + this._id);
		},
		updatePlacement         : function ( b, c ) {
			b = b || this.options.placement;
			this.options.placement = b;
			c = c || this.options.collision;
			c = c === true ? "flip" : c;
			var d = {
				at       : "right bottom",
				my       : "right top",
				of       : this.hasInput() && !this.isInputGroup() ? this.input : this.container,
				collision: c === true ? "flip" : c,
				within   : window
			};
			this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner " + "rightTop right rightBottom bottomRight bottomRightCorner " + "bottom bottomLeft bottomLeftCorner leftBottom left leftTop");
			if ( typeof b === "object" ) {
				return this.popover.pos(a.extend({}, d, b));
			}
			switch ( b ) {
				case "inline": {
					d = false;
				}
					break;

				case "topLeftCorner": {
					d.my = "right bottom";
					d.at = "left top";
				}
					break;

				case "topLeft": {
					d.my = "left bottom";
					d.at = "left top";
				}
					break;

				case "top": {
					d.my = "center bottom";
					d.at = "center top";
				}
					break;

				case "topRight": {
					d.my = "right bottom";
					d.at = "right top";
				}
					break;

				case "topRightCorner": {
					d.my = "left bottom";
					d.at = "right top";
				}
					break;

				case "rightTop": {
					d.my = "left bottom";
					d.at = "right center";
				}
					break;

				case "right": {
					d.my = "left center";
					d.at = "right center";
				}
					break;

				case "rightBottom": {
					d.my = "left top";
					d.at = "right center";
				}
					break;

				case "bottomRightCorner": {
					d.my = "left top";
					d.at = "right bottom";
				}
					break;

				case "bottomRight": {
					d.my = "right top";
					d.at = "right bottom";
				}
					break;

				case "bottom": {
					d.my = "center top";
					d.at = "center bottom";
				}
					break;

				case "bottomLeft": {
					d.my = "left top";
					d.at = "left bottom";
				}
					break;

				case "bottomLeftCorner": {
					d.my = "right top";
					d.at = "left bottom";
				}
					break;

				case "leftBottom": {
					d.my = "right top";
					d.at = "left center";
				}
					break;

				case "left": {
					d.my = "right center";
					d.at = "left center";
				}
					break;

				case "leftTop": {
					d.my = "right bottom";
					d.at = "left center";
				}
					break;

				default: {
					return false;
				}
					break;
			}
			this.popover.css({
				display: this.options.placement === "inline" ? "" : "block"
			});
			if ( d !== false ) {
				this.popover.pos(d).css("maxWidth", a(window).width() - this.container.offset().left - 5);
			} else {
				this.popover.css({
					top     : "auto",
					right   : "auto",
					bottom  : "auto",
					left    : "auto",
					maxWidth: "none"
				});
			}
			this.popover.addClass(this.options.placement);
			return true;
		},
		_updateComponents       : function () {
			this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass);
			if ( this.iconpickerValue ) {
				this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass);
			}
			if ( this.hasComponent() ) {
				var a = this.component.find("i");
				if ( a.length > 0 ) {
					a.attr("class", this.options.fullClassFormatter(this.iconpickerValue));
				} else {
					this.component.html(this.getHtml());
				}
			}
		},
		_updateFormGroupStatus  : function ( a ) {
			if ( this.hasInput() ) {
				if ( a !== false ) {
					this.input.parents(".form-group:first").removeClass("has-error");
				} else {
					this.input.parents(".form-group:first").addClass("has-error");
				}
				return true;
			}
			return false;
		},
		getValid                : function ( c ) {
			if ( !b.isString(c) ) {
				c = "";
			}
			var d = c === "";
			c = a.trim(c);
			if ( b.inArray(c, this.options.icons) || d ) {
				return c;
			}
			return false;
		},
		setValue                : function ( a ) {
			var b = this.getValid(a);
			if ( b !== false ) {
				this.iconpickerValue = b;
				this._trigger("iconpickerSetValue", {
					iconpickerValue: b
				});
				return this.iconpickerValue;
			} else {
				this._trigger("iconpickerInvalid", {
					iconpickerValue: a
				});
				return false;
			}
		},
		getHtml                 : function () {
			return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
		},
		setSourceValue          : function ( a ) {
			a = this.setValue(a);
			if ( a !== false && a !== "" ) {
				if ( this.hasInput() ) {
					this.input.val(this.iconpickerValue);
				} else {
					this.element.data("iconpickerValue", this.iconpickerValue);
				}
				this._trigger("iconpickerSetSourceValue", {
					iconpickerValue: a
				});
			}
			return a;
		},
		getSourceValue          : function ( a ) {
			a = a || this.options.defaultValue;
			var b = a;
			if ( this.hasInput() ) {
				b = this.input.val();
			} else {
				b = this.element.data("iconpickerValue");
			}
			if ( b === undefined || b === "" || b === null || b === false ) {
				b = a;
			}
			return b;
		},
		hasInput                : function () {
			return this.input !== false;
		},
		isInputSearch           : function () {
			return this.hasInput() && this.options.inputSearch === true;
		},
		isInputGroup            : function () {
			return this.container.is(".input-group");
		},
		isDropdownMenu          : function () {
			return this.container.is(".dropdown-menu");
		},
		hasSeparatedSearchInput : function () {
			return this.options.templates.search !== false && !this.isInputSearch();
		},
		hasComponent            : function () {
			return this.component !== false;
		},
		hasContainer            : function () {
			return this.container !== false;
		},
		getAcceptButton         : function () {
			return this.popover.find(".iconpicker-btn-accept");
		},
		getCancelButton         : function () {
			return this.popover.find(".iconpicker-btn-cancel");
		},
		getSearchInput          : function () {
			return this.popover.find(".iconpicker-search");
		},
		filter                  : function ( c ) {
			if ( b.isEmpty(c) ) {
				this.iconpicker.find(".iconpicker-item").show();
				return a(false);
			} else {
				var d = [];
				this.iconpicker.find(".iconpicker-item").each(function () {
					var b = a(this);
					var e = b.attr("title").toLowerCase();
					var f = false;
					try {
						f = new RegExp(c, "g");
					} catch ( a ) {
						f = false;
					}
					if ( f !== false && e.match(f) ) {
						d.push(b);
						b.show();
					} else {
						b.hide();
					}
				});
				return d;
			}
		},
		show                    : function () {
			if ( this.popover.hasClass("in") ) {
				return false;
			}
			a.iconpicker.batch(a(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide");
			this._trigger("iconpickerShow");
			this.updatePlacement();
			this.popover.addClass("in");
			setTimeout(a.proxy(function () {
				this.popover.css("display", this.isInline() ? "" : "block");
				this._trigger("iconpickerShown");
			}, this), this.options.animation ? 300 : 1);
		},
		hide                    : function () {
			if ( !this.popover.hasClass("in") ) {
				return false;
			}
			this._trigger("iconpickerHide");
			this.popover.removeClass("in");
			setTimeout(a.proxy(function () {
				this.popover.css("display", "none");
				this.getSearchInput().val("");
				this.filter("");
				this._trigger("iconpickerHidden");
			}, this), this.options.animation ? 300 : 1);
		},
		toggle                  : function () {
			if ( this.popover.is(":visible") ) {
				this.hide();
			} else {
				this.show(true);
			}
		},
		update                  : function ( a, b ) {
			a = a ? a : this.getSourceValue(this.iconpickerValue);
			this._trigger("iconpickerUpdate");
			if ( b === true ) {
				a = this.setValue(a);
			} else {
				a = this.setSourceValue(a);
				this._updateFormGroupStatus(a !== false);
			}
			if ( a !== false ) {
				this._updateComponents();
			}
			this._trigger("iconpickerUpdated");
			return a;
		},
		destroy                 : function () {
			this._trigger("iconpickerDestroy");
			this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element");
			this._unbindElementEvents();
			this._unbindWindowEvents();
			a(this.popover).remove();
			this._trigger("iconpickerDestroyed");
		},
		disable                 : function () {
			if ( this.hasInput() ) {
				this.input.prop("disabled", true);
				return true;
			}
			return false;
		},
		enable                  : function () {
			if ( this.hasInput() ) {
				this.input.prop("disabled", false);
				return true;
			}
			return false;
		},
		isDisabled              : function () {
			if ( this.hasInput() ) {
				return this.input.prop("disabled") === true;
			}
			return false;
		},
		isInline                : function () {
			return this.options.placement === "inline" || this.popover.hasClass("inline");
		}
	};
	a.iconpicker = c;
	a.fn.iconpicker = function ( b ) {
		return this.each(function () {
			var d = a(this);
			if ( !d.data("iconpicker") ) {
				d.data("iconpicker", new c(this, typeof b === "object" ? b : {}));
			}
		});
	};
	c.defaultOptions.icons = [
		"rp-icon-ion-alert-circled",
		"rp-icon-ion-alert",
		"rp-icon-ion-android-add-circle",
		"rp-icon-ion-android-add",
		"rp-icon-ion-android-alarm-clock",
		"rp-icon-ion-android-alert",
		"rp-icon-ion-android-apps",
		"rp-icon-ion-android-archive",
		"rp-icon-ion-android-arrow-back",
		"rp-icon-ion-android-arrow-down",
		"rp-icon-ion-android-arrow-dropdown-circle",
		"rp-icon-ion-android-arrow-dropdown",
		"rp-icon-ion-android-arrow-dropleft-circle",
		"rp-icon-ion-android-arrow-dropleft",
		"rp-icon-ion-android-arrow-dropright-circle",
		"rp-icon-ion-android-arrow-dropright",
		"rp-icon-ion-android-arrow-dropup-circle",
		"rp-icon-ion-android-arrow-dropup",
		"rp-icon-ion-android-arrow-forward",
		"rp-icon-ion-android-arrow-up",
		"rp-icon-ion-android-attach",
		"rp-icon-ion-android-bar",
		"rp-icon-ion-android-bicycle",
		"rp-icon-ion-android-boat",
		"rp-icon-ion-android-bookmark",
		"rp-icon-ion-android-bulb",
		"rp-icon-ion-android-bus",
		"rp-icon-ion-android-calendar",
		"rp-icon-ion-android-call",
		"rp-icon-ion-android-camera",
		"rp-icon-ion-android-cancel",
		"rp-icon-ion-android-car",
		"rp-icon-ion-android-cart",
		"rp-icon-ion-android-chat",
		"rp-icon-ion-android-checkbox-blank",
		"rp-icon-ion-android-checkbox-outline-blank",
		"rp-icon-ion-android-checkbox-outline",
		"rp-icon-ion-android-checkbox",
		"rp-icon-ion-android-checkmark-circle",
		"rp-icon-ion-android-clipboard",
		"rp-icon-ion-android-close",
		"rp-icon-ion-android-cloud-circle",
		"rp-icon-ion-android-cloud-done",
		"rp-icon-ion-android-cloud-outline",
		"rp-icon-ion-android-cloud",
		"rp-icon-ion-android-color-palette",
		"rp-icon-ion-android-compass",
		"rp-icon-ion-android-contact",
		"rp-icon-ion-android-contacts",
		"rp-icon-ion-android-contract",
		"rp-icon-ion-android-create",
		"rp-icon-ion-android-delete",
		"rp-icon-ion-android-desktop",
		"rp-icon-ion-android-document",
		"rp-icon-ion-android-done-all",
		"rp-icon-ion-android-done",
		"rp-icon-ion-android-download",
		"rp-icon-ion-android-drafts",
		"rp-icon-ion-android-exit",
		"rp-icon-ion-android-expand",
		"rp-icon-ion-android-favorite-outline",
		"rp-icon-ion-android-favorite",
		"rp-icon-ion-android-film",
		"rp-icon-ion-android-folder-open",
		"rp-icon-ion-android-folder",
		"rp-icon-ion-android-funnel",
		"rp-icon-ion-android-globe",
		"rp-icon-ion-android-hand",
		"rp-icon-ion-android-hangout",
		"rp-icon-ion-android-happy",
		"rp-icon-ion-android-home",
		"rp-icon-ion-android-image",
		"rp-icon-ion-android-laptop",
		"rp-icon-ion-android-list",
		"rp-icon-ion-android-locate",
		"rp-icon-ion-android-lock",
		"rp-icon-ion-android-mail",
		"rp-icon-ion-android-map",
		"rp-icon-ion-android-menu",
		"rp-icon-ion-android-microphone-off",
		"rp-icon-ion-android-microphone",
		"rp-icon-ion-android-more-horizontal",
		"rp-icon-ion-android-more-vertical",
		"rp-icon-ion-android-navigate",
		"rp-icon-ion-android-notifications-none",
		"rp-icon-ion-android-notifications-off",
		"rp-icon-ion-android-notifications",
		"rp-icon-ion-android-open",
		"rp-icon-ion-android-options",
		"rp-icon-ion-android-people",
		"rp-icon-ion-android-person-add",
		"rp-icon-ion-android-person",
		"rp-icon-ion-android-phone-landscape",
		"rp-icon-ion-android-phone-portrait",
		"rp-icon-ion-android-pin",
		"rp-icon-ion-android-plane",
		"rp-icon-ion-android-playstore",
		"rp-icon-ion-android-print",
		"rp-icon-ion-android-radio-button-off",
		"rp-icon-ion-android-radio-button-on",
		"rp-icon-ion-android-refresh",
		"rp-icon-ion-android-remove-circle",
		"rp-icon-ion-android-remove",
		"rp-icon-ion-android-restaurant",
		"rp-icon-ion-android-sad",
		"rp-icon-ion-android-search",
		"rp-icon-ion-android-send",
		"rp-icon-ion-android-settings",
		"rp-icon-ion-android-share-alt",
		"rp-icon-ion-android-share",
		"rp-icon-ion-android-star-half",
		"rp-icon-ion-android-star-outline",
		"rp-icon-ion-android-star",
		"rp-icon-ion-android-stopwatch",
		"rp-icon-ion-android-subway",
		"rp-icon-ion-android-sunny",
		"rp-icon-ion-android-sync",
		"rp-icon-ion-android-textsms",
		"rp-icon-ion-android-time",
		"rp-icon-ion-android-train",
		"rp-icon-ion-android-unlock",
		"rp-icon-ion-android-upload",
		"rp-icon-ion-android-volume-down",
		"rp-icon-ion-android-volume-mute",
		"rp-icon-ion-android-volume-off",
		"rp-icon-ion-android-volume-up",
		"rp-icon-ion-android-walk",
		"rp-icon-ion-android-warning",
		"rp-icon-ion-android-watch",
		"rp-icon-ion-android-wifi",
		"rp-icon-ion-aperture",
		"rp-icon-ion-archive",
		"rp-icon-ion-arrow-down-a",
		"rp-icon-ion-arrow-down-b",
		"rp-icon-ion-arrow-down-c",
		"rp-icon-ion-arrow-expand",
		"rp-icon-ion-arrow-graph-down-left",
		"rp-icon-ion-arrow-graph-down-right",
		"rp-icon-ion-arrow-graph-up-left",
		"rp-icon-ion-arrow-graph-up-right",
		"rp-icon-ion-arrow-left-a",
		"rp-icon-ion-arrow-left-b",
		"rp-icon-ion-arrow-left-c",
		"rp-icon-ion-arrow-move",
		"rp-icon-ion-arrow-resize",
		"rp-icon-ion-arrow-return-left",
		"rp-icon-ion-arrow-return-right",
		"rp-icon-ion-arrow-right-a",
		"rp-icon-ion-arrow-right-b",
		"rp-icon-ion-arrow-right-c",
		"rp-icon-ion-arrow-shrink",
		"rp-icon-ion-arrow-swap",
		"rp-icon-ion-arrow-up-a",
		"rp-icon-ion-arrow-up-b",
		"rp-icon-ion-arrow-up-c",
		"rp-icon-ion-asterisk",
		"rp-icon-ion-at",
		"rp-icon-ion-backspace-outline",
		"rp-icon-ion-backspace",
		"rp-icon-ion-bag",
		"rp-icon-ion-battery-charging",
		"rp-icon-ion-battery-empty",
		"rp-icon-ion-battery-full",
		"rp-icon-ion-battery-half",
		"rp-icon-ion-battery-low",
		"rp-icon-ion-beaker",
		"rp-icon-ion-beer",
		"rp-icon-ion-bluetooth",
		"rp-icon-ion-bonfire",
		"rp-icon-ion-bookmark",
		"rp-icon-ion-bowtie",
		"rp-icon-ion-briefcase",
		"rp-icon-ion-bug",
		"rp-icon-ion-calculator",
		"rp-icon-ion-calendar",
		"rp-icon-ion-camera",
		"rp-icon-ion-card",
		"rp-icon-ion-cash",
		"rp-icon-ion-chatbox-working",
		"rp-icon-ion-chatbox",
		"rp-icon-ion-chatboxes",
		"rp-icon-ion-chatbubble-working",
		"rp-icon-ion-chatbubble",
		"rp-icon-ion-chatbubbles",
		"rp-icon-ion-checkmark-circled",
		"rp-icon-ion-checkmark-round",
		"rp-icon-ion-checkmark",
		"rp-icon-ion-chevron-down",
		"rp-icon-ion-chevron-left",
		"rp-icon-ion-chevron-right",
		"rp-icon-ion-chevron-up",
		"rp-icon-ion-clipboard",
		"rp-icon-ion-clock",
		"rp-icon-ion-close-circled",
		"rp-icon-ion-close-round",
		"rp-icon-ion-close",
		"rp-icon-ion-closed-captioning",
		"rp-icon-ion-cloud",
		"rp-icon-ion-code-download",
		"rp-icon-ion-code-working",
		"rp-icon-ion-code",
		"rp-icon-ion-coffee",
		"rp-icon-ion-compass",
		"rp-icon-ion-compose",
		"rp-icon-ion-connection-bars",
		"rp-icon-ion-contrast",
		"rp-icon-ion-crop",
		"rp-icon-ion-cube",
		"rp-icon-ion-disc",
		"rp-icon-ion-document-text",
		"rp-icon-ion-document",
		"rp-icon-ion-drag",
		"rp-icon-ion-earth",
		"rp-icon-ion-easel",
		"rp-icon-ion-edit",
		"rp-icon-ion-egg",
		"rp-icon-ion-eject",
		"rp-icon-ion-email-unread",
		"rp-icon-ion-email",
		"rp-icon-ion-erlenmeyer-flask-bubbles",
		"rp-icon-ion-erlenmeyer-flask",
		"rp-icon-ion-eye-disabled",
		"rp-icon-ion-eye",
		"rp-icon-ion-female",
		"rp-icon-ion-filing",
		"rp-icon-ion-film-marker",
		"rp-icon-ion-fireball",
		"rp-icon-ion-flag",
		"rp-icon-ion-flame",
		"rp-icon-ion-flash-off",
		"rp-icon-ion-flash",
		"rp-icon-ion-folder",
		"rp-icon-ion-fork-repo",
		"rp-icon-ion-fork",
		"rp-icon-ion-forward",
		"rp-icon-ion-funnel",
		"rp-icon-ion-gear-a",
		"rp-icon-ion-gear-b",
		"rp-icon-ion-grid",
		"rp-icon-ion-hammer",
		"rp-icon-ion-happy-outline",
		"rp-icon-ion-happy",
		"rp-icon-ion-headphone",
		"rp-icon-ion-heart-broken",
		"rp-icon-ion-heart",
		"rp-icon-ion-help-buoy",
		"rp-icon-ion-help-circled",
		"rp-icon-ion-help",
		"rp-icon-ion-home",
		"rp-icon-ion-icecream",
		"rp-icon-ion-image",
		"rp-icon-ion-images",
		"rp-icon-ion-information-circled",
		"rp-icon-ion-information",
		"rp-icon-ion-ionic",
		"rp-icon-ion-ios-alarm-outline",
		"rp-icon-ion-ios-alarm",
		"rp-icon-ion-ios-albums-outline",
		"rp-icon-ion-ios-albums",
		"rp-icon-ion-ios-americanfootball-outline",
		"rp-icon-ion-ios-americanfootball",
		"rp-icon-ion-ios-analytics-outline",
		"rp-icon-ion-ios-analytics",
		"rp-icon-ion-ios-arrow-back",
		"rp-icon-ion-ios-arrow-down",
		"rp-icon-ion-ios-arrow-forward",
		"rp-icon-ion-ios-arrow-left",
		"rp-icon-ion-ios-arrow-right",
		"rp-icon-ion-ios-arrow-thin-down",
		"rp-icon-ion-ios-arrow-thin-left",
		"rp-icon-ion-ios-arrow-thin-right",
		"rp-icon-ion-ios-arrow-thin-up",
		"rp-icon-ion-ios-arrow-up",
		"rp-icon-ion-ios-at-outline",
		"rp-icon-ion-ios-at",
		"rp-icon-ion-ios-barcode-outline",
		"rp-icon-ion-ios-barcode",
		"rp-icon-ion-ios-baseball-outline",
		"rp-icon-ion-ios-baseball",
		"rp-icon-ion-ios-basketball-outline",
		"rp-icon-ion-ios-basketball",
		"rp-icon-ion-ios-bell-outline",
		"rp-icon-ion-ios-bell",
		"rp-icon-ion-ios-body-outline",
		"rp-icon-ion-ios-body",
		"rp-icon-ion-ios-bolt-outline",
		"rp-icon-ion-ios-bolt",
		"rp-icon-ion-ios-book-outline",
		"rp-icon-ion-ios-book",
		"rp-icon-ion-ios-bookmarks-outline",
		"rp-icon-ion-ios-bookmarks",
		"rp-icon-ion-ios-box-outline",
		"rp-icon-ion-ios-box",
		"rp-icon-ion-ios-briefcase-outline",
		"rp-icon-ion-ios-briefcase",
		"rp-icon-ion-ios-browsers-outline",
		"rp-icon-ion-ios-browsers",
		"rp-icon-ion-ios-calculator-outline",
		"rp-icon-ion-ios-calculator",
		"rp-icon-ion-ios-calendar-outline",
		"rp-icon-ion-ios-calendar",
		"rp-icon-ion-ios-camera-outline",
		"rp-icon-ion-ios-camera",
		"rp-icon-ion-ios-cart-outline",
		"rp-icon-ion-ios-cart",
		"rp-icon-ion-ios-chatboxes-outline",
		"rp-icon-ion-ios-chatboxes",
		"rp-icon-ion-ios-chatbubble-outline",
		"rp-icon-ion-ios-chatbubble",
		"rp-icon-ion-ios-checkmark-empty",
		"rp-icon-ion-ios-checkmark-outline",
		"rp-icon-ion-ios-checkmark",
		"rp-icon-ion-ios-circle-filled",
		"rp-icon-ion-ios-circle-outline",
		"rp-icon-ion-ios-clock-outline",
		"rp-icon-ion-ios-clock",
		"rp-icon-ion-ios-close-empty",
		"rp-icon-ion-ios-close-outline",
		"rp-icon-ion-ios-close",
		"rp-icon-ion-ios-cloud-download-outline",
		"rp-icon-ion-ios-cloud-download",
		"rp-icon-ion-ios-cloud-outline",
		"rp-icon-ion-ios-cloud-upload-outline",
		"rp-icon-ion-ios-cloud-upload",
		"rp-icon-ion-ios-cloud",
		"rp-icon-ion-ios-cloudy-night-outline",
		"rp-icon-ion-ios-cloudy-night",
		"rp-icon-ion-ios-cloudy-outline",
		"rp-icon-ion-ios-cloudy",
		"rp-icon-ion-ios-cog-outline",
		"rp-icon-ion-ios-cog",
		"rp-icon-ion-ios-color-filter-outline",
		"rp-icon-ion-ios-color-filter",
		"rp-icon-ion-ios-color-wand-outline",
		"rp-icon-ion-ios-color-wand",
		"rp-icon-ion-ios-compose-outline",
		"rp-icon-ion-ios-compose",
		"rp-icon-ion-ios-contact-outline",
		"rp-icon-ion-ios-contact",
		"rp-icon-ion-ios-copy-outline",
		"rp-icon-ion-ios-copy",
		"rp-icon-ion-ios-crop-strong",
		"rp-icon-ion-ios-crop",
		"rp-icon-ion-ios-download-outline",
		"rp-icon-ion-ios-download",
		"rp-icon-ion-ios-drag",
		"rp-icon-ion-ios-email-outline",
		"rp-icon-ion-ios-email",
		"rp-icon-ion-ios-eye-outline",
		"rp-icon-ion-ios-eye",
		"rp-icon-ion-ios-fastforward-outline",
		"rp-icon-ion-ios-fastforward",
		"rp-icon-ion-ios-filing-outline",
		"rp-icon-ion-ios-filing",
		"rp-icon-ion-ios-film-outline",
		"rp-icon-ion-ios-film",
		"rp-icon-ion-ios-flag-outline",
		"rp-icon-ion-ios-flag",
		"rp-icon-ion-ios-flame-outline",
		"rp-icon-ion-ios-flame",
		"rp-icon-ion-ios-flask-outline",
		"rp-icon-ion-ios-flask",
		"rp-icon-ion-ios-flower-outline",
		"rp-icon-ion-ios-flower",
		"rp-icon-ion-ios-folder-outline",
		"rp-icon-ion-ios-folder",
		"rp-icon-ion-ios-football-outline",
		"rp-icon-ion-ios-football",
		"rp-icon-ion-ios-game-controller-a-outline",
		"rp-icon-ion-ios-game-controller-a",
		"rp-icon-ion-ios-game-controller-b-outline",
		"rp-icon-ion-ios-game-controller-b",
		"rp-icon-ion-ios-gear-outline",
		"rp-icon-ion-ios-gear",
		"rp-icon-ion-ios-glasses-outline",
		"rp-icon-ion-ios-glasses",
		"rp-icon-ion-ios-grid-view-outline",
		"rp-icon-ion-ios-grid-view",
		"rp-icon-ion-ios-heart-outline",
		"rp-icon-ion-ios-heart",
		"rp-icon-ion-ios-help-empty",
		"rp-icon-ion-ios-help-outline",
		"rp-icon-ion-ios-help",
		"rp-icon-ion-ios-home-outline",
		"rp-icon-ion-ios-home",
		"rp-icon-ion-ios-infinite-outline",
		"rp-icon-ion-ios-infinite",
		"rp-icon-ion-ios-information-empty",
		"rp-icon-ion-ios-information-outline",
		"rp-icon-ion-ios-information",
		"rp-icon-ion-ios-ionic-outline",
		"rp-icon-ion-ios-keypad-outline",
		"rp-icon-ion-ios-keypad",
		"rp-icon-ion-ios-lightbulb-outline",
		"rp-icon-ion-ios-lightbulb",
		"rp-icon-ion-ios-list-outline",
		"rp-icon-ion-ios-list",
		"rp-icon-ion-ios-location-outline",
		"rp-icon-ion-ios-location",
		"rp-icon-ion-ios-locked-outline",
		"rp-icon-ion-ios-locked",
		"rp-icon-ion-ios-loop-strong",
		"rp-icon-ion-ios-loop",
		"rp-icon-ion-ios-medical-outline",
		"rp-icon-ion-ios-medical",
		"rp-icon-ion-ios-medkit-outline",
		"rp-icon-ion-ios-medkit",
		"rp-icon-ion-ios-mic-off",
		"rp-icon-ion-ios-mic-outline",
		"rp-icon-ion-ios-mic",
		"rp-icon-ion-ios-minus-empty",
		"rp-icon-ion-ios-minus-outline",
		"rp-icon-ion-ios-minus",
		"rp-icon-ion-ios-monitor-outline",
		"rp-icon-ion-ios-monitor",
		"rp-icon-ion-ios-moon-outline",
		"rp-icon-ion-ios-moon",
		"rp-icon-ion-ios-more-outline",
		"rp-icon-ion-ios-more",
		"rp-icon-ion-ios-musical-note",
		"rp-icon-ion-ios-musical-notes",
		"rp-icon-ion-ios-navigate-outline",
		"rp-icon-ion-ios-navigate",
		"rp-icon-ion-ios-nutrition-outline",
		"rp-icon-ion-ios-nutrition",
		"rp-icon-ion-ios-paper-outline",
		"rp-icon-ion-ios-paper",
		"rp-icon-ion-ios-paperplane-outline",
		"rp-icon-ion-ios-paperplane",
		"rp-icon-ion-ios-partlysunny-outline",
		"rp-icon-ion-ios-partlysunny",
		"rp-icon-ion-ios-pause-outline",
		"rp-icon-ion-ios-pause",
		"rp-icon-ion-ios-paw-outline",
		"rp-icon-ion-ios-paw",
		"rp-icon-ion-ios-people-outline",
		"rp-icon-ion-ios-people",
		"rp-icon-ion-ios-person-outline",
		"rp-icon-ion-ios-person",
		"rp-icon-ion-ios-personadd-outline",
		"rp-icon-ion-ios-personadd",
		"rp-icon-ion-ios-photos-outline",
		"rp-icon-ion-ios-photos",
		"rp-icon-ion-ios-pie-outline",
		"rp-icon-ion-ios-pie",
		"rp-icon-ion-ios-pint-outline",
		"rp-icon-ion-ios-pint",
		"rp-icon-ion-ios-play-outline",
		"rp-icon-ion-ios-play",
		"rp-icon-ion-ios-plus-empty",
		"rp-icon-ion-ios-plus-outline",
		"rp-icon-ion-ios-plus",
		"rp-icon-ion-ios-pricetag-outline",
		"rp-icon-ion-ios-pricetag",
		"rp-icon-ion-ios-pricetags-outline",
		"rp-icon-ion-ios-pricetags",
		"rp-icon-ion-ios-printer-outline",
		"rp-icon-ion-ios-printer",
		"rp-icon-ion-ios-pulse-strong",
		"rp-icon-ion-ios-pulse",
		"rp-icon-ion-ios-rainy-outline",
		"rp-icon-ion-ios-rainy",
		"rp-icon-ion-ios-recording-outline",
		"rp-icon-ion-ios-recording",
		"rp-icon-ion-ios-redo-outline",
		"rp-icon-ion-ios-redo",
		"rp-icon-ion-ios-refresh-empty",
		"rp-icon-ion-ios-refresh-outline",
		"rp-icon-ion-ios-refresh",
		"rp-icon-ion-ios-reload",
		"rp-icon-ion-ios-reverse-camera-outline",
		"rp-icon-ion-ios-reverse-camera",
		"rp-icon-ion-ios-rewind-outline",
		"rp-icon-ion-ios-rewind",
		"rp-icon-ion-ios-rose-outline",
		"rp-icon-ion-ios-rose",
		"rp-icon-ion-ios-search-strong",
		"rp-icon-ion-ios-search",
		"rp-icon-ion-ios-settings-strong",
		"rp-icon-ion-ios-settings",
		"rp-icon-ion-ios-shuffle-strong",
		"rp-icon-ion-ios-shuffle",
		"rp-icon-ion-ios-skipbackward-outline",
		"rp-icon-ion-ios-skipbackward",
		"rp-icon-ion-ios-skipforward-outline",
		"rp-icon-ion-ios-skipforward",
		"rp-icon-ion-ios-snowy",
		"rp-icon-ion-ios-speedometer-outline",
		"rp-icon-ion-ios-speedometer",
		"rp-icon-ion-ios-star-half",
		"rp-icon-ion-ios-star-outline",
		"rp-icon-ion-ios-star",
		"rp-icon-ion-ios-stopwatch-outline",
		"rp-icon-ion-ios-stopwatch",
		"rp-icon-ion-ios-sunny-outline",
		"rp-icon-ion-ios-sunny",
		"rp-icon-ion-ios-telephone-outline",
		"rp-icon-ion-ios-telephone",
		"rp-icon-ion-ios-tennisball-outline",
		"rp-icon-ion-ios-tennisball",
		"rp-icon-ion-ios-thunderstorm-outline",
		"rp-icon-ion-ios-thunderstorm",
		"rp-icon-ion-ios-time-outline",
		"rp-icon-ion-ios-time",
		"rp-icon-ion-ios-timer-outline",
		"rp-icon-ion-ios-timer",
		"rp-icon-ion-ios-toggle-outline",
		"rp-icon-ion-ios-toggle",
		"rp-icon-ion-ios-trash-outline",
		"rp-icon-ion-ios-trash",
		"rp-icon-ion-ios-undo-outline",
		"rp-icon-ion-ios-undo",
		"rp-icon-ion-ios-unlocked-outline",
		"rp-icon-ion-ios-unlocked",
		"rp-icon-ion-ios-upload-outline",
		"rp-icon-ion-ios-upload",
		"rp-icon-ion-ios-videocam-outline",
		"rp-icon-ion-ios-videocam",
		"rp-icon-ion-ios-volume-high",
		"rp-icon-ion-ios-volume-low",
		"rp-icon-ion-ios-wineglass-outline",
		"rp-icon-ion-ios-wineglass",
		"rp-icon-ion-ios-world-outline",
		"rp-icon-ion-ios-world",
		"rp-icon-ion-ipad",
		"rp-icon-ion-iphone",
		"rp-icon-ion-ipod",
		"rp-icon-ion-jet",
		"rp-icon-ion-key",
		"rp-icon-ion-knife",
		"rp-icon-ion-laptop",
		"rp-icon-ion-leaf",
		"rp-icon-ion-levels",
		"rp-icon-ion-lightbulb",
		"rp-icon-ion-link",
		"rp-icon-ion-load-a",
		"rp-icon-ion-load-b",
		"rp-icon-ion-load-c",
		"rp-icon-ion-load-d",
		"rp-icon-ion-location",
		"rp-icon-ion-lock-combination",
		"rp-icon-ion-locked",
		"rp-icon-ion-log-in",
		"rp-icon-ion-log-out",
		"rp-icon-ion-loop",
		"rp-icon-ion-magnet",
		"rp-icon-ion-male",
		"rp-icon-ion-man",
		"rp-icon-ion-map",
		"rp-icon-ion-medkit",
		"rp-icon-ion-merge",
		"rp-icon-ion-mic-a",
		"rp-icon-ion-mic-b",
		"rp-icon-ion-mic-c",
		"rp-icon-ion-minus-circled",
		"rp-icon-ion-minus-round",
		"rp-icon-ion-minus",
		"rp-icon-ion-model-s",
		"rp-icon-ion-monitor",
		"rp-icon-ion-more",
		"rp-icon-ion-mouse",
		"rp-icon-ion-music-note",
		"rp-icon-ion-navicon-round",
		"rp-icon-ion-navicon",
		"rp-icon-ion-navigate",
		"rp-icon-ion-network",
		"rp-icon-ion-no-smoking",
		"rp-icon-ion-nuclear",
		"rp-icon-ion-outlet",
		"rp-icon-ion-paintbrush",
		"rp-icon-ion-paintbucket",
		"rp-icon-ion-paper-airplane",
		"rp-icon-ion-paperclip",
		"rp-icon-ion-pause",
		"rp-icon-ion-person-add",
		"rp-icon-ion-person-stalker",
		"rp-icon-ion-person",
		"rp-icon-ion-pie-graph",
		"rp-icon-ion-pin",
		"rp-icon-ion-pinpoint",
		"rp-icon-ion-pizza",
		"rp-icon-ion-plane",
		"rp-icon-ion-planet",
		"rp-icon-ion-play",
		"rp-icon-ion-playstation",
		"rp-icon-ion-plus-circled",
		"rp-icon-ion-plus-round",
		"rp-icon-ion-plus",
		"rp-icon-ion-podium",
		"rp-icon-ion-pound",
		"rp-icon-ion-power",
		"rp-icon-ion-pricetag",
		"rp-icon-ion-pricetags",
		"rp-icon-ion-printer",
		"rp-icon-ion-pull-request",
		"rp-icon-ion-qr-scanner",
		"rp-icon-ion-quote",
		"rp-icon-ion-radio-waves",
		"rp-icon-ion-record",
		"rp-icon-ion-refresh",
		"rp-icon-ion-reply-all",
		"rp-icon-ion-reply",
		"rp-icon-ion-ribbon-a",
		"rp-icon-ion-ribbon-b",
		"rp-icon-ion-sad-outline",
		"rp-icon-ion-sad",
		"rp-icon-ion-scissors",
		"rp-icon-ion-search",
		"rp-icon-ion-settings",
		"rp-icon-ion-share",
		"rp-icon-ion-shuffle",
		"rp-icon-ion-skip-backward",
		"rp-icon-ion-skip-forward",
		"rp-icon-ion-social-android-outline",
		"rp-icon-ion-social-android",
		"rp-icon-ion-social-angular-outline",
		"rp-icon-ion-social-angular",
		"rp-icon-ion-social-apple-outline",
		"rp-icon-ion-social-apple",
		"rp-icon-ion-social-bitcoin-outline",
		"rp-icon-ion-social-bitcoin",
		"rp-icon-ion-social-buffer-outline",
		"rp-icon-ion-social-buffer",
		"rp-icon-ion-social-chrome-outline",
		"rp-icon-ion-social-chrome",
		"rp-icon-ion-social-codepen-outline",
		"rp-icon-ion-social-codepen",
		"rp-icon-ion-social-css3-outline",
		"rp-icon-ion-social-css3",
		"rp-icon-ion-social-designernews-outline",
		"rp-icon-ion-social-designernews",
		"rp-icon-ion-social-dribbble-outline",
		"rp-icon-ion-social-dribbble",
		"rp-icon-ion-social-dropbox-outline",
		"rp-icon-ion-social-dropbox",
		"rp-icon-ion-social-euro-outline",
		"rp-icon-ion-social-euro",
		"rp-icon-ion-social-facebook-outline",
		"rp-icon-ion-social-facebook",
		"rp-icon-ion-social-foursquare-outline",
		"rp-icon-ion-social-foursquare",
		"rp-icon-ion-social-freebsd-devil",
		"rp-icon-ion-social-github-outline",
		"rp-icon-ion-social-github",
		"rp-icon-ion-social-google-outline",
		"rp-icon-ion-social-google",
		"rp-icon-ion-social-googleplus-outline",
		"rp-icon-ion-social-googleplus",
		"rp-icon-ion-social-hackernews-outline",
		"rp-icon-ion-social-hackernews",
		"rp-icon-ion-social-html5-outline",
		"rp-icon-ion-social-html5",
		"rp-icon-ion-social-instagram-outline",
		"rp-icon-ion-social-instagram",
		"rp-icon-ion-social-javascript-outline",
		"rp-icon-ion-social-javascript",
		"rp-icon-ion-social-linkedin-outline",
		"rp-icon-ion-social-linkedin",
		"rp-icon-ion-social-markdown",
		"rp-icon-ion-social-nodejs",
		"rp-icon-ion-social-octocat",
		"rp-icon-ion-social-pinterest-outline",
		"rp-icon-ion-social-pinterest",
		"rp-icon-ion-social-python",
		"rp-icon-ion-social-reddit-outline",
		"rp-icon-ion-social-reddit",
		"rp-icon-ion-social-rss-outline",
		"rp-icon-ion-social-rss",
		"rp-icon-ion-social-sass",
		"rp-icon-ion-social-skype-outline",
		"rp-icon-ion-social-skype",
		"rp-icon-ion-social-snapchat-outline",
		"rp-icon-ion-social-snapchat",
		"rp-icon-ion-social-tumblr-outline",
		"rp-icon-ion-social-tumblr",
		"rp-icon-ion-social-tux",
		"rp-icon-ion-social-twitch-outline",
		"rp-icon-ion-social-twitch",
		"rp-icon-ion-social-twitter-outline",
		"rp-icon-ion-social-twitter",
		"rp-icon-ion-social-usd-outline",
		"rp-icon-ion-social-usd",
		"rp-icon-ion-social-vimeo-outline",
		"rp-icon-ion-social-vimeo",
		"rp-icon-ion-social-whatsapp-outline",
		"rp-icon-ion-social-whatsapp",
		"rp-icon-ion-social-windows-outline",
		"rp-icon-ion-social-windows",
		"rp-icon-ion-social-wordpress-outline",
		"rp-icon-ion-social-wordpress",
		"rp-icon-ion-social-yahoo-outline",
		"rp-icon-ion-social-yahoo",
		"rp-icon-ion-social-yen-outline",
		"rp-icon-ion-social-yen",
		"rp-icon-ion-social-youtube-outline",
		"rp-icon-ion-social-youtube",
		"rp-icon-ion-soup-can-outline",
		"rp-icon-ion-soup-can",
		"rp-icon-ion-speakerphone",
		"rp-icon-ion-speedometer",
		"rp-icon-ion-spoon",
		"rp-icon-ion-star",
		"rp-icon-ion-stats-bars",
		"rp-icon-ion-steam",
		"rp-icon-ion-stop",
		"rp-icon-ion-thermometer",
		"rp-icon-ion-thumbsdown",
		"rp-icon-ion-thumbsup",
		"rp-icon-ion-toggle-filled",
		"rp-icon-ion-toggle",
		"rp-icon-ion-transgender",
		"rp-icon-ion-trash-a",
		"rp-icon-ion-trash-b",
		"rp-icon-ion-trophy",
		"rp-icon-ion-tshirt-outline",
		"rp-icon-ion-tshirt",
		"rp-icon-ion-umbrella",
		"rp-icon-ion-university",
		"rp-icon-ion-unlocked",
		"rp-icon-ion-upload",
		"rp-icon-ion-usb",
		"rp-icon-ion-videocamera",
		"rp-icon-ion-volume-high",
		"rp-icon-ion-volume-low",
		"rp-icon-ion-volume-medium",
		"rp-icon-ion-volume-mute",
		"rp-icon-ion-wand",
		"rp-icon-ion-waterdrop",
		"rp-icon-ion-wifi",
		"rp-icon-ion-wineglass",
		"rp-icon-ion-woman",
		"rp-icon-ion-wrench",
		"rp-icon-ion-xbox",
		"rp-icon-ruler",
		"rp-icon-bed2",
		"rp-icon-bath2",
		"rp-icon-garage",
		"rp-icon-brick",
		"rp-icon-pool",
		"rp-icon-floor",
		"rp-icon-compass3",
		"rp-icon-price-house",
		"rp-icon-rent",
		"rp-icon-painting",
		"rp-icon-safe-house",
		"rp-icon-user-add",
		"rp-icon-vertification",
		"rp-icon-worldmap",
		"rp-icon-mapmarker",
		"rp-icon-decotitle",
		"rp-icon-asterisk",
		"rp-icon-plus",
		"rp-icon-question",
		"rp-icon-minus",
		"rp-icon-glass",
		"rp-icon-music",
		"rp-icon-search",
		"rp-icon-envelope-o",
		"rp-icon-heart",
		"rp-icon-star",
		"rp-icon-star-o",
		"rp-icon-user",
		"rp-icon-film",
		"rp-icon-th-large",
		"rp-icon-th",
		"rp-icon-th-list",
		"rp-icon-check",
		"rp-icon-close",
		"rp-icon-remove",
		"rp-icon-times",
		"rp-icon-search-plus",
		"rp-icon-search-minus",
		"rp-icon-power-off",
		"rp-icon-signal",
		"rp-icon-cog",
		"rp-icon-gear",
		"rp-icon-trash-o",
		"rp-icon-home",
		"rp-icon-file-o",
		"rp-icon-clock-o",
		"rp-icon-road",
		"rp-icon-download",
		"rp-icon-arrow-circle-o-down",
		"rp-icon-arrow-circle-o-up",
		"rp-icon-inbox",
		"rp-icon-play-circle-o",
		"rp-icon-repeat",
		"rp-icon-rotate-right",
		"rp-icon-refresh",
		"rp-icon-list-alt",
		"rp-icon-lock",
		"rp-icon-flag",
		"rp-icon-headphones",
		"rp-icon-volume-off",
		"rp-icon-volume-down",
		"rp-icon-volume-up",
		"rp-icon-qrcode",
		"rp-icon-barcode",
		"rp-icon-tag",
		"rp-icon-tags",
		"rp-icon-book",
		"rp-icon-bookmark",
		"rp-icon-print",
		"rp-icon-camera",
		"rp-icon-font",
		"rp-icon-bold",
		"rp-icon-italic",
		"rp-icon-text-height",
		"rp-icon-text-width",
		"rp-icon-align-left",
		"rp-icon-align-center",
		"rp-icon-align-right",
		"rp-icon-align-justify",
		"rp-icon-list",
		"rp-icon-dedent",
		"rp-icon-outdent",
		"rp-icon-indent",
		"rp-icon-video-camera",
		"rp-icon-image",
		"rp-icon-photo",
		"rp-icon-picture-o",
		"rp-icon-pencil",
		"rp-icon-map-marker",
		"rp-icon-adjust",
		"rp-icon-tint",
		"rp-icon-edit",
		"rp-icon-pencil-square-o",
		"rp-icon-share-square-o",
		"rp-icon-check-square-o",
		"rp-icon-arrows",
		"rp-icon-step-backward",
		"rp-icon-fast-backward",
		"rp-icon-backward",
		"rp-icon-play",
		"rp-icon-pause",
		"rp-icon-stop",
		"rp-icon-forward",
		"rp-icon-fast-forward",
		"rp-icon-step-forward",
		"rp-icon-eject",
		"rp-icon-chevron-left",
		"rp-icon-chevron-right",
		"rp-icon-plus-circle",
		"rp-icon-minus-circle",
		"rp-icon-times-circle",
		"rp-icon-check-circle",
		"rp-icon-question-circle",
		"rp-icon-info-circle",
		"rp-icon-crosshairs",
		"rp-icon-times-circle-o",
		"rp-icon-check-circle-o",
		"rp-icon-ban",
		"rp-icon-arrow-left",
		"rp-icon-arrow-right",
		"rp-icon-arrow-up",
		"rp-icon-arrow-down",
		"rp-icon-mail-forward",
		"rp-icon-share",
		"rp-icon-expand",
		"rp-icon-compress",
		"rp-icon-exclamation-circle",
		"rp-icon-gift",
		"rp-icon-leaf",
		"rp-icon-fire",
		"rp-icon-eye",
		"rp-icon-eye-slash",
		"rp-icon-exclamation-triangle",
		"rp-icon-warning",
		"rp-icon-plane",
		"rp-icon-calendar",
		"rp-icon-random",
		"rp-icon-comment",
		"rp-icon-magnet",
		"rp-icon-chevron-up",
		"rp-icon-chevron-down",
		"rp-icon-retweet",
		"rp-icon-shopping-cart",
		"rp-icon-folder",
		"rp-icon-folder-open",
		"rp-icon-arrows-v",
		"rp-icon-arrows-h",
		"rp-icon-bar-chart",
		"rp-icon-bar-chart-o",
		"rp-icon-twitter-square",
		"rp-icon-facebook-square",
		"rp-icon-camera-retro",
		"rp-icon-key",
		"rp-icon-cogs",
		"rp-icon-gears",
		"rp-icon-comments",
		"rp-icon-thumbs-o-up",
		"rp-icon-thumbs-o-down",
		"rp-icon-star-half",
		"rp-icon-heart-o",
		"rp-icon-sign-out",
		"rp-icon-linkedin-square",
		"rp-icon-thumb-tack",
		"rp-icon-external-link",
		"rp-icon-sign-in",
		"rp-icon-trophy",
		"rp-icon-github-square",
		"rp-icon-upload",
		"rp-icon-lemon-o",
		"rp-icon-phone",
		"rp-icon-square-o",
		"rp-icon-bookmark-o",
		"rp-icon-phone-square",
		"rp-icon-twitter",
		"rp-icon-facebook",
		"rp-icon-facebook-f",
		"rp-icon-github",
		"rp-icon-unlock",
		"rp-icon-credit-card",
		"rp-icon-feed",
		"rp-icon-rss",
		"rp-icon-hdd-o",
		"rp-icon-bullhorn",
		"rp-icon-bell-o",
		"rp-icon-certificate",
		"rp-icon-hand-o-right",
		"rp-icon-hand-o-left",
		"rp-icon-hand-o-up",
		"rp-icon-hand-o-down",
		"rp-icon-arrow-circle-left",
		"rp-icon-arrow-circle-right",
		"rp-icon-arrow-circle-up",
		"rp-icon-arrow-circle-down",
		"rp-icon-globe",
		"rp-icon-wrench",
		"rp-icon-tasks",
		"rp-icon-filter",
		"rp-icon-briefcase",
		"rp-icon-arrows-alt",
		"rp-icon-group",
		"rp-icon-users",
		"rp-icon-chain",
		"rp-icon-link",
		"rp-icon-cloud",
		"rp-icon-flask",
		"rp-icon-cut",
		"rp-icon-scissors",
		"rp-icon-copy",
		"rp-icon-files-o",
		"rp-icon-paperclip",
		"rp-icon-floppy-o",
		"rp-icon-save",
		"rp-icon-square",
		"rp-icon-bars",
		"rp-icon-navicon",
		"rp-icon-reorder",
		"rp-icon-list-ul",
		"rp-icon-list-ol",
		"rp-icon-strikethrough",
		"rp-icon-underline",
		"rp-icon-table",
		"rp-icon-magic",
		"rp-icon-truck",
		"rp-icon-pinterest",
		"rp-icon-pinterest-square",
		"rp-icon-google-plus-square",
		"rp-icon-google-plus",
		"rp-icon-money",
		"rp-icon-caret-down",
		"rp-icon-caret-up",
		"rp-icon-caret-left",
		"rp-icon-caret-right",
		"rp-icon-columns",
		"rp-icon-sort",
		"rp-icon-unsorted",
		"rp-icon-sort-desc",
		"rp-icon-sort-down",
		"rp-icon-sort-asc",
		"rp-icon-sort-up",
		"rp-icon-envelope",
		"rp-icon-linkedin",
		"rp-icon-rotate-left",
		"rp-icon-undo",
		"rp-icon-gavel",
		"rp-icon-legal",
		"rp-icon-dashboard",
		"rp-icon-tachometer",
		"rp-icon-comment-o",
		"rp-icon-comments-o",
		"rp-icon-bolt",
		"rp-icon-flash",
		"rp-icon-sitemap",
		"rp-icon-umbrella",
		"rp-icon-clipboard",
		"rp-icon-paste",
		"rp-icon-lightbulb-o",
		"rp-icon-exchange",
		"rp-icon-cloud-download",
		"rp-icon-cloud-upload",
		"rp-icon-user-md",
		"rp-icon-stethoscope",
		"rp-icon-suitcase",
		"rp-icon-bell",
		"rp-icon-coffee",
		"rp-icon-cutlery",
		"rp-icon-file-text-o",
		"rp-icon-building-o",
		"rp-icon-hospital-o",
		"rp-icon-ambulance",
		"rp-icon-medkit",
		"rp-icon-fighter-jet",
		"rp-icon-beer",
		"rp-icon-h-square",
		"rp-icon-plus-square",
		"rp-icon-angle-double-left",
		"rp-icon-angle-double-right",
		"rp-icon-angle-double-up",
		"rp-icon-angle-double-down",
		"rp-icon-angle-left",
		"rp-icon-angle-right",
		"rp-icon-angle-up",
		"rp-icon-angle-down",
		"rp-icon-desktop",
		"rp-icon-laptop",
		"rp-icon-tablet",
		"rp-icon-mobile",
		"rp-icon-mobile-phone",
		"rp-icon-circle-o",
		"rp-icon-quote-left",
		"rp-icon-quote-right",
		"rp-icon-spinner",
		"rp-icon-circle",
		"rp-icon-mail-reply",
		"rp-icon-reply",
		"rp-icon-github-alt",
		"rp-icon-folder-o",
		"rp-icon-folder-open-o",
		"rp-icon-smile-o",
		"rp-icon-frown-o",
		"rp-icon-meh-o",
		"rp-icon-gamepad",
		"rp-icon-keyboard-o",
		"rp-icon-flag-o",
		"rp-icon-flag-checkered",
		"rp-icon-terminal",
		"rp-icon-code",
		"rp-icon-mail-reply-all",
		"rp-icon-reply-all",
		"rp-icon-star-half-empty",
		"rp-icon-star-half-full",
		"rp-icon-star-half-o",
		"rp-icon-location-arrow",
		"rp-icon-crop",
		"rp-icon-code-fork",
		"rp-icon-chain-broken",
		"rp-icon-unlink",
		"rp-icon-info",
		"rp-icon-exclamation",
		"rp-icon-superscript",
		"rp-icon-subscript",
		"rp-icon-eraser",
		"rp-icon-puzzle-piece",
		"rp-icon-microphone",
		"rp-icon-microphone-slash",
		"rp-icon-shield",
		"rp-icon-calendar-o",
		"rp-icon-fire-extinguisher",
		"rp-icon-rocket",
		"rp-icon-maxcdn",
		"rp-icon-chevron-circle-left",
		"rp-icon-chevron-circle-right",
		"rp-icon-chevron-circle-up",
		"rp-icon-chevron-circle-down",
		"rp-icon-html5",
		"rp-icon-css3",
		"rp-icon-anchor",
		"rp-icon-unlock-alt",
		"rp-icon-bullseye",
		"rp-icon-ellipsis-h",
		"rp-icon-ellipsis-v",
		"rp-icon-rss-square",
		"rp-icon-play-circle",
		"rp-icon-ticket",
		"rp-icon-minus-square",
		"rp-icon-minus-square-o",
		"rp-icon-level-up",
		"rp-icon-level-down",
		"rp-icon-check-square",
		"rp-icon-pencil-square",
		"rp-icon-external-link-square",
		"rp-icon-share-square",
		"rp-icon-compass",
		"rp-icon-caret-square-o-down",
		"rp-icon-toggle-down",
		"rp-icon-caret-square-o-up",
		"rp-icon-toggle-up",
		"rp-icon-caret-square-o-right",
		"rp-icon-toggle-right",
		"rp-icon-eur",
		"rp-icon-euro",
		"rp-icon-gbp",
		"rp-icon-dollar",
		"rp-icon-usd",
		"rp-icon-inr",
		"rp-icon-rupee",
		"rp-icon-cny",
		"rp-icon-jpy",
		"rp-icon-rmb",
		"rp-icon-yen",
		"rp-icon-rouble",
		"rp-icon-rub",
		"rp-icon-ruble",
		"rp-icon-krw",
		"rp-icon-won",
		"rp-icon-bitcoin",
		"rp-icon-btc",
		"rp-icon-file",
		"rp-icon-file-text",
		"rp-icon-sort-alpha-asc",
		"rp-icon-sort-alpha-desc",
		"rp-icon-sort-amount-asc",
		"rp-icon-sort-amount-desc",
		"rp-icon-sort-numeric-asc",
		"rp-icon-sort-numeric-desc",
		"rp-icon-thumbs-up",
		"rp-icon-thumbs-down",
		"rp-icon-youtube-square",
		"rp-icon-youtube",
		"rp-icon-xing",
		"rp-icon-xing-square",
		"rp-icon-youtube-play",
		"rp-icon-dropbox",
		"rp-icon-stack-overflow",
		"rp-icon-instagram",
		"rp-icon-flickr",
		"rp-icon-adn",
		"rp-icon-bitbucket",
		"rp-icon-bitbucket-square",
		"rp-icon-tumblr",
		"rp-icon-tumblr-square",
		"rp-icon-long-arrow-down",
		"rp-icon-long-arrow-up",
		"rp-icon-long-arrow-left",
		"rp-icon-long-arrow-right",
		"rp-icon-apple",
		"rp-icon-windows",
		"rp-icon-android",
		"rp-icon-linux",
		"rp-icon-dribbble",
		"rp-icon-skype",
		"rp-icon-foursquare",
		"rp-icon-trello",
		"rp-icon-female",
		"rp-icon-male",
		"rp-icon-gittip",
		"rp-icon-gratipay",
		"rp-icon-sun-o",
		"rp-icon-moon-o",
		"rp-icon-archive",
		"rp-icon-bug",
		"rp-icon-vk",
		"rp-icon-weibo",
		"rp-icon-renren",
		"rp-icon-pagelines",
		"rp-icon-stack-exchange",
		"rp-icon-arrow-circle-o-right",
		"rp-icon-arrow-circle-o-left",
		"rp-icon-caret-square-o-left",
		"rp-icon-toggle-left",
		"rp-icon-dot-circle-o",
		"rp-icon-wheelchair",
		"rp-icon-vimeo-square",
		"rp-icon-try",
		"rp-icon-turkish-lira",
		"rp-icon-plus-square-o",
		"rp-icon-space-shuttle",
		"rp-icon-slack",
		"rp-icon-envelope-square",
		"rp-icon-wordpress",
		"rp-icon-openid",
		"rp-icon-bank",
		"rp-icon-institution",
		"rp-icon-university",
		"rp-icon-graduation-cap",
		"rp-icon-mortar-board",
		"rp-icon-yahoo",
		"rp-icon-google",
		"rp-icon-reddit",
		"rp-icon-reddit-square",
		"rp-icon-stumbleupon-circle",
		"rp-icon-stumbleupon",
		"rp-icon-delicious",
		"rp-icon-digg",
		"rp-icon-pied-piper-pp",
		"rp-icon-pied-piper-alt",
		"rp-icon-drupal",
		"rp-icon-joomla",
		"rp-icon-language",
		"rp-icon-fax",
		"rp-icon-building",
		"rp-icon-child",
		"rp-icon-paw",
		"rp-icon-spoon",
		"rp-icon-cube",
		"rp-icon-cubes",
		"rp-icon-behance",
		"rp-icon-behance-square",
		"rp-icon-steam",
		"rp-icon-steam-square",
		"rp-icon-recycle",
		"rp-icon-automobile",
		"rp-icon-car",
		"rp-icon-cab",
		"rp-icon-taxi",
		"rp-icon-tree",
		"rp-icon-spotify",
		"rp-icon-deviantart",
		"rp-icon-soundcloud",
		"rp-icon-database",
		"rp-icon-file-pdf-o",
		"rp-icon-file-word-o",
		"rp-icon-file-excel-o",
		"rp-icon-file-powerpoint-o",
		"rp-icon-file-image-o",
		"rp-icon-file-photo-o",
		"rp-icon-file-picture-o",
		"rp-icon-file-archive-o",
		"rp-icon-file-zip-o",
		"rp-icon-file-audio-o",
		"rp-icon-file-sound-o",
		"rp-icon-file-movie-o",
		"rp-icon-file-video-o",
		"rp-icon-file-code-o",
		"rp-icon-vine",
		"rp-icon-codepen",
		"rp-icon-jsfiddle",
		"rp-icon-life-bouy",
		"rp-icon-life-buoy",
		"rp-icon-life-ring",
		"rp-icon-life-saver",
		"rp-icon-support",
		"rp-icon-circle-o-notch",
		"rp-icon-ra",
		"rp-icon-rebel",
		"rp-icon-resistance",
		"rp-icon-empire",
		"rp-icon-ge",
		"rp-icon-git-square",
		"rp-icon-git",
		"rp-icon-hacker-news",
		"rp-icon-y-combinator-square",
		"rp-icon-yc-square",
		"rp-icon-tencent-weibo",
		"rp-icon-qq",
		"rp-icon-wechat",
		"rp-icon-weixin",
		"rp-icon-paper-plane",
		"rp-icon-send",
		"rp-icon-paper-plane-o",
		"rp-icon-send-o",
		"rp-icon-history",
		"rp-icon-circle-thin",
		"rp-icon-header",
		"rp-icon-paragraph",
		"rp-icon-sliders",
		"rp-icon-share-alt",
		"rp-icon-share-alt-square",
		"rp-icon-bomb",
		"rp-icon-futbol-o",
		"rp-icon-soccer-ball-o",
		"rp-icon-tty",
		"rp-icon-binoculars",
		"rp-icon-plug",
		"rp-icon-slideshare",
		"rp-icon-twitch",
		"rp-icon-yelp",
		"rp-icon-newspaper-o",
		"rp-icon-wifi",
		"rp-icon-calculator",
		"rp-icon-paypal",
		"rp-icon-google-wallet",
		"rp-icon-cc-visa",
		"rp-icon-cc-mastercard",
		"rp-icon-cc-discover",
		"rp-icon-cc-amex",
		"rp-icon-cc-paypal",
		"rp-icon-cc-stripe",
		"rp-icon-bell-slash",
		"rp-icon-bell-slash-o",
		"rp-icon-trash",
		"rp-icon-copyright",
		"rp-icon-at",
		"rp-icon-eyedropper",
		"rp-icon-paint-brush",
		"rp-icon-birthday-cake",
		"rp-icon-area-chart",
		"rp-icon-pie-chart",
		"rp-icon-line-chart",
		"rp-icon-lastfm",
		"rp-icon-lastfm-square",
		"rp-icon-toggle-off",
		"rp-icon-toggle-on",
		"rp-icon-bicycle",
		"rp-icon-bus",
		"rp-icon-ioxhost",
		"rp-icon-angellist",
		"rp-icon-cc",
		"rp-icon-ils",
		"rp-icon-shekel",
		"rp-icon-sheqel",
		"rp-icon-meanpath",
		"rp-icon-buysellads",
		"rp-icon-connectdevelop",
		"rp-icon-dashcube",
		"rp-icon-forumbee",
		"rp-icon-leanpub",
		"rp-icon-sellsy",
		"rp-icon-shirtsinbulk",
		"rp-icon-simplybuilt",
		"rp-icon-skyatlas",
		"rp-icon-cart-plus",
		"rp-icon-cart-arrow-down",
		"rp-icon-diamond",
		"rp-icon-ship",
		"rp-icon-user-secret",
		"rp-icon-motorcycle",
		"rp-icon-street-view",
		"rp-icon-heartbeat",
		"rp-icon-venus",
		"rp-icon-mars",
		"rp-icon-mercury",
		"rp-icon-intersex",
		"rp-icon-transgender",
		"rp-icon-transgender-alt",
		"rp-icon-venus-double",
		"rp-icon-mars-double",
		"rp-icon-venus-mars",
		"rp-icon-mars-stroke",
		"rp-icon-mars-stroke-v",
		"rp-icon-mars-stroke-h",
		"rp-icon-neuter",
		"rp-icon-genderless",
		"rp-icon-facebook-official",
		"rp-icon-pinterest-p",
		"rp-icon-whatsapp",
		"rp-icon-server",
		"rp-icon-user-plus",
		"rp-icon-user-times",
		"rp-icon-bed",
		"rp-icon-hotel",
		"rp-icon-viacoin",
		"rp-icon-train",
		"rp-icon-subway",
		"rp-icon-medium",
		"rp-icon-y-combinator",
		"rp-icon-yc",
		"rp-icon-optin-monster",
		"rp-icon-opencart",
		"rp-icon-expeditedssl",
		"rp-icon-battery",
		"rp-icon-battery-4",
		"rp-icon-battery-full",
		"rp-icon-battery-3",
		"rp-icon-battery-three-quarters",
		"rp-icon-battery-2",
		"rp-icon-battery-half",
		"rp-icon-battery-1",
		"rp-icon-battery-quarter",
		"rp-icon-battery-0",
		"rp-icon-battery-empty",
		"rp-icon-mouse-pointer",
		"rp-icon-i-cursor",
		"rp-icon-object-group",
		"rp-icon-object-ungroup",
		"rp-icon-sticky-note",
		"rp-icon-sticky-note-o",
		"rp-icon-cc-jcb",
		"rp-icon-cc-diners-club",
		"rp-icon-clone",
		"rp-icon-balance-scale",
		"rp-icon-hourglass-o",
		"rp-icon-hourglass-1",
		"rp-icon-hourglass-start",
		"rp-icon-hourglass-2",
		"rp-icon-hourglass-half",
		"rp-icon-hourglass-3",
		"rp-icon-hourglass-end",
		"rp-icon-hourglass",
		"rp-icon-hand-grab-o",
		"rp-icon-hand-rock-o",
		"rp-icon-hand-paper-o",
		"rp-icon-hand-stop-o",
		"rp-icon-hand-scissors-o",
		"rp-icon-hand-lizard-o",
		"rp-icon-hand-spock-o",
		"rp-icon-hand-pointer-o",
		"rp-icon-hand-peace-o",
		"rp-icon-trademark",
		"rp-icon-registered",
		"rp-icon-creative-commons",
		"rp-icon-gg",
		"rp-icon-gg-circle",
		"rp-icon-tripadvisor",
		"rp-icon-odnoklassniki",
		"rp-icon-odnoklassniki-square",
		"rp-icon-get-pocket",
		"rp-icon-wikipedia-w",
		"rp-icon-safari",
		"rp-icon-chrome",
		"rp-icon-firefox",
		"rp-icon-opera",
		"rp-icon-internet-explorer",
		"rp-icon-television",
		"rp-icon-tv",
		"rp-icon-contao",
		"rp-icon-500px",
		"rp-icon-amazon",
		"rp-icon-calendar-plus-o",
		"rp-icon-calendar-minus-o",
		"rp-icon-calendar-times-o",
		"rp-icon-calendar-check-o",
		"rp-icon-industry",
		"rp-icon-map-pin",
		"rp-icon-map-signs",
		"rp-icon-map-o",
		"rp-icon-map",
		"rp-icon-commenting",
		"rp-icon-commenting-o",
		"rp-icon-houzz",
		"rp-icon-vimeo",
		"rp-icon-black-tie",
		"rp-icon-fonticons",
		"rp-icon-reddit-alien",
		"rp-icon-edge",
		"rp-icon-credit-card-alt",
		"rp-icon-codiepie",
		"rp-icon-modx",
		"rp-icon-fort-awesome",
		"rp-icon-usb",
		"rp-icon-product-hunt",
		"rp-icon-mixcloud",
		"rp-icon-scribd",
		"rp-icon-pause-circle",
		"rp-icon-pause-circle-o",
		"rp-icon-stop-circle",
		"rp-icon-stop-circle-o",
		"rp-icon-shopping-bag",
		"rp-icon-shopping-basket",
		"rp-icon-hashtag",
		"rp-icon-bluetooth",
		"rp-icon-bluetooth-b",
		"rp-icon-percent",
		"rp-icon-gitlab",
		"rp-icon-wpbeginner",
		"rp-icon-wpforms",
		"rp-icon-envira",
		"rp-icon-universal-access",
		"rp-icon-wheelchair-alt",
		"rp-icon-question-circle-o",
		"rp-icon-blind",
		"rp-icon-audio-description",
		"rp-icon-volume-control-phone",
		"rp-icon-braille",
		"rp-icon-assistive-listening-systems",
		"rp-icon-american-sign-language-interpreting",
		"rp-icon-asl-interpreting",
		"rp-icon-deaf",
		"rp-icon-deafness",
		"rp-icon-hard-of-hearing",
		"rp-icon-glide",
		"rp-icon-glide-g",
		"rp-icon-sign-language",
		"rp-icon-signing",
		"rp-icon-low-vision",
		"rp-icon-viadeo",
		"rp-icon-viadeo-square",
		"rp-icon-snapchat",
		"rp-icon-snapchat-ghost",
		"rp-icon-snapchat-square",
		"rp-icon-pied-piper",
		"rp-icon-first-order",
		"rp-icon-yoast",
		"rp-icon-themeisle",
		"rp-icon-google-plus-circle",
		"rp-icon-google-plus-official",
		"rp-icon-fa",
		"rp-icon-font-awesome",
		"rp-icon-handshake-o",
		"rp-icon-envelope-open",
		"rp-icon-envelope-open-o",
		"rp-icon-linode",
		"rp-icon-address-book",
		"rp-icon-address-book-o",
		"rp-icon-address-card",
		"rp-icon-vcard",
		"rp-icon-address-card-o",
		"rp-icon-vcard-o",
		"rp-icon-user-circle",
		"rp-icon-user-circle-o",
		"rp-icon-user-o",
		"rp-icon-id-badge",
		"rp-icon-drivers-license",
		"rp-icon-id-card",
		"rp-icon-drivers-license-o",
		"rp-icon-id-card-o",
		"rp-icon-quora",
		"rp-icon-free-code-camp",
		"rp-icon-telegram",
		"rp-icon-thermometer",
		"rp-icon-thermometer-4",
		"rp-icon-thermometer-full",
		"rp-icon-thermometer-3",
		"rp-icon-thermometer-three-quarters",
		"rp-icon-thermometer-2",
		"rp-icon-thermometer-half",
		"rp-icon-thermometer-1",
		"rp-icon-thermometer-quarter",
		"rp-icon-thermometer-0",
		"rp-icon-thermometer-empty",
		"rp-icon-shower",
		"rp-icon-bath",
		"rp-icon-bathtub",
		"rp-icon-s15",
		"rp-icon-podcast",
		"rp-icon-window-maximize",
		"rp-icon-window-minimize",
		"rp-icon-window-restore",
		"rp-icon-times-rectangle",
		"rp-icon-window-close",
		"rp-icon-times-rectangle-o",
		"rp-icon-window-close-o",
		"rp-icon-bandcamp",
		"rp-icon-grav",
		"rp-icon-etsy",
		"rp-icon-imdb",
		"rp-icon-ravelry",
		"rp-icon-eercast",
		"rp-icon-microchip",
		"rp-icon-snowflake-o",
		"rp-icon-superpowers",
		"rp-icon-wpexplorer",
		"rp-icon-meetup"
	];
});