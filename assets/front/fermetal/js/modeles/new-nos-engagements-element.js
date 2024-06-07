//import "../../css/modeles/modele-nos-engagements.css";
$(document).ready(function (e) {
    let carousel_exist = $(".section-nos-engagements .carousel-nos-engagement").length ? 1 : 0;
    // if (carousel_exist) {
    //console.log('carousel_exist', carousel_exist);
    let slide_pershow = $('.carousel-nos-engagement').parents('.section-nos-engagements').length ? 3 : 4;
    /**
     * carousel nos engagement in mobile
     */
    if ($(window).width() <= 768) {
        let width_diff = $('.nos-engagement-content').width() - 310;
        //console.log('width_diff', width_diff);
        $('body').append('<style>.nos-engagement-content .slick-list{padding:0 ' + width_diff + 'px 0 0 !important;}</style>')

        $(".carousel-nos-engagement").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: false,
            centerMode: true,
            centerPadding: 0,
            lazyLoad: 'ondemand',
        })
    }
    // }


})