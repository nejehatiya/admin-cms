//import '../../css/modeles/modele-galerie-images.css'
$(document).ready(function (e) {
    /**
         * set one slide
         */
    /*$(".carousel-galerie-images").on(
        "init afterChange",
        function (event, slick, currentSlide, nextSlide) {
            //console.log('event, slick, currentSlide, nextSlide', event, slick, currentSlide, nextSlide)
            if (currentSlide == 1) {
                $('#modele-galerie-images').html('.galeries-images .carousel-galerie-images .slick-list{padding:0 0px 0 0 !important;}');
                $('.carousel-galerie-images').slick('setPosition');
                $('.carousel-galerie-images').addClass('modele-galerie-images-one-slide');
            }
        }
    );*/
    let width_slide = ($(window).width() <= 480) ? 283 : 400;

    /**
     * carousel galeries mobile
     */
    if ($(window).width() <= 768) {
        let width_diff = $(".galeries-images .carousel-galerie-images").width() - width_slide;
        //console.log('width_diff', width_diff);
        $('body').append('<style id="modele-galerie-images">.galeries-images .carousel-galerie-images .slick-list{padding:0 ' + width_diff + 'px 0 0 !important;}</style>');
        $(".carousel-galerie-images").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: false,
            dots: false,
            centerMode: true,
            centerPadding: 0,
            lazyLoad: 'ondemand'
        })
    }

})