(function ( $ ) {
	'use strict';

	if ( $('.rp-gmap').length > 0 ) {

		$('.rp-gmap').each(function ( index, el ) {
			/**
			 * VAR
			 */
			var $$                   = $(this),
			    id                   = $$.data('id'),
			    property_id          = $$.data('property_id'),
			    drag_map             = $$.data('drag_map') || true,
			    fitbounds            = $$.data('fitbounds'),
			    url                  = $$.data('url'),
			    title                = $$.data('title'),
			    image                = $$.data('image'),
			    area                 = $$.data('area'),
			    bathrooms            = $$.data('bathrooms'),
			    bedrooms             = $$.data('bedrooms'),
			    garages              = $$.data('garages'),
			    price_html           = $$.data('price_html'),
			    zoom                 = parseInt(Noo_Map.zoom),
			    lat                  = parseFloat(Noo_Map.lat),
			    lng                  = parseFloat(Noo_Map.lng),
			    enable_auto_complete = Noo_Map.enable_auto_complete,
			    country_restriction  = Noo_Map.country_restriction,
			    location_type        = Noo_Map.location_type,
			    gmarkers             = [],
			    infoBox,
			    bounds,
			    map;

			/**
			 * Get value lat, lng
			 */
			var lat_current = $('#latitude').val(),
			    lng_current = $('#longitude').val();

			if ( typeof lat_current !== 'undefined' && lat_current !== '' ) {

				lat = lat_current;

			}

			if ( typeof lng_current !== 'undefined' && lng_current !== '' ) {

				lng = lng_current;

			}

			/**
			 * Process map
			 */
			function initMap() {
				map = new google.maps.Map(document.getElementById(id), {
					flat            : false,
					noClear         : false,
					zoom            : zoom,
					scrollwheel     : false,
					draggable       : drag_map,
					disableDefaultUI: false,
					center          : new google.maps.LatLng(lat, lng),
					mapTypeId       : google.maps.MapTypeId.ROADMAP
				});

				map.setZoom(zoom);

				google.maps.visualRefresh = true;

				var point = new google.maps.LatLng(lat, lng);

				Saved_Markers(map, point);

				var infowindow = new google.maps.InfoWindow();
				var marker = new google.maps.Marker({
					map        : map,
					anchorPoint: new google.maps.Point(0, 0)
				});

				var infoboxOptions = {
					content               : document.createElement("div"),
					disableAutoPan        : true,
					maxWidth              : 500,
					boxClass              : "rp-property-item-map-wrap",
					zIndex                : null,
					// closeBoxMargin: "0",
					// closeBoxURL: "",
					infoBoxClearance      : new google.maps.Size(1, 1),
					isHidden              : false,
					pane                  : "floatPane",
					enableEventPropagation: false
				};
				infoBox = new InfoBox(infoboxOptions);

				/**
				 * Auto find address to map
				 */
				if ( enable_auto_complete ) {

					var find_address = document.getElementById('rp-field-item-address');

					if ( typeof find_address !== 'undefined' && find_address !== '' && find_address !== null ) {

						var options = {
							types: [ location_type ]
						};

						var autocomplete = new google.maps.places.Autocomplete(find_address, options);

						if ( country_restriction === 'all' ) {
							autocomplete.setComponentRestrictions([]);
						} else {
							autocomplete.setComponentRestrictions({ 'country': country_restriction });
						}

						autocomplete.bindTo('bounds', map);
						autocomplete.addListener('place_changed', function () {
							infowindow.close();
							marker.setVisible(false);
							var place = autocomplete.getPlace();
							if ( !place.geometry ) {
								window.alert("Autocomplete's returned place contains no geometry");
								return;
							}

							/**
							 * If the place has a geometry, then present it on a map.
							 */
							if ( place.geometry.viewport ) {
								map.fitBounds(place.geometry.viewport);
							} else {
								map.setCenter(place.geometry.location);
							}


							marker.setPosition(place.geometry.location);
							marker.setVisible(true);

							var address = '';

							/**
							 * Auto get data
							 */

							if ( place.address_components ) {

								Find_Address(map, place.geometry.location, true);

							}

						});

					}

				}

				/**
				 * Event click on map
				 */
				google.maps.event.addListener(map, 'click', function ( event ) {
					infoBox.close();
					Find_Address(map, event.latLng, true);
				});

			}

			/**
			 * Save markers on map
			 */
			function Saved_Markers( map, location ) {

				Remove_Markers();

				Find_Address(map, location, false);

			}

			/**
			 * Remove all markers on map
			 */
			function Remove_Markers() {
				for ( var i = 0; i < gmarkers.length; i++ ) {
					gmarkers[ i ].setMap(null);
				}
				gmarkers = [];
			}

			/**
			 * Find address on map by location
			 */
			function Find_Address( map, location, auto_field ) {
				var geocoder = new google.maps.Geocoder;

				Remove_Markers();

				geocoder.geocode({ 'location': location }, function ( results, status ) {

					if ( status === 'OK' ) {

						if ( results[ 1 ] ) {

							var marker = new Marker({
								position      : location,
								map           : map,
								icon          : {
									path        : MAP_PIN,
									fillColor   : Noo_Map.background_map,
									fillOpacity : 1,
									strokeColor : '',
									strokeWeight: 0
								},
								map_icon_label: '<span class="noo-icon-map fa fa-home" style="color: ' + Noo_Map.background_map + '"></span>'
							});

							gmarkers.push(marker);

							var bounds = new google.maps.LatLngBounds();
							bounds.extend(location);

							map.fitBounds(bounds);
							map.setZoom(zoom);

							if ( typeof property_id !== 'undefined' ) {

								var data_markers = {
									url                  : url,
									title                : title,
									image                : image,
									rp_property_area     : area,
									rp_property_bedrooms : bedrooms,
									rp_property_bathrooms: bathrooms,
									rp_property_garages  : garages,
									price_html           : price_html
								}
								infoBox.setContent(create_info_property(data_markers));

							} else {

								infoBox.setContent(
									'<strong>Latitude:</strong> ' + location.lat() +
									'<br /><strong>Longitude:</strong> ' + location.lng()
								);

							}

							infoBox.open(map, marker);

							if ( !auto_field ) {
								return;
							}

							/**
							 * Set info to field
							 */
							var list_results = results[ 1 ][ 'address_components' ],
							    city         = false,
							    neighborhood = false,
							    zip          = false,
							    state        = false;

							$.each(list_results, function ( index, item ) {
								if ( typeof item[ 'types' ][ 0 ] !== 'undefined' ) {

									switch ( item[ 'types' ][ 0 ] ) {
										case 'country':
											$('option[value=' + item[ 'short_name' ] + ']').attr('selected', true);
											break;

										case 'locality':
											$('#rp-field-item-city').val(item[ 'long_name' ]);
											city = true;
											break;

										case 'neighborhood':
											$('#rp-field-item-neighborhood').val(item[ 'long_name' ]);
											neighborhood = true;
											break;

										case 'postal_code':
											$('#rp-field-item-zip').val(item[ 'long_name' ]);
											zip = true;
											break;

										case 'administrative_area_level_1':
											$('#rp-field-item-state').val(item[ 'long_name' ]);
											state = true;
											break;
									}

								}

							});

							if ( !city ) {
								$('#rp-field-item-city').val('');
							}

							if ( !neighborhood ) {
								$('#rp-field-item-neighborhood').val('');
							}

							if ( !state ) {
								$('#rp-field-item-state').val('');
							}

							if ( !zip ) {
								$('#rp-field-item-zip').val('');
							}

							$('#rp-field-item-latitude').val(location.lat());
							$('#rp-field-item-longitude').val(location.lng());

						} else {
							window.alert(Noo_Map.no_results);
						}
					} else {
						window.alert(Noo_Map.geo_fail + status);
					}

				});
			}

			/**
			 * This function create html info property
			 */
			function create_info_property( data_markers ) {
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
					'<span class="rp-area">' +
					'<i class="rp-icon-ruler"></i>' +
					data_markers.rp_property_area +
					'</span>' +
					'<span class="rp-bed">' +
					'<i class="rp-icon-bed"></i>' +
					data_markers.rp_property_bedrooms +
					'</span>' +
					'<span class="rp-bath">' +
					'<i class="rp-icon-bath"></i>' +
					data_markers.rp_property_bathrooms +
					'</span>' +
					'<span class="rp-storage">' +
					'<i class="rp-icon-garage"></i>' +
					data_markers.rp_property_garages +
					'</span>' +
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
			 * Code to support demo color selector
			 */
			if ( $('.noo-sw-section').length > 0 && $.cookie !== "undefined" ) {
				if ( $.cookie('noo-selector-color') != null ) {
					Noo_Map.background_map = $.cookie('noo-selector-color');
				}

				$(document).bind('noo-color-changed', function () {
					var markerColor = ( $.cookie('noo-selector-color') != null ) ? $.cookie('noo-selector-color') : '#114a82';
					if ( $.cookie('noo-selector-color') != null ) {
						for ( var i = 0; i < gmarkers.length; i++ ) {

							gmarkers[ i ].setIcon({
								path        : MAP_PIN,
								fillColor   : markerColor,
								fillOpacity : 1,
								strokeColor : '',
								strokeWeight: 0
							});
						}
					}
				});
			}

			google.maps.event.addDomListener(window, 'load', initMap);

		});
	}
})(jQuery);
