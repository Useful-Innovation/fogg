(function($, undefined) {


//
//
//      Translatable
//
//
$.fn.translatable = function() {
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
$.fn.onDelete = function() {
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
$.fn.evenHeight = function() {

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
$.fn.mediaField = function() {
  var items = $(this);

  var methods = {
    onChooseMedia : function(e) {
      var self  = this;
      var field = $(e.target).closest('.media-field');
      var type  = field.data('type');

      wp.media.editor.send.attachment = function(props, attachment){
        self.types[type].set(field, attachment);
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
          field.find('.value-field').val(attachment.id);
          field.find('img').attr('src', attachment.url);
        },
        remove : function(field) {
          field.find('.value-field').val('');
          field.find('img').attr('src', '');
        }
      }
    }
  };

  return items.each(function() {
    $(this).find('.api-choose-media').on('click', function(e) {e.preventDefault(); methods.onChooseMedia(e); });
    $(this).find('.api-remove-media').on('click', function(e) {e.preventDefault(); methods.onRemoveMedia(e); });
  });
};


//
//
//    Mark all / Mark none
//    Works on checkbox-fields
//
//
$.fn.marker = function() {
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



var script = {
  init : function() {
    $('.translatable-container').translatable();
    $('.api-delete').onDelete();
    $('.splitter').evenHeight();
    $('.media-field').mediaField();
    $('.api-mark').marker();
  }
};

$(document).ready(function() {
  script.init();
});

})(jQuery);
