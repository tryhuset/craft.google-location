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

    var pluginName = "CraftGoogleLocationLocation",
        defaults = {
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;
        this._timeout = null;

        this.init = this.init.bind(this);
        this.initGoogleMap = this.initGoogleMap.bind(this);
        this.showCountry = this.showCountry.bind(this);
        this.showAddress = this.showAddress.bind(this);
        this.updatePosition = this.updatePosition.bind(this);
        this.onCountryChange = this.onCountryChange.bind(this);
        this.onLookup = this.onLookup.bind(this);
        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {
              if (window.google) {
                _this.initGoogleMap();
              } else {
                $('body').one('apt-google-location-map-init', function() {
                  _this.initGoogleMap();
                });
              }
            });
        },

        initGoogleMap: function() {
          var _this = this;
          var mapContainer = document.getElementById(this.options.namespace+'-map');

          this.country = document.getElementById(this.options.namespace+'-country');
          this.address = document.getElementById(this.options.namespace+'-address');
          this.lat = document.getElementById(this.options.namespace+'-lat');
          this.lng = document.getElementById(this.options.namespace+'-lng');
          this.zoom = document.getElementById(this.options.namespace+'-zoom');
          this.lookup = document.getElementById(this.options.namespace+'-lookup');
          this.geocoder = new google.maps.Geocoder();

          var location = {
            lat: parseFloat(this.lat.value),
            lng: parseFloat(this.lng.value),
          };

          var zoom = parseInt(this.zoom.value);

          if (this.country) {
            this.country.addEventListener('change', this.onCountryChange);
          }
          if (this.lookup) {
            this.lookup.addEventListener('click', this.onLookup);
          }

          this.map = new google.maps.Map(mapContainer, {
            zoom: 4,
            mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            fullscreenControl: false,
            streetViewControl: false,
            gestureHandling: 'cooperative',
          });

          this.map.addListener('zoom_changed', function() {

            if (_this.map.getZoom() !== _this.zoom.value) {
              _this.zoom.value = _this.map.getZoom();
            }
          });

          this.marker = new google.maps.Marker({
            map: this.map,
            draggable: true,
          });

          this.marker.addListener('dragend', function() {
            _this.updatePosition(_this.marker.getPosition());
          });

          if (location.lat && location.lng) {
            this.map.setCenter(location);
            if (zoom) {
              this.map.setZoom(zoom);
            }
            this.marker.setPosition(location);
            this.marker.setTitle(this.address.value);
          } else {
            this.showCountry(this.country.value);
          }
        },

        updatePosition: function(position) {
          this.lat.value = position.lat();
          this.lng.value = position.lng();
        },

        onCountryChange: function(e) {
          this.showCountry(e.target.value);
        },

        onLookup: function() {
          this.showAddress(this.address.value);
        },

        showCountry: function(countryCode) {
          var _this = this;

          this.geocoder.geocode({
            componentRestrictions: {
              country: countryCode,
            }
          }, function(results) {
            if (results.length > 0) {
              if (results[0].geometry) {
                _this.map.setCenter(results[0].geometry.location);
                if (results[0].geometry.bounds) {
                  _this.map.fitBounds(results[0].geometry.bounds);
                }
                _this.marker.setPosition(results[0].geometry.location);
                _this.marker.setTitle(results[0].formatted_address);
                _this.updatePosition(results[0].geometry.location);
              }
            }
          });
        },

        showAddress: function(address) {
          var _this = this;
          if (address.length > 0) {
            this.geocoder.geocode({
              address: address,
              componentRestrictions: {
                country: this.country.value,
              }
            }, function(results) {
              if (results.length > 0) {
                if (results[0].geometry) {
                  _this.map.setCenter(results[0].geometry.location);
                  _this.map.setZoom(12);
                  _this.marker.setPosition(results[0].geometry.location);
                  _this.marker.setTitle(address);
                  _this.updatePosition(results[0].geometry.location);
                }
              }
            });
          }
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
