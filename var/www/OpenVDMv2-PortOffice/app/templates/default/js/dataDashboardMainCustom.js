$(function () {
    'use strict';
    
    var MAPPROXY_DIR = '/mapproxy';
    
    function displayLatestGeoJSON(dataType) {
        var getVisualizerDataURL = siteRoot + 'api/dashboardData/getLatestVisualizerDataByType/' + cruiseID + '/' + dataType;
        $.getJSON(getVisualizerDataURL, function (data, status) {
            if (status === 'success' && data !== null) {

                var placeholder = '#' + dataType + '-placeholder';
                if ('error' in data) {
                    $(placeholder).html('<strong>Error: ' + data.error + '</strong>');
                } else if (data.length > 0) {
                    //Get the last coordinate from the latest trackline
                    var lastCoordinate = data[0].features[0].geometry.coordinates[data[0].features[0].geometry.coordinates.length - 1],
                        latLng = L.latLng(lastCoordinate[1], lastCoordinate[0]);
                    
                    if (lastCoordinate[0] < 0) {
                        latLng = latLng.wrap(360, 0);
                    } else {
                        latLng = latLng.wrap();
                    }
                    
                    // Add latest trackline (GeoJSON)
                    var ggaData = L.geoJson(data[0], {
                        style: { weight: 3 },
                        coordsToLatLng: function (coords) {
                            var longitude = coords[0],
                                latitude = coords[1];

                            var latlng = L.latLng(latitude, longitude);

                            if (longitude < 0) {
                                return latlng.wrap(360, 0);
                            } else {
                                return latlng.wrap();
                            }
                        }
                    }),
                        mapBounds = ggaData.getBounds();
                    
                    mapBounds.extend(latLng);

                    //Build the map
                    var mapdb = L.map(placeholder.split('#')[1], {
                        //maxZoom: 13,
                        zoomControl: false,
                        dragging: false,
                        doubleClickZoom: false,
                        touchZoom: false,
                        scrollWheelZoom: false
                    }).fitBounds(mapBounds).zoomOut(1);
                    
                    mapdb.on('click', function(e) {
                        window.location.href = siteRoot + 'dataDashboard/customTab/' + subPages[dataType] + '#' + dataType;
                    });

                    //Add basemap layer, use ESRI Oceans Base Layer
                    L.esri.basemapLayer("Oceans", { minZoom:1, maxNativeZoom:9 }).addTo(mapdb);
                    //L.tileLayer(window.location.origin + MAPPROXY_DIR +'/tms/1.0.0/WorldOceanBase/EPSG900913/{z}/{x}/{y}.png', { tms:true, zoomOffset:-1, minZoom:1, maxNativeZoom:9 } ).addTo(mapdb);
                    //L.control.attribution().addAttribution('<a href="http://www.esri.com" target="_blank" style="border: none;">esri</a>').addTo(mapdb);

                    // Add latest trackline (GeoJSON)
                    ggaData.addTo(mapdb);
                    
                    // Add marker at the last coordinate
                    var marker = L.marker(latLng).addTo(mapdb);
                    
                }
            }
        });
    }
    
    displayLatestGeoJSON('ropos-data');
    
});
