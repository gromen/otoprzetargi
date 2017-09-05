(function ( $ ) {
	'use strict';
	if ( $('.rp-advanced-search-property-form').length > 0 ) {

		$('.rp-advanced-search-property-form').each(function () {

			var form_map              = $(this),
				id_map                = form_map.find('.rp-search-map').data('id'),
				zoom                  = parseInt(form_map.find('.rp-search-map').data('zoom')),
				lat                   = parseFloat(form_map.find('.rp-search-map').data('latitude')),
				lng                   = parseFloat(form_map.find('.rp-search-map').data('longitude')),
				source                = form_map.find('.rp-search-map').data('source'),
				drag_map              = form_map.find('.rp-search-map').data('drag-map'),
				fitbounds             = form_map.find('.rp-search-map').data('fitbounds'),
				disable_auto_complete = form_map.find('.rp-search-map').data('disable_auto_complete'),
				country_restriction   = form_map.find('.rp-search-map').data('country_restriction'),
				location_type         = form_map.find('.rp-search-map').data('location_type'),
				location              = {
					lat: lat,
					lng: lng
				},
				markers,
				bounds,
				map,
				infoBox,
				gmarkers              = [],
				current_place         = 0;

			/**
			 * Check enable box map
			 */
			if ( typeof id_map === 'undefined' ) {
				return false;
			}

			function RP_Box_Search_Property() {
				var mapCanvas = document.getElementById(id_map);
				var mapOptions = {
					flat             : false,
					noClear          : false,
					zoom             : zoom,
					scrollwheel      : false,
					draggable        : (Modernizr.touch ? false : drag_map),
					disableDefaultUI : true,
					center           : new google.maps.LatLng(location),
					mapTypeId        : google.maps.MapTypeId.ROADMAP,
					streetViewControl: true,
					mapTypeControl   : false,
					panControl       : false,
					rotateControl    : false,
					zoomControl      : false
				}
				map = new google.maps.Map(mapCanvas, mapOptions);
				google.maps.visualRefresh = true;

				google.maps.event.addListener(map, 'tilesloaded', function () {
					form_map.find('.gmap-loading').hide();
				});

				/**
				 * Event search input
				 */
				search_input();

				/**
				 * Event search address when active shortcode addvanced search
				 */
				search_address_for_shortcode();

				/**
				 * Create info box map
				 */
				var infoboxOptions = {
					content               : document.createElement("div"),
					disableAutoPan        : true,
					maxWidth              : 500,
					boxClass              : "rp-property-item-map-wrap",
					zIndex                : null,
					closeBoxMargin        : "0",
					// closeBoxURL: "",
					infoBoxClearance      : new google.maps.Size(1, 1),
					isHidden              : false,
					pane                  : "floatPane",
					enableEventPropagation: false
				};
				infoBox = new InfoBox(infoboxOptions);

				markers = $.parseJSON(RP_Map_Property.markers);

				/**
				 * Get list markers
				 */
				get_list_markers();

				/**
				 * Process event when changed form data
				 */
				request_data_form();

			}

			function search_input() {
				if ( typeof disable_auto_complete === 'undefined' || disable_auto_complete === '' ) {

					if ( $('#address_map').length > 0 ) {
						return false;
					}

					var find_address = document.getElementById('address_map');

					// Check value field address
					if ( find_address == '' || find_address == null ) {
						return false;
					}

					var options = {
						types: [ location_type ]
					};

					var autocomplete = new google.maps.places.Autocomplete(find_address, options);

					if ( country_restriction === 'all' ) {
						autocomplete.setComponentRestrictions([]);
					} else {
						autocomplete.setComponentRestrictions({ 'country': country_restriction });
					}

					/**
					 * Auto find address to map
					 */
					autocomplete.bindTo('bounds', map);
					autocomplete.addListener('place_changed', function () {
						infoBox.close();
						var places = autocomplete.getPlace();
						if ( !places.geometry ) {
							window.alert("Autocomplete's returned place contains no geometry");
							return;
						}

						var bounds = new google.maps.LatLngBounds();
						for ( var i = 0, place; place = places[ i ]; i++ ) {
							// Create a marker for each place.
							var _marker = new google.maps.Marker({
								map     : map,
								zoom    : zoom,
								title   : place.name,
								position: place.geometry.location
							});
							bounds.extend(place.geometry.location);
						}
						map.fitBounds(bounds);
						map.setZoom(13);

					});

				}

			}

			function search_address_for_shortcode() {
				var input = document.getElementById('gmap_search_input');
				var searchBox = new google.maps.places.SearchBox(input);
				google.maps.event.addListener(searchBox, 'places_changed', function () {
					var places = searchBox.getPlaces();

					if ( places.length == 0 ) {
						return;
					}
					var bounds = new google.maps.LatLngBounds();
					for ( var i = 0, place; place = places[ i ]; i++ ) {
						// Create a marker for each place.
						var _marker = new google.maps.Marker({
							map     : map,
							zoom    : 13,
							title   : place.name,
							position: place.geometry.location
						});
						bounds.extend(place.geometry.location);
					}
					map.fitBounds(bounds);
					map.setZoom(13);
				});
			}

			function get_list_markers() {

				if ( 'property' === source ) {

					if ( markers.length > 0 ) {
						var bounds = new google.maps.LatLngBounds();
						for ( var i = 0; i < markers.length; i++ ) {

							var marker = markers[ i ];
							var markerPlace = new google.maps.LatLng(marker.latitude, marker.longitude);

							var searchParams = getSearchParams();
							var points_map = {
								position: markerPlace,
								map     : map,
							}

							$.extend(points_map, marker);

							/**
							 * Create icon map
							 */
							var gmarker = new Marker(points_map);

							gmarkers.push(gmarker);

							if ( setMarkerVisible(gmarker, searchParams) ) {
								bounds.extend(gmarker.getPosition());
							}

							google.maps.event.addListener(gmarker, 'click', function ( e ) {
								RP_Click_Marker_Listener(this);
							});

						}
					}

				} else if ( 'idx' === source ) {
					var searchParams = getSearchParams();
					var bounds = new google.maps.LatLngBounds();
					if ( ('object' === typeof(dsidx)) && !$.isEmptyObject(dsidx.dataSets) ) {

						var idxCode = null;
						$.each(dsidx.dataSets, function ( i, e ) {
							idxCode = i;
						});
						for ( var i = 0; i < dsidx.dataSets[ idxCode ].length; i++ ) {

							var marker = dsidx.dataSets[ idxCode ][ i ];
							var markerPlace = new google.maps.LatLng(marker.Latitude, marker.Longitude);

							marker.latitude = marker.Latitude;
							marker.longitude = marker.Longitude;
							marker._bathrooms = parseInt(( marker.BedsShortString ).charAt(0));
							marker._bedrooms = parseInt(( marker.BathsShortString ).charAt(0));
							marker.url = RP_Map_Property.url_idx + marker.PrettyUriForUrl;
							marker.image = marker.PhotoUriBase + '0-medium.jpg';
							marker.price = parseFloat((marker.Price).replace(/[^\d.]/g, ''));
							marker.price_html = marker.Price;
							marker._area = marker.ImprovedSqFt + ' ' + RP_Property.area_unit;
							marker.icon_markers = 'rp-icon-home';

							var info_address = marker.ShortDescription.split(',');

							marker.city = info_address[ 1 ];
							marker.title = info_address[ 0 ] + ', ' + info_address[ 1 ];

							var points_map = {
								position: markerPlace,
								map     : map,
							}

							$.extend(points_map, marker);

							/**
							 * Create icon map
							 */
							var gmarker = new Marker(points_map);

							gmarkers.push(gmarker);

							if ( setMarkerVisible(gmarker, searchParams) ) {
								bounds.extend(gmarker.getPosition());
							}

							google.maps.event.addListener(gmarker, 'click', function ( e ) {
								RP_Click_Marker_Listener(this);
							});

						}

					} else {

						for ( var i = 0; i < RP_Source_IDX.length; i++ ) {

							var marker = RP_Source_IDX[ i ];
							var markerPlace = new google.maps.LatLng(marker.latitude, marker.longitude);

							var points_map = {
								position: markerPlace,
								map     : map,
							}

							$.extend(points_map, marker);

							/**
							 * Create icon map
							 */
							var gmarker = new Marker(points_map);

							gmarkers.push(gmarker);

							if ( setMarkerVisible(gmarker, searchParams) ) {
								bounds.extend(gmarker.getPosition());
							}

							google.maps.event.addListener(gmarker, 'click', function ( e ) {
								RP_Click_Marker_Listener(this);
							});

						}

					}

				}

				if ( fitbounds && bounds ) {
					map.fitBounds(bounds);
				}

			}

			function RP_Click_Marker_Listener( gmarker ) {
				infoBox.close();
				infoBox.setContent(create_info_property(gmarker));
				infoBox.open(map, gmarker);
				map.setCenter(gmarker.getPosition());
				map.panBy(50, -120);
			}

			/**
			 * This function create html info property
			 */
			function create_info_property( data_markers ) {
				var rp_property_area = '';
				if ( typeof data_markers._area !== 'undefined' ) {
					rp_property_area = '<span class="rp-area">' +
						'<i class="rp-icon-ruler"></i>' +
						data_markers._area +
						'</span>';
				}

				var rp_property_bedrooms = '';
				if ( typeof data_markers._bedrooms !== 'undefined' ) {
					rp_property_bedrooms = '<span class="rp-bed">' +
						'<i class="rp-icon-bed"></i>' +
						data_markers._bedrooms +
						'</span>';
				}

				var rp_property_bathrooms = '';
				if ( typeof data_markers._bathrooms !== 'undefined' ) {
					rp_property_bathrooms = '<span class="rp-bath">' +
						'<i class="rp-icon-bath"></i>' +
						data_markers._bathrooms +
						'</span>';
				}

				var rp_property_garages = '';
				if ( typeof data_markers._garages !== 'undefined' ) {
					rp_property_garages = '<span class="rp-storage">' +
						'<i class="rp-icon-garage"></i>' +
						data_markers._garages +
						'</span>';
				}

				return '<div class="rp-property-item-map">' +
					'<div class="rp-thumbnail">' +
					'<a target="_blank" href="' + data_markers.url + '" title="' + data_markers.title + '">' +
					'<img src="' + data_markers.image + '" alt="' + data_markers.title + '" />' +
					'</a>' +
					'</div>' +
					'<div class="rp-content">' +
					'<h4 class="rp-content-title">' +
					'<a target="_blank" href="' + data_markers.url + '" title="' + data_markers.title + '">' +
					data_markers.title +
					'</a>' +
					'</h4>' +
					'<div class="rp-info">' +
					rp_property_area + rp_property_bedrooms + rp_property_bathrooms + rp_property_garages +
					'</div>' +
					'<div class="rp-action">' +
					'<div class="rp-price">' +
					data_markers.price_html +
					'</div>' +
					'<a class="more" target="_blank" href="' + data_markers.url + '" title="' + data_markers.title + '"><i class="rp-icon-plus" aria-hidden="true"></i></a>' +
					'</div>' +
					'</div>' +
					'</div><!-- /.rp-property-item-map -->';
			}

			/**
			 * This function get form data
			 */
			function getSearchParams() {
				if ( $('.page-template-property-half-map .rp-form-halfmap').length > 0 ) {
					return $('.page-template-property-half-map .rp-form-halfmap').serializeArray();
				}
				return form_map.serializeArray();
			}

			/**
			 * This function process when changed form data
			 */
			function request_data_form() {
				if ( $('.page-template-property-half-map .rp-form-halfmap').length > 0 ) {

					$('.page-template-property-half-map .rp-form-halfmap').find('.rp-item-wrap').find(':input').on('change', function () {

						if ( typeof infoBox !== 'undefined' && infoBox !== null ) {
							infoBox.close();
						}
						if ( gmarkers.length > 0 ) {
							bounds = new google.maps.LatLngBounds();

							var searchParams = getSearchParams();
							var total_property = 0;
							for ( var i = 0; i < gmarkers.length; i++ ) {

								var gmarker = gmarkers[ i ];

								if ( setMarkerVisible(gmarker, searchParams) ) {
									bounds.extend(gmarker.getPosition());
									total_property++;
								}

							}

							if ( !bounds.isEmpty() ) {
								map.fitBounds(bounds);
							}

							var box_search_map = $('.page-template-property-half-map .rp-form-halfmap'),
								data_form      = box_search_map.serializeArray();

							data_form.push(
								{
									'name' : 'action',
									'value': 'loadmore_property_request'
								},
								{
									'name' : 'security',
									'value': RP_Map_Property.security
								},
								{
									'name' : 'style',
									'value': 'style-2'
								}
							);

							/**
							 * Process data
							 */
							$.ajax({
								url       : RP_Map_Property.ajax_url,
								type      : 'POST',
								dataType  : 'html',
								data      : data_form,
								beforeSend: function () {
									$('.rp-advanced-search-property').find('.rp-list-property').html('<div class="rp-loading-property"><i class="rp-icon-ion-ios-loop rp-icon-spin"></i></div>');
									$('.rp-advanced-search-property').find('.rp-map-footer').hide();
								},
								success   : function ( property ) {
									/**
									 * Remove loadmore item and update results property
									 */
									$('.rp-advanced-search-property').find('.rp-list-property').find('.rp-loading-property').remove();
									$('.rp-advanced-search-property').find('.rp-list-property').html(property).hide();
									$('.rp-advanced-search-property').find('.rp-list-property').slideToggle(1000);
									$('.rp-advanced-search-property').find('.rp-map-footer').slideToggle(1333);
								}
							})

						}
					});

				} else {

					form_map.find('.rp-item-wrap').find(':input').on('change', function () {
						if ( typeof infoBox !== 'undefined' && infoBox !== null ) {
							infoBox.close();
						}
						if ( gmarkers.length > 0 ) {
							bounds = new google.maps.LatLngBounds();

							var searchParams = getSearchParams();
							var total_property = 0;
							for ( var i = 0; i < gmarkers.length; i++ ) {

								var gmarker = gmarkers[ i ];

								if ( setMarkerVisible(gmarker, searchParams) ) {
									bounds.extend(gmarker.getPosition());
									total_property++;
								}

							}
							
							form_map.find('.show-filter-property').show().find('b').html(total_property);

							if ( !bounds.isEmpty() ) {
								map.fitBounds(bounds);
								if ( total_property < gmarkers.length - 1 ) {
									map.setZoom(14);
								}
							}
						}
					});

				}
			}

			/**
			 * Filters markers
			 */
			function setMarkerVisible( gmarker, searchParams ) {
				if ( gmarker == null || typeof gmarker === "undefined" ) {
					return false;
				}
				if ( searchParams == null || typeof searchParams === "undefined" ) {
					return false;
				}

				var end_point = false;
				$.each(searchParams, function ( name, value ) {

					if ( searchParams[ name ].name == null || typeof searchParams[ name ].name === "undefined" ) {
						return false;
					}

					if ( searchParams[ name ].value == null || typeof searchParams[ name ].value === "undefined" ) {
						return false;
					}

					var name_field = searchParams[ name ].name;
					var value_field = searchParams[ name ].value;

					if ( source === 'idx' ) {

						if ( name_field === 'address_map' && gmarker.title.match(new RegExp(value_field, 'g')) == null ) {
							gmarker.setVisible(false);
							end_point = true;
							return false;
						}
						if ( name_field === 'MinPrice' && parseInt(gmarker.price) < parseInt(value_field) ) {
							gmarker.setVisible(false);
							end_point = true;
							return false;
						}
						if ( name_field === 'MaxPrice' && parseInt(gmarker.price) > parseInt(value_field) ) {
							gmarker.setVisible(false);
							end_point = true;
							return false;
						}

						if ( name_field === 'idx-q-BathsMin' && parseInt(gmarker.rp_property_bathrooms) < parseInt(value_field) ) {
							gmarker.setVisible(false);
							end_point = true;
							return false;
						}

						if ( name_field === 'idx-q-BedsMin' && parseInt(gmarker.rp_property_bedrooms) < parseInt(value_field) ) {
							gmarker.setVisible(false);
							end_point = true;
							return false;
						}

					} else if ( source === 'property' ) {

						if ( name_field === 'status' ) {

						}
						if ( name_field === 'types' ) {

						}

						if ( value_field !== '' && value_field !== null && typeof gmarker[ name_field ] !== 'undefined' ) {

							if ( name_field === 'min_price' && parseInt(gmarker.price) < parseInt(value_field) ) {
								gmarker.setVisible(false);
								end_point = true;
								return false;
							}
							if ( name_field === 'max_price' && parseInt(gmarker.price) > parseInt(value_field) ) {
								gmarker.setVisible(false);
								end_point = true;
								return false;
							}
							if ( name_field === 'min_area' && parseInt(gmarker.area) < parseInt(value_field) ) {
								gmarker.setVisible(false);
								end_point = true;
								return false;
							}
							if ( name_field === 'max_area' && parseInt(gmarker.area) > parseInt(value_field) ) {
								gmarker.setVisible(false);
								end_point = true;
								return false;
							}

							/**
							 * Check field is array
							 */
							if ( $.isArray(gmarker[ name_field ]) ) {
								/**
								 * Check field status
								 */
								if ( name_field === 'offers' && (gmarker.offers).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}
								/**
								 * Check field types
								 */
								if ( name_field === 'types' && (gmarker.types).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}

								/**
								 * Check field location
								 */
								if ( name_field === 'location' && (gmarker.location).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}
								if ( name_field === 'city' && (gmarker.city).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}
								if ( name_field === 'neighborhood' && (gmarker.neighborhood).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}
								if ( name_field === 'zip' && (gmarker.zip).toString() !== value_field ) {
									gmarker.setVisible(false);
									end_point = true;
									return false;
								}
							} else if ( value_field !== gmarker[ name_field ] ) {
								gmarker.setVisible(false);
								end_point = true;
								return false;
							}

						}

					}

				});

				if ( end_point ) {
					gmarker.setVisible(false);
					return false;
				}

				gmarker.setVisible(true);
				return true;

			}

			function showMyPosition( pos ) {

				var MyPoint = new google.maps.LatLng(pos[ 0 ], pos[ 1 ]);
				map.setCenter(MyPoint);
				map.setZoom(13);

				var points_map = {
					position: MyPoint,
					map     : map
				}

				/**
				 * Create icon map
				 */
				var gmarker = new Marker(points_map);

				gmarkers.push(gmarker);

				var populationOptions = {
					strokeColor  : '#ffdf94',
					strokeOpacity: 0.6,
					strokeWeight : 1,
					fillColor    : '#ffdf94',
					fillOpacity  : 0.2,
					map          : map,
					center       : MyPoint,
					radius       : parseInt(5000, 10)
				};
				var cityCircle = new google.maps.Circle(populationOptions);

			}

			/**
			 * Function helpers: Get info address my location
			 * Sent request to site ipinfo.io and get json results
			 */
			function rp_check_my_location( map ) {
				infoBox.close();
				if ( navigator.geolocation ) {
					var latLong;
					if ( location.protocol === 'https:' ) {
						navigator.geolocation.getCurrentPosition(showMyPosition_original, errorCallback, { timeout: 10000 });
					} else {
						jQuery.getJSON("http://ipinfo.io", function ( ipinfo ) {
							latLong = ipinfo.loc.split(",");
							showMyPosition(latLong);
						});
					}

				} else {
					alert('can not find your location');
				}
			}

			/**
			 * Process event autocomplete input search
			 */
			var rp_auto_complete = function ( name_input ) {

				if ( $('body').find('input[name="' + name_input + '"]').length > 0 ) {

					var source_property = jQuery.parseJSON(RP_Property.data_autocomplete);
					if ( source == 'idx' ) {
						var source_property = RP_Source_IDX_autocomplete;
					}


					var rp_auto_filter = $('input[name="' + name_input + '"]').autocomplete({
						source   : source_property,
						delay    : 300,
						minLength: 1,
						change   : function () {
							// request_data_form();
						},
						select   : function () {
							request_data_form();
						}
					});

					rp_auto_filter.autocomplete('option', 'change').call(rp_auto_filter);
					$('input[name="' + name_input + '"]').trigger('keydown');

				}

			}

			rp_auto_complete('address_map');
			rp_auto_complete('keyword');

			/**
			 * Process event when click zoom map
			 */
			if ( document.getElementById(form_map.find('.gmap-zoom > .zoom-in').attr('id')) ) {
				google.maps.event.addDomListener(document.getElementById(form_map.find('.gmap-zoom > .zoom-in').attr('id')), 'click', function () {
					infoBox.close();
					var current = parseInt(map.getZoom(), 10);
					current++;
					if ( current > 20 ) {
						current = 15;
					}
					map.setZoom(current);
				});
			}

			if ( document.getElementById(form_map.find('.gmap-zoom > .miniature').attr('id')) ) {
				google.maps.event.addDomListener(document.getElementById(form_map.find('.gmap-zoom > .miniature').attr('id')), 'click', function () {
					infoBox.close();
					var current = parseInt(map.getZoom(), 10);
					current--;
					if ( current < 0 ) {
						current = 0;
					}
					map.setZoom(current);
				});
			}

			/**
			 * Process event when click view type map
			 */
			$(form_map.find('.map-view-type')).on('click', '> span', function ( event ) {
				event.preventDefault();
				infoBox.close();
				var type_view = $(this),
					map_type  = type_view.data('type');

				if ( map_type === 'roadmap' ) {
					map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
				} else if ( map_type === 'satellite' ) {
					map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
				} else if ( map_type === 'hybrid' ) {
					map.setMapTypeId(google.maps.MapTypeId.HYBRID);
				} else if ( map_type === 'terrain' ) {
					map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
				}

			});

			/**
			 * Process event when click find my location
			 */
			if ( form_map.find('.my-location') ) {
				google.maps.event.addDomListener(document.getElementById(form_map.find('.my-location').attr('id')), 'click', function () {
					infoBox.close();
					rp_check_my_location(map);
				});
			}

			/**
			 * Process event when click fullmap map
			 */
			$(form_map.find('.gmap-full')).click(function () {
				infoBox.close();
				if ( form_map.find('.rp-search-map').hasClass('fullmap') ) {
					$('body').removeClass('body-fullmap');
					form_map.removeClass('fullmap');
					form_map.find('.rp-search-map').removeClass('fullmap');
					$(this).empty().html('<i class="rp-icon-expand"></i> ' + RP_Map_Property.label_fullscreen);
					if ( Modernizr.touch ) {
						map.setOptions({ draggable: false });
					}
				} else {
					$('body').addClass('body-fullmap');
					form_map.addClass('fullmap');
					form_map.find('.rp-search-map').addClass('fullmap');
					$(this).empty().html('<i class="rp-icon-compress"></i> ' + RP_Map_Property.label_default);
					if ( Modernizr.touch ) {
						map.setOptions({ draggable: true });
					}
				}
				google.maps.event.trigger(map, "resize");
			});

			/**
			 * Process event when click prev map
			 */
			$(form_map.find('.gmap-prev')).click(function () {
				infoBox.close();
				current_place--;
				if ( current_place < 1 ) {
					current_place = gmarkers.length;
				}
				while ( gmarkers[ current_place - 1 ].visible === false ) {
					current_place--;
					if ( current_place > gmarkers.length ) {
						current_place = 1;
					}
				}
				if ( map.getZoom() < 15 ) {
					map.setZoom(15);
				}
				google.maps.event.trigger(gmarkers[ current_place - 1 ], 'click');
			});

			/**
			 * Process event when click next map
			 */
			$(form_map.find('.gmap-next')).click(function () {
				infoBox.close();
				current_place++;
				if ( current_place > gmarkers.length ) {
					current_place = 1;
				}
				while ( gmarkers[ current_place - 1 ].visible === false ) {
					current_place++;
					if ( current_place > gmarkers.length ) {
						current_place = 1;
					}
				}

				if ( map.getZoom() < 15 ) {
					map.setZoom(15);
				}
				google.maps.event.trigger(gmarkers[ current_place - 1 ], 'click');
			});

			google.maps.event.addDomListener(window, 'load', RP_Box_Search_Property);

		});

		/**
		 * Process event when clicking load more property
		 */
		if ( $('.rp-box-map').length > 0 ) {

			$('.rp-box-map').each(function ( index, el ) {

				var box_map        = $(this),
					box_search_map = box_map.closest('.rp-advanced-search-property'),
					data_form      = box_search_map.find('.rp-advanced-search-property-form').serializeArray();

				data_form.push(
					{
						'name' : 'action',
						'value': 'loadmore_property_request'
					},
					{
						'name' : 'security',
						'value': RP_Map_Property.security
					}
				);

				/**
				 * Process event when clicking show property results
				 */
				box_map.find('.rp-action-search-top').on('click', '.show-filter-property', function ( event ) {
					event.preventDefault();
					var box_search = $(this).closest('.rp-advanced-search-property');
					var data_search = box_search.find('.rp-advanced-search-property-form').serializeArray()
					data_search.push(
						{
							'name' : 'action',
							'value': 'loadmore_property_request'
						},
						{
							'name' : 'security',
							'value': RP_Map_Property.security
						}
					);
					/**
					 * Process data
					 */
					$.ajax({
						url       : RP_Map_Property.ajax_url,
						type      : 'POST',
						dataType  : 'html',
						data      : data_search,
						beforeSend: function () {
							box_search.find('.rp-results-property').show().html('<div class="rp-loading-property"><i class="rp-icon-ion-ios-loop rp-icon-spin"></i></div>');
							$('.rp-property-main').remove();
						},
						success   : function ( property ) {
							/**
							 * Remove loadmore item and update results property
							 */
							box_search.find('.rp-results-property').find('.rp-loading-property').remove();
							box_search.find('.rp-results-property').html(property).hide();
							box_search.find('.rp-results-property').slideToggle(1000);
						}
					})

				});

				/**
				 * Process event when clicking loadmore property results
				 */
				box_search_map.on('click', '.loadmore-results', function ( event ) {
					event.preventDefault();
					var loadmore     = $(this),
						current_page = parseInt(loadmore.data('current-page'));

					data_form.push(
						{
							'name' : 'current_page',
							'value': current_page + 1
						}
					);
					/**
					 * Process data
					 */
					$.ajax({
						url       : RP_Map_Property.ajax_url,
						type      : 'POST',
						dataType  : 'html',
						data      : data_form,
						beforeSend: function () {
							$('<div class="rp-loading-property"><i class="rp-icon-ion-ios-loop rp-icon-spin"></i></div>').appendTo(box_search_map.find('.rp-results-property'));
							box_search_map.find('.rp-results-property').find('.loadmore-results-wrap').remove();
						},
						success   : function ( property ) {
							/**
							 * Remove loadmore item and update results property
							 */
							box_search_map.find('.rp-results-property').find('.rp-loading-property').remove();
							$(property).appendTo(box_search_map.find('.rp-results-property'));
						}
					})

				});

			});

		}

		/**
		 * Process event orderby on page half mao
		 */
		if ( $('.rp-box-map').length > 0 ) {

			$('.rp-box-map').on('change', 'select[name="orderby"]', function ( event ) {
				event.preventDefault();
				var data_form = $('.rp-box-map').find('.sort-property').serializeArray();
				data_form.push(
					{
						'name' : 'action',
						'value': 'loadmore_property_request'
					},
					{
						'name' : 'security',
						'value': RP_Map_Property.security
					},
					{
						'name' : 'style',
						'value': 'style-2'
					},
					{
						'name' : 'show_loadmore_ajax',
						'value': false
					}
				);

				/**
				 * Process data
				 */
				$.ajax({
					url       : RP_Map_Property.ajax_url,
					type      : 'POST',
					dataType  : 'html',
					data      : data_form,
					beforeSend: function () {
						$('.rp-advanced-search-property').find('.rp-list-property').html('<div class="rp-loading-property"><i class="rp-icon-ion-ios-loop rp-icon-spin"></i></div>');
						$('.rp-advanced-search-property').find('.rp-map-footer').hide();
					},
					success   : function ( property ) {
						/**
						 * Remove loadmore item and update results property
						 */
						$('.rp-advanced-search-property').find('.rp-list-property').find('.rp-loading-property').remove();
						$('.rp-advanced-search-property').find('.rp-list-property').html(property).hide();
						$('.rp-advanced-search-property').find('.rp-list-property').slideToggle(1000);
						$('.rp-advanced-search-property').find('.rp-map-footer').slideToggle(1333);
					}
				})
			});

		}

	}

})(jQuery);