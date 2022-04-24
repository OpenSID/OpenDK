@push('css')
  <!-- leaflet -->
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/leaflet.css") }}">
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/MarkerCluster.css") }}">
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/leaflet-geoman.css") }}">
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/L.Control.Locate.min.css") }}">
@endpush

@push('scripts')
  <!-- leaflet -->
  <script src="{{ asset ("/js/leaflet/leaflet-src.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/togeojson.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet-providers.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/turf.min.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/L.Control.Locate.min.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet.markercluster.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet-geoman.min.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/togpx.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet.filelayer.js") }}"></script>
  <script>
    var layers = {};

    function showPolygon(wilayah, layerpeta, warna = '#ffffff') {
      var area_wilayah = JSON.parse(JSON.stringify(wilayah));
      var bounds = new Array();

      var path = new Array();
      for (var i = 0; i < wilayah.length; i++) {
         var daerah_wilayah = area_wilayah[i];
        daerah_wilayah[0].push(daerah_wilayah[0][0]);
        var poligon_wilayah = L.polygon(daerah_wilayah, {
          showMeasurements: true,
          measurementOptions: {
            showSegmentLength: false
          },
        }).addTo(layerpeta);
        layers[poligon_wilayah._leaflet_id] = wilayah[i];
        poligon_wilayah.on("pm:edit", function (e) {
          var old_path = getLatLong("Poly", {
            _latlngs: layers[e.target._leaflet_id],
          }).toString();
          var new_path = getLatLong("Poly", e.target).toString();
          var value_path = document.getElementById("path").value; //ambil value pada input

          document.getElementById("path").value = value_path.replace(
            old_path,
            new_path
          );
          layers[e.target._leaflet_id] = JSON.parse(
            JSON.stringify(e.target._latlngs)
          ); // update value layers
        });
        var layer = poligon_wilayah;
        var geojson = layer.toGeoJSON();
        var shape_for_db = JSON.stringify(geojson);
        var gpxData = togpx(JSON.parse(shape_for_db));

        $("#exportGPX").on("click", function (event) {
          data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
          $(this).attr({
            href: data,
            target: "_blank",
          });
        });

        bounds.push(poligon_wilayah.getBounds());
        // set value setelah create masing2 polygon
        path.push(layer._latlngs);
      }

      layerpeta.fitBounds(bounds);
      document.getElementById("path").value = getLatLong("multi", path).toString();

    }

    function hapuslayer(layerpeta) {
      layerpeta.on("pm:remove", function (e) {
        var type = e.layerType;
        var layer = e.layer;
        var latLngs;

        // set value setelah create polygon
        var last_path = document.getElementById("path").value;
        var new_path = getLatLong("Poly", layer).toString();
        last_path = last_path
          .replace(new_path, "")
          .replace(",,", ",")
          .replace("[,", "[")
          .replace(",]", "]");
        document.getElementById("path").value = last_path;
      });
    }

    function getBaseLayers(peta, access_token) {
      //Menampilkan BaseLayers Peta
      var defaultLayer = L.tileLayer
        .provider("OpenStreetMap.Mapnik", {
          attribution: '<a href="https://openstreetmap.org/copyright">Â© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
        }).addTo(peta);


      if (access_token) {
        mbGLstr = L.mapboxGL({
          accessToken: access_token,
          style: "mapbox://styles/mapbox/streets-v11",
          attribution: '<a href="https://www.mapbox.com/about/maps">Â© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
        });

        mbGLsat = L.mapboxGL({
          accessToken: access_token,
          style: "mapbox://styles/mapbox/satellite-v9",
          attribution: '<a href="https://www.mapbox.com/about/maps">Â© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
        });

        mbGLstrsat = L.mapboxGL({
          accessToken: access_token,
          style: "mapbox://styles/mapbox/satellite-streets-v11",
          attribution: '<a href="https://www.mapbox.com/about/maps">Â© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
        });
      } else {
        mbGLstr = L.tileLayer
          .provider("OpenStreetMap.Mapnik", {
            attribution: '<a href="https://openstreetmap.org/copyright">Â© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
          })

        mbGLsat = L.tileLayer
          .provider("OpenStreetMap.Mapnik", {
            attribution: '<a href="https://openstreetmap.org/copyright">Â© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
          })

        mbGLstrsat = L.tileLayer
          .provider("OpenStreetMap.Mapnik", {
            attribution: '<a href="https://openstreetmap.org/copyright">Â© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
          })

      }

      var baseLayers = {
        OpenStreetMap: defaultLayer,
        "OpenStreetMap H.O.T.": L.tileLayer.provider("OpenStreetMap.HOT", {
          attribution: '<a href="https://openstreetmap.org/copyright">Â© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
        }),
        "Mapbox Streets": mbGLstr,
        "Mapbox Satellite": mbGLsat,
        "Mapbox Satellite-Street": mbGLstrsat,
      };
      return baseLayers;
    }

    function getLatLong(x, y) {
      var hasil;
      if (x == "Rectangle" || x == "Line" || x == "Poly") {
        hasil = JSON.stringify(y._latlngs);
      } else if (x == "multi") {
        hasil = JSON.stringify(y);
      } else {
        hasil = JSON.stringify(y._latlng);
      }

      hasil = hasil
        .replace(/\}/g, "]")
        .replace(/(\{)/g, "[")
        .replace(/(\"lat\"\:|\"lng\"\:)/g, "")
        .replace(/(\"alt\"\:)/g, "")
        .replace(/(\"ele\"\:)/g, "");

      return hasil;
    }

    function stylePointLogo(url) {
      var style = {
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -28],
        iconUrl: url,
      };
      return style;
    }

    function editToolbarPoly() {
      var options = {
        position: "topright", // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
        drawMarker: false, // adds button to draw markers
        drawCircleMarker: false, // adds button to draw markers
        drawPolyline: false, // adds button to draw a polyline
        drawRectangle: false, // adds button to draw a rectangle
        drawPolygon: true, // adds button to draw a polygon
        drawCircle: false, // adds button to draw a cricle
        cutPolygon: false, // adds button to cut a hole in a polygon
        editMode: true, // adds button to toggle edit mode for all layers
        removalMode: true, // adds a button to remove layers
      };
      return options;
    }

    function editToolbarLine() {
      var options = {
        position: "topright", // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
        drawMarker: false, // adds button to draw markers
        drawCircleMarker: false, // adds button to draw markers
        drawPolyline: true, // adds button to draw a polyline
        drawRectangle: false, // adds button to draw a rectangle
        drawPolygon: false, // adds button to draw a polygon
        drawCircle: false, // adds button to draw a cricle
        cutPolygon: false, // adds button to cut a hole in a polygon
        editMode: true, // adds button to toggle edit mode for all layers
        removalMode: true, // adds a button to remove layers
      };
      return options;
    }

    function geoLocation(layerpeta) {
      var lc = L.control
        .locate({
          drawCircle: false,
          icon: "fa fa-map-marker",
          locateOptions: {
            enableHighAccuracy: true
          },
          strings: {
            title: "Lokasi Saya",
            popup: "Anda berada di sekitar {distance} {unit} dari titik ini",
          },
        })
        .addTo(layerpeta);

      layerpeta.on("locationfound", function (e) {
        layerpeta.setView(e.latlng);
      });

      layerpeta
        .on("startfollowing", function () {
          layerpeta.on("dragstart", lc._stopFollowing, lc);
        })
        .on("stopfollowing", function () {
          layerpeta.off("dragstart", lc._stopFollowing, lc);
        });
      return lc;
    }

    function styleGpx() {
      var style = {
        color: "red",
        opacity: 1.0,
        fillOpacity: 1.0,
        weight: 3,
        clickable: true,
      };
      return style;
    }

    function addpoly(layerpeta) {
      layerpeta.on("pm:create", function (e) {
        var type = e.layerType;
        var layer = e.layer;
        var latLngs;

        // set value setelah create polygon
        if (document.getElementById("path").value == "") {
          var last_path = new Array();
        } else {
          var last_path = JSON.parse(document.getElementById("path").value);
        }

        var new_path = JSON.parse(getLatLong("Poly", layer).toString());
        last_path.push(new_path); // gabungkan value lama dengan yang baru

        e.layer.on("pm:edit", function (f) {
          var id_path = f.target._leaflet_id;
          var _path = new Array();
          for (i in layerpeta._layers) {
            if (layerpeta._layers[i]._path != undefined && layers[i]) {
              try {
                _path.push(layerpeta._layers[i]._latlngs);
              } catch (e) {
                alert("problem with " + e + layerpeta._layers[i]);
              }
            }
          }

          var new_path = getLatLong("multi", _path).toString();
          document.getElementById("path").value = new_path;
        });
        layers[e.layer._leaflet_id] = last_path[0];
        document.getElementById("path").value = JSON.stringify(last_path);
      });

    }

    function eximGpxRegion(layerpeta) {
      L.Control.FileLayerLoad.LABEL =
        '<img class="icon-map" src="{{ asset("/js/leaflet/images/gpx.png") }}" alt="file icon"/>';
      L.Control.FileLayerLoad.TITLE = "Impor GPX/KML";

      controlGpxPoly = L.Control.fileLayerLoad({
        addToMap: true,
        formats: [".gpx", ".kml"],
        fitBounds: true,
        layerOptions: {
          pointToLayer: function (data, latlng) {
            return L.marker(latlng);
          },
        },
      });
      controlGpxPoly.addTo(layerpeta);

      controlGpxPoly.loader.on("data:loaded", function (e) {
        
        var type = e.layerType;
        var layer = e.layer;
        var coords = [];
        var geojson = turf.flip(layer.toGeoJSON());
        var shape_for_db = JSON.stringify(geojson);
        var polygon = L.geoJson(JSON.parse(shape_for_db), {
          pointToLayer: function (feature, latlng) {
            return L.marker(latlng);
          },
          onEachFeature: function (feature, layer) {
            coords.push(feature.geometry.coordinates);
          },
        }).addTo(layerpeta);

        var jml = coords[0].length;
        for (var x = 0; x < jml; x++) {
          if (coords[0][x].length > 2) {
            coords[0][x].pop();
          }

        }
        
        var path = get_path_import(coords);
        coords = new Array(coords);
        document.getElementById("path").value = path;
      });

      return controlGpxPoly;
    }

    function eximShp(layerpeta) {
      L.Control.Shapefile = L.Control.extend({
        onAdd: function (map) {
          var thisControl = this;

          var controlDiv = L.DomUtil.create(
            "div",
            "leaflet-control-zoom leaflet-bar leaflet-control leaflet-control-command"
          );

          // Create the leaflet control.
          var controlUI = L.DomUtil.create(
            "div",
            "leaflet-control-command-interior",
            controlDiv
          );

          // Create the form inside of the leaflet control.
          var form = L.DomUtil.create(
            "form",
            "leaflet-control-command-form",
            controlUI
          );
          form.action = "";
          form.method = "post";
          form.enctype = "multipart/form-data";

          // Create the input file element.
          var input = L.DomUtil.create(
            "input",
            "leaflet-control-command-form-input",
            form
          );
          input.id = "file";
          input.type = "file";
          input.accept = ".zip";
          input.name = "uploadFile";
          input.style.display = "none";

          L.DomEvent.addListener(form, "click", function () {
            document.getElementById("file").click();
          }).addListener(input, "change", function () {
            var input = document.getElementById("file");
            if (!input.files[0]) {
              alert("Pilih file shapefile dalam format .zip");
            } else {
              file = input.files[0];
              fr = new FileReader();
              fr.onload = receiveBinary;
              fr.readAsArrayBuffer(file);
            }

            function receiveBinary() {
              geojson = fr.result;
              var shpfile = new L.Shapefile(geojson).addTo(map);

              shpfile.once("data:loaded", function (e) {
                var type = e.layerType;
                var layer = e.layer;
                var coords = [];
                var geojson = turf.flip(shpfile.toGeoJSON());
                var shape_for_db = JSON.stringify(geojson);

                var polygon = L.geoJson(JSON.parse(shape_for_db), {
                  pointToLayer: function (feature, latlng) {
                    return L.circleMarker(latlng, {
                      style: style
                    });
                  },
                  onEachFeature: function (feature, layer) {
                    coords.push(feature.geometry.coordinates);
                  },
                });

                var jml = coords[0].length;
                for (var x = 0; x < jml; x++) {
                  if (coords[0][x].length > 2) {
                    coords[0][x].pop();
                  }
                }

                var path = get_path_import(coords);

                if (multi == true) {
                  coords = new Array(coords);
                }

                document.getElementById("path").value = path;

                layerpeta.fitBounds(shpfile.getBounds());
              });
            }
          });

          controlUI.title = "Impor Shapefile (.Zip)";

          return controlDiv;
        },
      });

      L.control.shapefile = function (opts) {
        return new L.Control.Shapefile(opts);
      };

      L.control.shapefile({
        position: "topleft"
      }).addTo(layerpeta);

      return eximShp;
    }

    function get_path_import(coords) {
      var path = JSON.stringify(coords)
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]],[[", "],[")
        .replace("]]],[[[", "],[")
        .replace("]]],[[[", "],[")
        .replace("]]],[[[", "],[")
        .replace("]]],[[[", "],[")
        .replace("]]],[[[", "],[")
        .replace("[[[[[", "[[[")
        .replace("]]]]]", "]]]")
        .replace("[[[[", "[[[")
        .replace("]]]]", "]]]")
        .replace(/,0]/g, "]")
        .replace("],null]", "]");
      path = "".concat("[", path, "]");
      return path;
    }

    function set_marker(data, judul , contents,color) {
      marker = new Array();
      var area = JSON.parse(data["path"]);
      var jml = area.length;
      content = $(contents).html();
      var style_polygon = {
        stroke: true,
        color: color.line,
        opacity: 1,
        weight: 3,
        fillColor: color.fill,
        fillOpacity: 0.8,
        dashArray: 4,
      };

      for (var x = 0; x < jml; x++) {
        for (var i = 0; i < area[x][0].length; i++) {
          area[x][0][i].reverse();
        }
        area[x][0].push(area[x][0][0]);
        marker.push(
         turf.polygon(area[x], { content: contents, style: style_polygon })
        );
      }
       return marker;
    }

  
    function wilayah_property(set_marker, set_content = false) {
      var wilayah_property = L.geoJSON(turf.featureCollection(set_marker), {
        pmIgnore: true,
        showMeasurements: false,
        measurementOptions: {
          showSegmentLength: false,
        },
        onEachFeature: function (feature, layer) {
          if (feature.properties.name == "kantor_desa") {
            // Beri classname berbeda, supaya bisa gunakan css berbeda
            layer.bindPopup(feature.properties.content, {
              className: "kantor_desa",
            });
          } else if (set_content === true) {
            layer.bindPopup(feature.properties.content);
          }
          layer.bindTooltip(feature.properties.content, {
            sticky: true,
            direction: "top",
          });
          feature.properties.style;
        },
        style: function (feature) {
          if (feature.properties.style) {
            return feature.properties.style;
          }
        },
        pointToLayer: function (feature, latlng) {
          if (feature.properties.style) {
            return L.marker(latlng, { icon: feature.properties.style });
          } else {
            return L.marker(latlng);
          }
        },
      });

      return wilayah_property;
    }
  </script>
@endpush