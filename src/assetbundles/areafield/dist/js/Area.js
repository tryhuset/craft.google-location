/**
 * Craft Google Location plugin for Craft CMS
 *
 * Location Field JS
 *
 * @author    Thomas Sømoen
 * @copyright Copyright (c) 2018 Thomas Sømoen
 * @link      https://apt.no/
 * @package   CraftGoogleLocation
 * @since     1.0.0CraftGoogleLocationLocation
 */

 ;(function ( $, window, document, undefined ) {
    var pluginName = "CraftGoogleLocationArea", defaults = {};

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;
        this._timeout = null;

        this.init = this.init.bind(this);
        this.initGoogleMap = this.initGoogleMap.bind(this);
        this.addMarkers = this.addMarkers.bind(this);
        this.addDrawingManager = this.addDrawingManager.bind(this);
        this.onDrawingComplete = this.onDrawingComplete.bind(this);
        this.createShape = this.createShape.bind(this);

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {
              if (window.google) {
                _this.initGoogleMap();
              } else {
                $('body').one('apt-google-area-map-init', function() {
                  _this.initGoogleMap();
                });
              }
            });
        },

        initGoogleMap: function() {
          var _this = this;
          var mapContainer = document.getElementById(this.options.namespace+'-map');
          this.input = document.getElementById(this.options.namespace);
          this.search = document.getElementById(this.options.namespace+'-search');
          this.searchBox = new google.maps.places.SearchBox(this.search);
          this.geocoder = new google.maps.Geocoder();
          this.map = new google.maps.Map(mapContainer, {
            center: {lat: 59.0538388, lng: 10.0289138},
            zoom: 8,
            mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            fullscreenControl: false,
            streetViewControl: false,
            gestureHandling: 'cooperative',
          });

          this.shapes = this.createShapes(this.options.shapes);

          this.addMarkers(this.options.markers);
          this.fitToBounds();
          this.addDrawingManager();

          this.places = new google.maps.places.PlacesService(this.map);

          this.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(this.search);

          google.maps.event.addDomListener(this.search, 'keydown', function(e) {
            if (e.keyCode === 13) {
              e.preventDefault();
            }
          });

          this.searchBox.addListener('places_changed', function() {
            var places = _this.searchBox.getPlaces();

            if (places.length == 0) {
              return;
            }

            var bounds = new google.maps.LatLngBounds();

            places.forEach(function(place) {
              if (!place.geometry) {
                return;
              }

              if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
              } else {
                bounds.extend(place.geometry.location);
              }
            });
            _this.map.fitBounds(bounds);
          });
        },

        fitToBounds: function() {
          const bounds = new google.maps.LatLngBounds();
          if (this.shapes.length) {
            this.shapes.forEach(function(shape) {
              shape.getPath().getArray().forEach(function(path) {
                bounds.extend(path);
              });
            });
            this.map.fitBounds(bounds);
          } else if(this.options.markers) {
            this.options.markers.forEach(function(marker) {
              bounds.extend(marker.position);
            });
            this.map.fitBounds(bounds);
          }
        },

        addMarkers: function(markers) {
          var _this = this;
          markers.forEach(function(marker) {
            return new google.maps.Marker({
              position: marker.position,
              map: _this.map,
              title: marker.title,
            });
          });
        },

        addDrawingManager: function() {
          var _this = this;
          var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControlOptions: {
              position: google.maps.ControlPosition.TOP_CENTER,
              drawingModes: ['polygon']
            },
          });
          drawingManager.setMap(this.map);
          drawingManager.addListener('overlaycomplete', this.onDrawingComplete);
        },

        onDrawingComplete: function(e) {
          if (e.type === 'polygon') {
            var shape = this.createShape(e.overlay, this.shapes.length);
            this.addShape(shape, true);
          }
        },

        createShapes: function(shapes = []) {
          var _this = this;
          return shapes.map((paths, i) => {
            var shape = new google.maps.Polygon({ paths });
            shape = this.createShape(shape, i);
            shape.setMap(_this.map);
            return shape;
          });
        },

        createShape: function(shape, index) {
          var _this = this;
          var path = shape.getPath();
          var dragging = false;

          shape.setEditable(true);
          shape.setDraggable(true);
          shape.setOptions({ zIndex: ((index + 1) * 10) });

          const onEdit = () => {
            if (!dragging) {
              this.triggerSelectionChange();
            }
          };

          google.maps.event.addListener(path, 'insert_at', onEdit);
          google.maps.event.addListener(path, 'remove_at', onEdit);
          google.maps.event.addListener(path, 'set_at', onEdit);

          google.maps.event.addListener(shape, 'rightclick', () => {
            _this.destroyShape(shape);
            _this.removeShape(shape, true);
          });

          google.maps.event.addListener(shape, 'dragstart', () => {
            dragging = true;
          });

          google.maps.event.addListener(shape, 'dragend', () => {
            dragging = false;
            this.triggerSelectionChange();
          });

          return shape;
        },

        destroyShape: function(shape) {
          const path = shape.getPath();
          google.maps.event.clearListeners(shape, 'rightclick');
          google.maps.event.clearListeners(path, 'insert_at');
          google.maps.event.clearListeners(path, 'remove_at');
          google.maps.event.clearListeners(path, 'set_at');
          shape.setMap(null);
        },

        addShape: function(shape, trigger = false) {
          this.shapes.push(shape);
          if (trigger) {
            this.triggerSelectionChange();
          }
        },

        removeShape: function(shape, trigger = false) {
          this.destroyShape(shape);

          this.shapes = this.shapes.filter(function(s) {
            return s !== shape;
          });

          if (trigger) {
            this.triggerSelectionChange();
          }
        },

        triggerSelectionChange: function() {
          console.log(this.shapes);

          var transformed = this.shapes.map(function(shape) {
            return shape.getPath().getArray().map(function(path) {
              return {
                lat: path.lat(),
                lng: path.lng(),
              };
            });
          });
          this.input.value = JSON.stringify(transformed);
        },
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
