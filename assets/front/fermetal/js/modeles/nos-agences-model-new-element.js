//import "../../css/modeles/nos-agences-model-new-element.css";
$(document).ready(function () {
    $(".carousel-agence-modele").on(
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


    $('.carousel-agence-modele').slick({

        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        centerMode: false,
        infinite: false,
        centerPadding: 0,
        lazyLoad: 'ondemand',
        responsive: [
            {
                breakpoint: 1450,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1
                }
            }
        ]



    });
})