//import "../../css/modeles/nos-services-element.css";
$(document).ready(function($){
    let images = $(".modele-nos-services .tabs-box .tabs-content .section-image-text>div img");
    $.each(images,function(e){
        let img = images.eq(e)
        img.on('load', function () {
            let height = img.height();
            img.parents('.tabs-content').find('.text-column>.content-box').height(height);
        })
    });

    // change click
    $(".modele-nos-services .tabs-box .single-btn").on('click',function(e){
        $(this).addClass('active-btn').siblings('.single-btn').removeClass('active-btn');
        let id = $(this).find('>div').attr('data-tab');
        $(id).addClass('active-tab').siblings('.tabs-content').removeClass('active-tab');
    })
})