//import '../../css/modeles/nos-services-de-reparation-element.css';
$(document).ready(function(){
    let slide_pershow = Math.floor( $(".content-models").width()  / 330 ) ;
    slide_pershow = slide_pershow <= 3 ?slide_pershow:3;
    if($(window).width()<576){
        slide_pershow = 1;
    }
    $(".carousel-items").slick({
        /**
         * Afficher 4 diapositives en même temps
         */
        slidesToShow: slide_pershow,
        /**
         * Faire défiler par 4 diapositives
         */
        slidesToScroll: 1,
        dots: false,
        centerMode: true,
        centerPadding: 0,
        lazyLoad: 'ondemand',
    })
})