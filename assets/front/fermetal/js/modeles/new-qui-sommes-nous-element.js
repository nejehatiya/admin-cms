//import "../../css/modeles/modele-qui-sommes-nous.css";


$(document).ready(function () {
    /**
     * show lire suite text
     */
    $('.lire-suite-qui-sommes-nous').on('click', function (e) {
        e.preventDefault();
        if ($('.content-description').hasClass('show-lire-suite')) {
            $('.content-description').removeClass('show-lire-suite').addClass('hidden-lire-suite');
            $('.lire-suite-qui-sommes-nous').text('Lire moins');
        } else {
            $('.content-description').removeClass('hidden-lire-suite').addClass('show-lire-suite');
            $('.lire-suite-qui-sommes-nous').text('Lire la suite');
        }
    });


    
    /**
         * fix height iframe
         */
    let iframe = $(".section-qui-somme-nous .content-img-text");
    iframe.each(function (index) {
        let video = iframe.eq(index).find('iframe');
        //console.log(video.height());
        let height_title = video.parents('.section-qui-somme-nous').find('description').find(".sec-title").height();
        let height_button = video.parents('.section-qui-somme-nous').find('description').find(".button").height();
        video.on('load', function (e) {
            let height_video = video.height();
            let height_video_content_description = height_video - (height_title + height_button + 30);
            //console.log('height_video_content_description', height_video_content_description);

            if (video.parents('.qui-somme-nous-page').length) {
                //console.log('page');
                video.parents('.section-qui-somme-nous').find('.conetnt-description').height(height_video);
            } else {
                video.parents('.section-qui-somme-nous.mode-1-3').find('.content-description').height(height_video_content_description);

            }
        })

    })

    $(".section-qui-somme-nous .content-img-text figure img").on('load',function(){            
            let img = $(this);
            //console.log(img.innerHeight());
            let height = img.height() + 20;
            let height_title = $(this).parents('.section-qui-somme-nous').find('description').find(".sec-title").height();
            let height_button = $(this).parents('.section-qui-somme-nous').find('description').find(".button").height();

            height_title = height_title ? height_title : 0;
            height_button = height_button ? height_button : 0;
            //console.log('height_title', height_title);
            //console.log('height_button', height_button);
            //console.log('height', height);
            let width = img.width();
            let height_content_description = height - (height_title + height_button);

            if (img.parents('.qui-somme-nous-page').length) {
                //console.log('page');
                img.parents('.section-qui-somme-nous').find('.content-description').height(height);
            } else {
                //console.log('accueil', img.parents('.section-qui-somme-nous.mode-1-2').find('.content-description').height(height));
                img.parents('.section-qui-somme-nous.mode-1-2').find('.content-description').height(height);
                img.parents('.section-qui-somme-nous.mode-1-3').find('.content-description').height(height_content_description);
            }
            if (!img.parents('.qui-somme-nous-page').length || true) {
                let width_description = 'calc( 100% - ' + width + 'px - 25px )';
                if (img.parents('.qui-somme-nous-page').length) {
                    width_description = 'calc( 100% - ' + width + 'px - 0px )';
                }
                //img.parent().width(width+'px');
                img.parents('.section-qui-somme-nous.mode-1-2').find('.description').width(width_description);
                img.parents('.section-qui-somme-nous.mode-1-3').find('.description').width(width_description);
            }
    })
});