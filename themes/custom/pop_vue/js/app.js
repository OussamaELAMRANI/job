(function ($) {
  $(document).ready(function () {

    //-- Sticky Header
    (function () {

      let mainnav = $('.header');

      if (mainnav.length) {
        let elmHeight = $('.header-top').outerHeight(true);
        $(window).scroll(function () {

          let scrolltop = $(window).scrollTop();
          if (scrolltop > elmHeight) {
            if (!mainnav.hasClass('sticky')) {
              mainnav.addClass('sticky');
            }

          }
          else {
            mainnav.removeClass('sticky');
          }
        })
      }
    })();

    //-- Search icon
    (function () {

      $(".open-form").click(function () {
        $(".open-form").hide();
        $(".close-form").css("display", "block");
        $(".search-block-form").show();
        $(".search-block-form input").focus();
        return false;
      });
      $(".close-form").click(function () {
        $(".close-form").hide();
        $(".open-form").css("display", "block");
        $(".search-block-form").hide();
        return false;
      });

    })();


    //-- Flexslider
    (function () {
      if (typeof $.fn.flexslider === 'function') {
        $('.flexslider').flexslider({
          direction: "vertical",
          controlNav: false,
          directionNav: false
        });
      }
    })();

    //-- Parallax
    (function () {
      $(window).scroll(function () {
        let bg = $('.intro');
        let yPos = -($(window).scrollTop() / bg.data('speed'));
        let coords = '50% ' + yPos + 'px';
        bg.css({backgroundPosition: coords});
      });
    })();


    //-- Scroll to
    (function () {
      $('#goto-section2').on('click', function (e) {
        e.preventDefault()
        $.scrollTo('#section2', 800, {offset: -220});
      });
    })();
  });

  //-- Owl Carousel
  (function () {
      if (typeof $.fn.owlCarousel === 'function') {
        $('.owl-carousel').owlCarousel({
          slideSpeed: 300,
          paginationSpeed: 400,
          singleItem: true
        });
      }
    }
  )();

})(jQuery);
