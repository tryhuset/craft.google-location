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
    var pluginName = "CraftGoogleLocationAreaSettings", defaults = {};

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init = this.init.bind(this);
        this.domReady = this.domReady.bind(this);
        this.onSectionChange = this.onSectionChange.bind(this);
        this.onTypeChange = this.onTypeChange.bind(this);
        this.init();
    }

    Plugin.prototype = {
        init: function(id) {
            $(this.domReady);
        },

        domReady: function() {
          var _this = this;
          this.$fields = $(this.element);
          this.$switch = $('#'+this.options.prefix+'markers');
          this.$sections = $('#'+this.options.prefix+'section');
          this.$types = $('#'+this.options.prefix+'type');
          var $container = $('#'+this.options.prefix+'container');

          this.selectedSection = this.options.section;
          this.selectedtype = this.options.type;
          this.selectedfield = this.options.field;

          if (this.options.markers) {
            $container.removeClass('hidden');
          }

          if (this.selectedSection) {
            this.$sections.parent().removeClass('hidden');
          }

          if (this.selectedtype) {
            this.$types.parent().removeClass('hidden');
          }

          if (this.selectedfield) {
            this.$fields.parent().removeClass('hidden');
          }

          this.$switch.on('change', function() {
            if ($(this).find('input').val()) {
              $container.removeClass('hidden');
            } else {
              $container.addClass('hidden');
            }
          });

          this.$sections.on('change', this.onSectionChange);
          this.$types.on('change', this.onTypeChange);
        },

        onSectionChange: function(e) {
          this.selectedSection = e.target.value;
          this.$types.find('option').remove();
          this.$fields.find('option').remove();
          this.$fields.parent().addClass('hidden');

          if (this.selectedSection !== '') {
            var section = this.options.sections[this.selectedSection];
            this.$types.append('<option value="">'+section.label+'</option>');

            for (var key in section.types) {
              if (section.types.hasOwnProperty(key)) {
                this.$types.append('<option value="'+key+'">'+section.types[key].label+'</option>');
              }
            }
            this.$types.parent().removeClass('hidden');
          } else {
            this.$types.parent().addClass('hidden');
          }
        },

        onTypeChange: function(e) {
          this.selectedType = e.target.value;

          this.$fields.find('option').remove();
          if (this.selectedType !== '') {
            var type = this.options.sections[this.selectedSection].types[this.selectedType];
            var _this = this;
            type.fields.forEach(function(field) {
              _this.$fields.append('<option value="'+field.handle+'">'+field.label+'</option>');
            });
            this.$fields.parent().removeClass('hidden');
          } else {
            this.$fields.parent().addClass('hidden');
          }
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
