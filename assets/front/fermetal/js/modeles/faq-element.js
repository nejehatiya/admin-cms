//import "../../css/modeles/modele-faq-element.css";
$(document).ready(function () {
    /**
     * Ouvrez le deuxième élément li par défaut && icon
     */
    console.log('test faq', $('.faq .column1').find('li').length);
    if ($('.faq .column1').find('li').length === 1) {
        // If only one li in column1
        $('.faq .column1').find('li:eq(0)').find('.reponse').removeClass('close-response').addClass('open-response');
        $('.faq .column1').find('li:eq(0) .open img').attr('src', '/build/images/Icon-fois.svg');
    } else {
        // If more than one li in column1
        $('.faq .column1').find('li:eq(1)').find('.reponse').removeClass('close-response').addClass('open-response');
        $('.faq .column1').find('li:eq(1)').find('.open img').attr('src', '/build/images/Icon-fois.svg');
    }

    /**
     * Gérez l'ouverture/fermeture des éléments .open
     */
    $('.faq .question').on('click', function (e) {
        e.preventDefault();

        /**
         *  la réponse associée à l'élément cliqué
         */
        var reponse = $(this).closest('li').find('.reponse');

        /**
         * Fermez automatiquement la dernière réponse ouverte
         */
        if ($(this).parents('.column1').length) {
            $('.faq .reponse.open-response').not(reponse).toggleClass('open-response close-response').closest('li').find('.open').find('img').attr('src', '/build/images/Icon-Plus.svg');
        } else {
            $('.faq .column2 .reponse.open-response').not(reponse).toggleClass('open-response close-response').closest('li').find('.open').find('img').attr('src', '/build/images/Icon-Plus.svg');
        }

        /**
         * Basculez l'état de la réponse cliquée
         */
        reponse.toggleClass('close-response open-response');

        /**
         * Modifiez l'attribut data-src
         */
        if (reponse.hasClass('close-response')) {
            $(this).closest('li').find('.open').find('img').attr('src', '/build/images/Icon-Plus.svg');
        } else {
            $(this).closest('li').find('.open').find('img').attr('src', '/build/images/Icon-fois.svg');
        }

    });
    /**
     * set one slide
     */
    $(".carousel-faq").on(
        "init afterChange",
        function (event, slick, currentSlide, nextSlide) {
            //console.log('event, slick, currentSlide, nextSlide', event, slick, currentSlide, nextSlide)
            if (currentSlide == 1) {
                $('#modele-carousel-faq').html('.faq .carousel-faq .slick-list{padding:0 0px 0 0 !important;}');
                $('.carousel-faq').slick('setPosition');
                $('.carousel-faq').addClass('modele-carousel-faq-one-slide');
            }
        }
    );
    /**
     * width slide mobile
     */
    let width_slide = ($(window).width() <= 576) ? 330 : 550;

    /**
     * init carousel faq
     */
    if ($(window).width() <= 768) {
        let width_diff = $(".faq .carousel-faq").width() - width_slide;
        //console.log('width_diff', width_diff);
        $('body').append('<style id="modele-carousel-faq">.faq .carousel-faq .slick-list{padding:0 ' + width_diff + 'px 0 0 !important;}</style>');
        $(".carousel-faq").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: false,
            dots: false,
            centerMode: true,
            centerPadding: 0,
            lazyLoad: 'ondemand',


        })
    }
});
