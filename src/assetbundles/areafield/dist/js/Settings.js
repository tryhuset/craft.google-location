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
        this.onFieldChange = this.onFieldChange.bind(this);
        this.init();
    }

    Plugin.prototype = {
        init: function(id) {
            $(this.domReady);
        },

        domReady: function() {
          var _this = this;
          this.$switch = $('#'+this.options.prefix+'markers');
          this.$sections = $('#'+this.options.prefix+'section');
          this.$types = $('#'+this.options.prefix+'type');
          this.$fields = $(this.element);
          this.$fieldType = $('#'+this.options.prefix+'fieldType');
          this.$matrixBlock = $('#'+this.options.prefix+'matrixBlock');
          this.$matrixBlockField = $('#'+this.options.prefix+'matrixBlockField');
          var $container = $('#'+this.options.prefix+'container');

          this.selectedSection = this.options.section;
          this.selectedType = this.options.type;
          this.selectedField = this.options.field;

          if (this.options.markers) {
            $container.removeClass('hidden');
          }

          if (this.selectedSection) {
            this.$sections.parent().removeClass('hidden');
          }

          if (this.selectedType) {
            this.$types.parent().removeClass('hidden');
          }

          if (this.selectedField) {
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
          this.$fields.on('change', this.onFieldChange);
        },

        onSectionChange: function(e) {
          this.selectedSection = e.target.value;
          this.clearOptions(this.$types);
          this.clearOptions(this.$fields);
          // this.$types.find('option').remove();
          // this.$fields.find('option').remove();
          // this.$fields.parent().addClass('hidden');

          if (this.selectedSection !== '') {
            var section = this.options.sections[this.selectedSection];

            for (var key in section.types) {
              if (section.types.hasOwnProperty(key)) {
                console.log(key, section.types[key]);
                this.$types.append('<option value="'+key+'">'+section.types[key].name+'</option>');
              }
            }
            // this.$types.parent().removeClass('hidden');
          } else {
            // this.$types.parent().addClass('hidden');
          }
        },

        onTypeChange: function(e) {
          this.selectedType = e.target.value;
          this.clearOptions(this.$fields);

          if (this.selectedType !== '') {
            var type = this.options.sections[this.selectedSection].types[this.selectedType];
            for (var key in type.fields) {
              if (type.fields.hasOwnProperty(key)) {
                this.$fields.append('<option value="'+key+'">'+type.fields[key].name+'</option>');
              }
            }
            // this.$fields.parent().removeClass('hidden');
          } else {
            // this.$fields.parent().addClass('hidden');
          }
        },

        onFieldChange: function(e) {
          this.selectedField = e.target.value;
          console.log(this.selectedSection);
          console.log(this.selectedType);
          console.log(this.selectedField);
          if (this.selectedField !== '') {

            var field = this.options
              .sections[this.selectedSection]
              .types[this.selectedType]
              .fields[this.selectedField]
            ;
            this.$fieldType.val(field.type);

            // for (var key in type.fields) {
            //   if (type.fields.hasOwnProperty(key)) {
            //     this.$fields.append('<option value="'+key+'">'+type.fields[key].name+'</option>');
            //   }
            // }
            // this.$fields.parent().removeClass('hidden');
          } else {
            // this.$fields.parent().addClass('hidden');
          }
        },

        clearOptions: function($select) {
          $select.find('option').each(function() {
            if (this.value) {
              $(this).remove();
            }
          });
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
