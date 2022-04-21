@push('css')
  <!-- leaflet -->
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/leaflet.css") }}">
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/MarkerCluster.css") }}">
  <link rel="stylesheet" href="{{ asset ("/js/leaflet/leaflet-geoman.css") }}">
@endpush

@push('scripts')
  <!-- leaflet -->
  <script src="{{ asset ("/js/leaflet/leaflet.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet-providers.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/turf.min.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet.markercluster.js") }}"></script>
  <script src="{{ asset ("/js/leaflet/leaflet-geoman.min.js") }}"></script>
  <script>
    function getBaseLayers(peta, access_token) {
      //Menampilkan BaseLayers Peta
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

    function eximGpxRegion(layerpeta, multi = false) {
      L.Control.FileLayerLoad.LABEL =
        '<img class="icon-map" src="' +
        BASE_URL +
        'assets/images/gpx.png" alt="file icon"/>';
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

        var path = get_path_import(coords, multi);

        if (multi == true) {
          coords = new Array(coords);
        }

        document.getElementById("path").value = path;
      });

      return controlGpxPoly;
    }
  </script>
@endpush