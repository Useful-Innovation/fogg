(function($, undefined) {


//
//
//      Translatable
//
//
$.fn.foggTranslatable = function() {
  var items = $(this);

  items.find('.translatable').hide();

  function showTab(tabs, element, li) {
    tabs.find('li').removeClass('active');
    element.find('.translatable').hide();

    var key = li.find('a').attr('href').replace('#', '');

    element.find('.' + key).show();
    li.addClass('active');
  }

  return items.each(function() {
    var element = $(this);
    var tabs    = element.find('.translatable-tabs');

    tabs.find('li').each(function() {
      $(this).find('a').on('click', function(e) {
        e.preventDefault();
        var li = $(e.target).closest('li');
        showTab(tabs, element, li);
      });
    });

    showTab(tabs, element, tabs.find('li:first'));
  });
};


//
//
//      Prevent accidental deletion
//
//
$.fn.foggOnDelete = function() {
  return $(this).each(function() {
    $(this).on('click', function(e) {
      if(!confirm('Är du säker?')) {
        e.preventDefault();
      }
    });
  });
};

//
//
//      Even height
//
//
$.fn.foggEvenHeight = function() {

  var items = $(this);

  return items.each(function() {
    var max = 0;
    $(this).find('.postbox').each(function() {
      var height = $(this).find('.inside').height();
      if(height > max) {
        max = height;
      }
    });

    $(this).find('.postbox').each(function() {
      $(this).find('.inside').css('min-height', max + 'px');
    });
  });
};



//
//
//        Media Field
//
//
$.fn.foggMediaField = function() {
  var items        = $(this);
  var methods      = {
    onChooseMedia : function(e) {
      var self         = this;
      var field        = $(e.target).closest('.media-field');
      var type         = field.data('type');
      var original     = wp.media.editor.send.attachment;
      var custom_media = true;
      wp.media.editor.send.attachment = function(props, attachment) {
        if (custom_media === true) {
          self.types[type].set(field, attachment);
          custom_media = false;
        }
        return original.apply( this, [props, attachment] );
      };

      wp.media.editor.open($(e.target));
    },
    onRemoveMedia : function(e) {
      var self  = this;
      var field = $(e.target).closest('.media-field');
      var type  = field.data('type');

      self.types[type].remove(field);
    },

    types : {
      image : {
        set : function(field, attachment) {
          var url;
          if(attachment.sizes.thumbnail) {
            url = attachment.sizes.thumbnail.url;
          } else {
            url = attachment.url;
          }
          field.find('.value-field').val(attachment.id);
          field.find('img').attr('src', url);
        },
        remove : function(field) {
          field.find('.value-field').val('');
          field.find('img').attr('src', '');
        }
      },
      file : {
        set : function(field, attachment) {
          field.find('.value-field').val(attachment.id);
          field.find('a').attr('href', attachment.url).html(attachment.filename);
        },
        remove : function(field) {
          field.find('.value-field').val('');
          field.find('a').attr('href', '').html('');
        }
      }
    }
  };

  return items.closest('.field').each(function() {
    $(this).on('click', '.fogg-choose-media', function(e) {e.preventDefault(); methods.onChooseMedia(e); });
    $(this).on('click', '.fogg-remove-media', function(e) {e.preventDefault(); methods.onRemoveMedia(e); });
  });
};


//
//
//    Mark all / Mark none
//    Works on checkbox-fields
//
//
$.fn.foggMarker = function() {
  var links = $(this);

  return links.each(function() {
    var link = $(this);
    link.on('click', function() {
      var items = $(this).closest('.field').find('input[type=checkbox]');
      items.each(function() {
        $(this).attr('checked', (link.data('type') == 'mark-all'));
      });
    });
  });
};





//
//
//    Duplicate
//
//
$.fn.foggDuplicate = function() {
  return $(this).each(function() {
    var field = $(this);

    field.on('click', 'button.duplicate', function(e) {
      e.preventDefault();

      var button = $(e.target);
      var field  = button.closest('.duplicatable-field');
      var html   = field.find('.fogg-template').html();

      field.find('.groups').append(html);
      setPositions(field);
    });

    field.on('click', 'button.remove-group', function(e) {
      e.preventDefault();

      var button = $(e.target);
      var group  = button.closest('.group');
      group.remove();
    });

    function setPositions(field) {
      field.find('.group').each(function(i) {
        $(this).find('input,select,textarea').each(function() {
          $(this).attr('name', $(this).attr('name').replace('[]', '[' + i + ']'));
        });
      });
    }

    setPositions(field);
  });
};





//
//
//    Duplicate
//
//
$.fn.foggAutoComplete = function() {
  return $(this).each(function() {
    var field      = $(this);
    var data       = $.trim(field.find('.autocomplete-data').html());
    var data       = JSON.parse(data);
    var input      = field.find('.autocomplete-input');
    var list       = field.find('.autocomplete-list');
    var template   = field.find('.fogg-template').html();
    var list_class = 'autocomplete-id-';

    input.autocomplete({
      source : data,
      minLength : 3,
      select : select
    }).keypress(function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if(code == 13) {
        return false;
      }
    });

    list.on('click', 'li .remove', remove);

    function select(e, object) {
      var item = object.item;
      var html = template;

      if(list.find('li.' + list_class + item.id).length) {
        resetInput();
        return;
      }

      list.append(html);
      var element = list.find('li:last');
      element.addClass(list_class + item.id);
      element.find('.value').html(item.value);
      element.find('.title').html(item.title);
      element.find('input').val(item.id);

      resetInput();
    }

    function remove(e) {
      var target = $(e.target);
      target.closest('li').remove();
    }

    function resetInput() {
      setTimeout(function() {
        input.val('');
      }, 1);
    }
  });
};



var script = {
  init : function() {
    $('.fogg-wrap .duplicatable-field').foggDuplicate();
    $('.fogg-wrap .translatable-container').foggTranslatable();
    $('.fogg-wrap .fogg-delete').foggOnDelete();
    $('.fogg-wrap .splitter').foggEvenHeight();
    $('.fogg-wrap .media-field').foggMediaField();
    $('.fogg-wrap .api-mark').foggMarker();
    $('.fogg-wrap .autocomplete-field').foggAutoComplete();
  }
};

$(document).ready(function() {
  script.init();
});

})(jQuery);
