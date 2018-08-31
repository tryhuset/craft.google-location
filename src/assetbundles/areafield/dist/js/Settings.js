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
        this.setState = this.setState.bind(this);
        this.render = this.render.bind(this);
        this.onSwitchChange = this.onSwitchChange.bind(this);
        this.onSectionChange = this.onSectionChange.bind(this);
        this.onTypeChange = this.onTypeChange.bind(this);
        this.onFieldChange = this.onFieldChange.bind(this);
        this.onMatrixBlockChange = this.onMatrixBlockChange.bind(this);
        this.init();
    }

    Plugin.prototype = {
        state: {
          markers: false,
          section: '',
          type: '',
          field: '',
          matrixBlock: '',
          matrixBlockField: '',
        },

        init: function(id) {
            $(this.domReady);
        },

        setState: function(state) {
          this.state = $.extend(this.state, state);
          this.render();
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
          this.$container = $('#'+this.options.prefix+'container');
          this.$matrixContainer = $('#'+this.options.prefix+'matrix-container');

          this.setState(this.options.state);

          this.$switch.on('change', this.onSwitchChange);
          this.$sections.on('change', this.onSectionChange);
          this.$types.on('change', this.onTypeChange);
          this.$fields.on('change', this.onFieldChange);
          this.$matrixBlock.on('change', this.onMatrixBlockChange);
        },

        onSwitchChange: function() {
          if (this.$switch.find('input').val()) {
            this.setState({ markers: true });
          } else {
            this.setState({ markers: false });
          }
        },

        onSectionChange: function(e) {
          this.setState({
            section: e.target.value,
            type: '',
            field: '',
            matrixBlock: '',
            matrixBlockField: '',
          });
        },

        onTypeChange: function(e) {
          this.setState({
            type: e.target.value,
            field: '',
            matrixBlock: '',
            matrixBlockField: '',
          });
        },

        onFieldChange: function(e) {
          this.setState({
            field: e.target.value,
            matrixBlock: '',
            matrixBlockField: '',
          });
        },

        onMatrixBlockChange: function(e) {
          this.setState({
            matrixBlock: e.target.value,
            matrixBlockField: '',
          });
        },

        clearOptions: function($select) {
          $select.find('option').each(function() {
            if (this.value) {
              $(this).remove();
            }
          });
        },

        renderType: function() {
          this.clearOptions(this.$types);

          if (this.state.section) {
            var section = this.options.sections[this.state.section];

            for (var key in section.types) {
              if (section.types.hasOwnProperty(key)) {
                this.$types.append('<option value="'+key+'">'+section.types[key].name+'</option>');
              }
            }

            if (this.state.type) {
              this.$types.val(this.state.type);
            }

            this.$types.removeAttr('disabled','disabled');
            this.$types.parent().removeClass('disabled');
          } else {
            this.$types.attr('disabled','disabled');
            this.$types.parent().addClass('disabled');
          }
        },

        renderField: function() {
          this.clearOptions(this.$fields);

          if (this.state.type) {
            var type = this.options
              .sections[this.state.section]
              .types[this.state.type]
            ;

            for (var key in type.fields) {
              if (type.fields.hasOwnProperty(key)) {
                this.$fields.append('<option value="'+key+'">'+type.fields[key].name+'</option>');
              }
            }

            this.$fields.val(this.state.field);

            if (this.state.field) {
              var field = type.fields[this.state.field];
              this.$fieldType.val(field.type);

              if (field.type === 'matrix') {
                this.$matrixContainer.removeClass('hidden');
              } else {
                this.$matrixContainer.addClass('hidden');
              }
            }

            this.$fields.removeAttr('disabled','disabled');
            this.$fields.parent().removeClass('disabled');
          } else {
            this.$fields.attr('disabled','disabled');
            this.$fields.parent().addClass('disabled');
            this.$fieldType.val('');
            this.$matrixContainer.addClass('hidden');
          }
        },

        renderMatrixBlock: function() {
          this.clearOptions(this.$matrixBlock);

          if (this.state.field) {
            var field = this.options
              .sections[this.state.section]
              .types[this.state.type]
              .fields[this.state.field]
            ;

            for (var key in field.blocks) {
              if (field.blocks.hasOwnProperty(key)) {
                this.$matrixBlock.append('<option value="'+key+'">'+field.blocks[key].name+'</option>');
              }
            }

            this.$matrixBlock.val(this.state.matrixBlock);

            this.$matrixBlock.removeAttr('disabled','disabled');
            this.$matrixBlock.parent().removeClass('disabled');
          } else {
            this.$matrixBlock.attr('disabled','disabled');
            this.$matrixBlock.parent().addClass('disabled');
          }
        },

        renderMatrixBlockField: function() {
          this.clearOptions(this.$matrixBlockField);

          if (this.state.matrixBlock) {
            var block = this.options
              .sections[this.state.section]
              .types[this.state.type]
              .fields[this.state.field]
              .blocks[this.state.matrixBlock]
            ;

            for (var key in block.fields) {
              if (block.fields.hasOwnProperty(key)) {
                this.$matrixBlockField.append('<option value="'+key+'">'+block.fields[key].name+'</option>');
              }
            }

            this.$matrixBlockField.val(this.state.matrixBlockField);

            this.$matrixBlockField.removeAttr('disabled','disabled');
            this.$matrixBlockField.parent().removeClass('disabled');
          } else {
            this.$matrixBlockField.attr('disabled','disabled');
            this.$matrixBlockField.parent().addClass('disabled');
          }
        },

        render: function() {
          if (this.state.markers) {
            this.$container.removeClass('hidden');
          } else {
            this.$container.addClass('hidden');
          }

          this.renderType();
          this.renderField();
          this.renderMatrixBlock();
          this.renderMatrixBlockField();
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
