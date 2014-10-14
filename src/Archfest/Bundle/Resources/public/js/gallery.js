/**
 * Copyright (c) 2009 Anders Ekdahl (http://coffeescripter.com/)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.1.1
 *
 * Demo and documentation: http://coffeescripter.com/code/ad-gallery/
 */
(function($) {
  $.fn.adGallery = function(options) {
    var defaults = { loader_image: 'loader.gif',
                     start_at_index: 0,
                     thumb_opacity: 1,
                     animate_first_image: false,
                     animation_speed: 400,
                     width: false,
                     height: false,
                     display_next_and_prev: true,
                     display_back_and_forward: true,
                     scroll_jump: 0, // If 0, it jumps the width of the container
                     animated_scroll: true,
                     slideshow: {
                       enable: true,
                       autostart: false,
                       speed: 4000,
                      
                       stop_on_scroll: true,
                       countdown_prefix: '(',
                       countdown_sufix: ')'
                     },
                     effect: 'fade', // or 'slide-vert', 'fade', or 'resize', 'none', false
                     enable_keyboard_move: true,
                     cycle: true,
                     callbacks: {
                       init: false,
                       afterImageVisible: false,
                       beforeImageVisible: false,
                       slideShowStart: false,
                       slideShowStop: false
                     }
    };
    var settings = $.extend(defaults, options);
    if(!settings.slideshow.enable) {
      settings.slideshow.autostart = false;
    };
    var galleries = [];
    $(this).each(function() {
      var gallery = new AdGallery(this, settings);
      galleries[galleries.length] = gallery;
    });
    // Sorry, breaking the jQuery chain because the gallery instances
    // are returned so you can fiddle with them
    return galleries;
  };

  function AdGallery(wrapper, settings) {
    this.init(wrapper, settings);
  };
  AdGallery.prototype = {
    // Elements
    wrapper: false,
    image_wrapper: false,
    gallery_info: false,
    nav: false,
    loader: false,
    preloads: false,
    thumbs_wrapper: false,
    scroll_back: false,
    scroll_forward: false,
    next_link: false,
    prev_link: false,
    start_slideshow_link: false,
    stop_slideshow_link: false,
    slideshow_countdown: false,
    slideshow_controls: false,

    slideshow_enabled: false,
    slideshow_running: false,
    slideshow_timeout: false,
    slideshow_countdown_interval: false,
    thumbs_scroll_interval: false,
    image_wrapper_width: 0,
    image_wrapper_height: 0,
    current_index: 0,
    current_image: false,
    nav_display_width: 0,
    settings: false,
    images: false,
    in_transition: false,
    init: function(wrapper, settings) {
      var context = this;
      this.wrapper = $(wrapper);
      this.settings = settings;
      this.setupElements();
      if(this.settings.width) {
        this.image_wrapper_width = this.settings.width;
        this.image_wrapper.width(this.settings.width);
        this.wrapper.width(this.settings.width);
      } else {
        this.image_wrapper_width = this.image_wrapper.width();
      };
      if(this.settings.height) {
        this.image_wrapper_height = this.settings.height;
        this.image_wrapper.height(this.settings.height);
      } else {
        this.image_wrapper_height = this.image_wrapper.height();
      };
      this.nav_display_width = this.nav.width();
      this.images = [];
      this.current_index = 0;
      this.current_image = false;
      this.in_transition = false;
      this.slideshow_enabled = false;
      this.findImages();

      if(this.settings.display_next_and_prev) {
        this.initNextAndPrev();
      };
      this.initSlideshow();
      if(!this.settings.slideshow.enable) {
        this.disableSlideshow();
      } else {
        this.enableSlideshow();
      };
      if(this.settings.display_back_and_forward) {
        this.initBackAndForward();
      };
      if(this.settings.enable_keyboard_move) {
      
      };
      var start_at = this.settings.start_at_index;
      if(window.location.hash && window.location.hash.indexOf('#ad-image') === 0) {
        start_at = window.location.hash.replace(/[^0-9]+/g, '');
        // Check if it's a number
        if((start_at * 1) != start_at) {
          start_at = this.settings.start_at_index;
        };
      };

      
  
      if(typeof this.settings.callbacks.init == 'function') {
        this.settings.callbacks.init.call(this);
      };
    },
    setupElements: function() {
      this.controls = this.wrapper.find('.ad-controls');
      this.gallery_info = $('<p class="ad-info"></p>');
      this.controls.append(this.gallery_info);
      this.image_wrapper = this.wrapper.find('.ad-image-wrapper');
      this.image_wrapper.empty();
      this.nav = this.wrapper.find('.ad-nav');
      this.thumbs_wrapper = this.nav.find('.ad-thumbs');
      this.preloads = $('<div class="ad-preloads"></div>');
      this.loader = $('<img class="ad-loader" src="'+ this.settings.loader_image +'">');
      this.image_wrapper.append(this.loader);
      this.loader.hide();
      $(document.body).append(this.preloads);
    },
    
    findImages: function() {
      var context = this;
      var thumb_wrapper_width = 0;
      var thumbs_loaded = 0;
      var thumbs = this.thumbs_wrapper.find('a');
      var thumb_count = thumbs.length;
      if(this.settings.thumb_opacity < 1) {
        thumbs.find('img').css('opacity', this.settings.thumb_opacity);
      };
      thumbs.each(
        function(i) {
          var link = $(this);
          var image = link.attr('href');
          var thumb = link.find('img');
          // Check if the thumb has already loaded
          if(!context.isImageLoaded(thumb[0])) {
            thumb.load(
              function() {
                var width = this.parentNode.parentNode.offsetWidth;
                thumb_wrapper_width += width;
                thumbs_loaded++;
              }
            );
          } else{
            var width = thumb[0].parentNode.parentNode.offsetWidth;
            thumb_wrapper_width += width;
            thumbs_loaded++;
          };
          link.addClass('ad-thumb'+ i);
          link.click(
            function() {
              context.showImage(i);
              context.stopSlideshow();
              return false;
            }
          ).hover(
            function() {
              if(!$(this).is('.ad-active') && context.settings.thumb_opacity < 1) {
                $(this).find('img').fadeTo(300, 1);
              };
              context.preloadImage(i);
            },
            function() {
              if(!$(this).is('.ad-active') && context.settings.thumb_opacity < 1) {
                $(this).find('img').fadeTo(300, context.settings.thumb_opacity);
              };
            }
          );
          var desc = false;
          var title = false;
          
          context.images[i] = { thumb: thumb.attr('src'), image: image, error: false,
                                preloaded: false, size: false };
        }
      );
      // Wait until all thumbs are loaded, and then set the width of the ul
      var inter = setInterval(
        function() {
          if(thumb_count == thumbs_loaded) {
            context.nav.find('ul').css('width', thumb_wrapper_width +'px');
            clearInterval(inter);
          };
        },
        100
      );
    },
    
    initNextAndPrev: function() {
      this.next_link = $('<div class="ad-next"><div class="ad-next-image"></div></div>');
      this.prev_link = $('<div class="ad-prev"><div class="ad-prev-image"></div></div>');
      this.image_wrapper.append(this.next_link);
      this.image_wrapper.append(this.prev_link);
      var context = this;
      this.prev_link.add(this.next_link).mouseover(
        function(e) {
          // IE 6 hides the wrapper div, so we have to set it's width
          $(this).css('height', context.image_wrapper_height);
          $(this).find('div').show();
        }
      ).mouseout(
        function(e) {
          $(this).find('div').hide();
        }
      ).click(
        function() {
          if($(this).is('.ad-next')) {
            context.nextImage();
            context.stopSlideshow();
          } else {
            context.prevImage();
            context.stopSlideshow();
          };
        }
      ).find('div').css('opacity', 0.1);
    },
    initBackAndForward: function() {
      var context = this;
      this.scroll_forward = $('<div class="ad-forward"></div>');
      this.scroll_back = $('<div class="ad-back"></div>');
      this.nav.append(this.scroll_forward);
      this.nav.prepend(this.scroll_back);
      var has_scrolled = 0;
      $(this.scroll_back).add(this.scroll_forward).click(
        function() {
            console.log(context.nav_display_width);
          // We don't want to jump the whole width, since an image
          // might be cut at the edge
          var width = context.nav_display_width - 50;
          if(context.settings.scroll_jump > 0) {
            var width = context.settings.scroll_jump;
          };
          if($(this).is('.ad-forward')) {
            var left = context.thumbs_wrapper.scrollLeft() + width;
          } else {
            var left = context.thumbs_wrapper.scrollLeft() - width;
          };
          if(context.settings.slideshow.stop_on_scroll) {
            context.stopSlideshow();
          };
          if(context.settings.animated_scroll) {
              console.log(context.thumbs_wrapper



              );
            context.thumbs_wrapper.animate({scrollLeft: left +'px'});
          } else {
            context.thumbs_wrapper.scrollLeft(left);
          };
          return false;
        }
      ).css('opacity', 0.3).hover(
        function() {
          var direction = 'left';
          if($(this).is('.ad-forward')) {
            direction = 'right';
          };
          context.thumbs_scroll_interval = setInterval(
            function() {
              has_scrolled++;
              if(has_scrolled > 30 && context.settings.slideshow.stop_on_scroll) {
                context.stopSlideshow();
              };
              var left = context.thumbs_wrapper.scrollLeft() + 1;
              if(direction == 'left') {
                left = context.thumbs_wrapper.scrollLeft() - 1;
              };
              context.thumbs_wrapper.scrollLeft(left);
            },
            10
          );
          $(this).css('opacity', 0.7);
        },
        function() {
          has_scrolled = 0;
          clearInterval(context.thumbs_scroll_interval);
          $(this).css('opacity', 0.4);
        }
      );
    },
    initSlideshow: function() {
      var context = this;
      this.start_slideshow_link = $('<span class="ad-slideshow-start" style="color:green">'+ this.settings.slideshow.start_label +'</span><span> |</span>');
      this.stop_slideshow_link = $('<span class="ad-slideshow-stop" style="color:red">'+ this.settings.slideshow.stop_label +'</span>');
      this.slideshow_countdown = $('<span class="ad-slideshow-countdown"></span>');
      this.slideshow_controls = $('<div class="ad-slideshow-controls"></div>');
      this.slideshow_controls.append(this.start_slideshow_link).append(this.stop_slideshow_link).append(this.slideshow_countdown);
      this.controls.append(this.slideshow_controls);
      this.slideshow_countdown.hide();

      this.start_slideshow_link.click(
        function() {
          context.startSlideshow();
        }
      );
      this.stop_slideshow_link.click(
        function() {
          context.stopSlideshow();
        }
      );
    },

    enableSlideshow: function() {
      this.slideshow_enabled = true;
      this.slideshow_controls.show();
    },
    
    stopSlideshow: function() {
      if(!this.slideshow_running) return false;
      this.slideshow_running = false;
      this.slideshow_countdown.hide();
      this.slideshow_controls.removeClass('ad-slideshow-running');
      clearTimeout(this.slideshow_timeout);
      clearInterval(this.slideshow_countdown_interval);
      if(typeof this.settings.callbacks.slideShowStop == 'function') {
        this.settings.callbacks.slideShowStop.call(this);
      };
      return true;
    },
   
    isImageLoaded: function(img) {
      if(typeof img.complete != 'undefined' && !img.complete) {
        return false;
      };
      if(typeof img.naturalWidth != 'undefined' && img.naturalWidth == 0) {
        return false;
      };
      return true;
    },
    highLightThumb: function(thumb) {
      this.thumbs_wrapper.find('.ad-active').removeClass('ad-active');
      thumb.addClass('ad-active');
      if(this.settings.thumb_opacity < 1) {
        this.thumbs_wrapper.find('a:not(.ad-active) img').fadeTo(300, this.settings.thumb_opacity);
        thumb.find('img').fadeTo(300, 1);
      };
      var left = thumb[0].parentNode.offsetLeft;
      left -= (this.nav_display_width / 2) - (thumb[0].offsetWidth / 2);
      if(this.settings.animated_scroll) {
        this.thumbs_wrapper.animate({scrollLeft: left +'px'});
      } else {
        this.thumbs_wrapper.scrollLeft(left);
      };
    }
  };
})(jQuery);