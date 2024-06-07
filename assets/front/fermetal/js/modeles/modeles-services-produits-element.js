//import '../../css/modeles/modeles-services-produits.css';
$(document).ready(function () {
  $(".carousel-2").on(
    "init afterChange",
    function (event, slick, currentSlide, nextSlide) {
      /**
       * disabled btn prev if first slide !==0 else enable btn
       */
      if (slick.currentSlide === 0) {
        $(".slick-prev").addClass("disabled");
        $(".slick-prev").css("opacity", "0");
      } else {

        $(".slick-prev").removeClass("disabled");
        $(".slick-prev").css("opacity", "1");
      }
      /**
       * disabled btn next if currentSlide !==slideCount -1 else enable btn
       */
      if (slick.currentSlide + slick.options.slidesToShow >= slick.slideCount) {
        $(".slick-next").addClass("disabled");
        $(".slick-next").css("opacity", "0");
      } else {
        $(".slick-next").removeClass("disabled");
        $(".slick-next").css("opacity", "1");
      }
    }
  );
  /**
   * carousel avis
   */
  let slide_pershow = $('.carousel-2').parents('.modele-services-produits-accueil-page').length ? 3 : 4;
  if (!$('.carousel-2').parents('.modele-services-produits-accueil-page').length) {
    if ($(window).width() > 1800) {
      slide_pershow = Math.floor(($(window).width() - 70 * 2) / 450);

    } else {
      slide_pershow = Math.floor(($(window).width() - 70 * 2) / 360);

    }
  } else {
    slide_pershow = Math.floor(($(".content-models").width()) / 360);
  }
  slide_pershow = slide_pershow ? slide_pershow : 1;
  let centerMode = $(".modele-services-produits-accueil .carousel-2 .content-produit").length > slide_pershow;
  /**
   * Afficher 4 diapositives en même temps
   */
  if ($(window).width() < 768) {
    let width_diff = $(".carousel-2").width() - 310;
    //console.log('width_diff', width_diff);
    centerMode = true;
    $('body').append('<style>.carousel-2 .slick-list{padding:0 ' + width_diff + 'px 0 0 !important;}</style>')
  }
  $('.carousel-2').slick({

    slidesToShow: slide_pershow,
    slidesToScroll: 1,
    dots: false,
    centerMode: centerMode,
    infinite: true,
    centerPadding: 0,
    lazyLoad: 'ondemand'



  });
})


