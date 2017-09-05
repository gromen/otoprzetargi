(function ( $ ) {
    'use strict';
    
    if ( $('.googleMap').length > 0 ) {

        $('.googleMap').each(function(index, el) {
            /**
             * VAR
             */
                var $$          = $(this),
                    id          = $$.data('id'),
                    drag_map    = $$.data('drag_map') || true,
                    fitbounds   = $$.data('fitbounds'),
                    title       = $$.data('title'),
                    desc        = $$.data('desc'),
                    gicon       = $$.data('icon'),
                    zoom        = parseInt( NooMapVars.zoom ),
                    lat         = parseFloat( NooMapVars.lat ),
                    lng         = parseFloat( NooMapVars.lng ),
                    gmarkers    = [],
                    infoBox,
                    map;

                /**
                 * Get value lat, lng
                 */
                    var lat_current = $$.data('lat'),
                        lng_current = $$.data('lon');

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
                    map = new google.maps.Map(document.getElementById( id ), {
                        flat: false,
                        noClear: false,
                        zoom: zoom,
                        scrollwheel: false,
                        draggable: drag_map,
                        disableDefaultUI: false,
                        center: new google.maps.LatLng( lat, lng),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    google.maps.visualRefresh = true;
    
                    var point = new google.maps.LatLng( lat, lng);
                    
                    Saved_Markers(map, point);

                    var infowindow       = new google.maps.InfoWindow();
                    var marker           = new google.maps.Marker({
                        map: map,
                        anchorPoint: new google.maps.Point(0, 0)
                    });

                    var infoboxOptions = {
                            content: document.createElement("div"),
                            disableAutoPan: true,
                            maxWidth: 500,
                            boxClass: "noo-map-item-wrap",
                            zIndex: null,
                            // closeBoxMargin: "0",
                            // closeBoxURL: "",
                            infoBoxClearance: new google.maps.Size(1, 1),
                            isHidden: false,
                            pane: "floatPane",
                            enableEventPropagation: false                   
                        };               
                        infoBox = new InfoBox( infoboxOptions );

                }

                /**
                 * Save markers on map
                 */
                    function Saved_Markers(map, location) {

                        Remove_Markers();

                        Find_Address( map, location, false );

                    }

                /**
                 * Remove all markers on map
                 */
                    function Remove_Markers(){
                        for (var i = 0; i < gmarkers.length; i++){
                            gmarkers[i].setMap(null);
                        }
                        gmarkers = [];
                    }

                /**
                 * Find address on map by location
                 */
                    function Find_Address(map, location, auto_field) {
                        var geocoder   = new google.maps.Geocoder;
                        
                        Remove_Markers();
                        
                        geocoder.geocode({'location': location}, function(results, status) {
                        
                            infoBox.close();

                            if (status === 'OK') {

                                if (results[1]) {

                                    map.setZoom(zoom);

                                    var marker = new Marker({
                                        position: location,
                                        map: map,
                                        icon: {
                                            path: MAP_PIN,
                                            fillColor: NooMapVars.background_map,
                                            fillOpacity: 1,
                                            strokeColor: '',
                                            strokeWeight: 0
                                        },
                                        map_icon_label: '<span class="noo-icon-map ' + gicon + '" style="color: ' + NooMapVars.background_map + '"></span>'
                                    });

                                    gmarkers.push(marker);
                                    
                                    infoBox.setContent(
                                        '<p><strong> ' + title + '</strong></p>' + 
                                        '<p>' + desc + '</p>'
                                    );


                                    infoBox.open(map, marker);

                                    if ( !auto_field ) return;
                                } else {
                                    window.alert( NooMapVars.no_results );
                                }
                            } else {
                                window.alert( NooMapVars.geo_fail + status );
                            }

                        });
                    }

                /**
                 * Pin symbol icon markers to map
                 */
                function pinSymbol(color) {
                  return {
                    path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z',
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: '#000',
                    strokeWeight: 2,
                    scale: 2
                  };
                }

            google.maps.event.addDomListener(window, 'load', initMap);

        });
    }
})( jQuery );