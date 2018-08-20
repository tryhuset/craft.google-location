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

        this.init();
        this.createMap = this.createMap.bind(this);
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {
              if (window.google) {
                _this.createMap();
              } else {
                $('body').on('apt-google-location-map-init', function() {
                  _this.createMap();
                });
              }
            });
        },

        createMap: function() {
          console.log(this.options.prefix+'map');
          var mapContainer = document.getElementById(this.options.prefix+'map');
          console.log(mapContainer);
          // this.map = map = new google.maps.Map(document.getElementById('map'), {
          //   center: {lat: -34.397, lng: 150.644},
          //   zoom: 8
          // });
        }
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
