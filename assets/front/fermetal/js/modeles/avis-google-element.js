//import '../../css/modeles/modele-avis-google.css';
$(document).ready(function (e) {
  /** init vars */
  let infinite = true;
  if ($(window).width() <= 768) {
    infinite = false;
  }
  /**
 * after Change btn next and prev
 */
  $(".carousel").on(
    "init afterChange",
    function (event, slick, currentSlide, nextSlide) {
      //console.log('event, slick, currentSlide, nextSlide', event, slick, currentSlide, nextSlide)
      if (currentSlide == 1 && $(window).width() <= 768) {
        $('#aviss-googles-style').html('.avis-google .container .all-avis .slick-list{padding:0 0px 0 0 !important;}');
        $(".carousel").slick('setPosition');
        $(".carousel").addClass('avis-google-one-slide')
      }
    }
  );
  let slide_pershow = 4;
  if ($(window).width() >= 1800) {
    slide_pershow = Math.floor($(".avis-google .all-avis").width() / 400);

  } else {
    slide_pershow = Math.floor($(".avis-google .all-avis").width() / 360);

  }
  //console.log('slide_pershow', slide_pershow);
  slide_pershow = slide_pershow ? slide_pershow : 1;
  let centre_mode = $(".avis-google .all-avis .commentaire").length > slide_pershow;
  //console.log('slide_pershow', slide_pershow);
  slide_pershow = slide_pershow ? slide_pershow : 1;
  /*** caclule la diference entren 316 et width window */
  if ($(window).width() <= 768) {
    let our_wudth_diff = $(".avis-google .all-avis").width() - 310;
    //console.log('our_wudth_diff', our_wudth_diff);
    $('body').append('<style id="aviss-googles-style">.avis-google .container .all-avis .slick-list{padding:0 ' + our_wudth_diff + 'px 0 0 !important;}</style>')
    centre_mode = true;
  }
  /**
   * carousel avis
   */
  $(".carousel").slick({
    slidesToShow: slide_pershow,
    slidesToScroll: 1,
    dots: false,
    centerMode: centre_mode,
    infinite: infinite,
    centerPadding: 0,
    lazyLoad: 'ondemand',
  })
})
