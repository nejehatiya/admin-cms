/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import './styles/dashbord-wordpress.css';
import  './styles/css/wp-admin.css';

import './styles/app.css';
import * as main_function from './js/main-functions.js';
// main function
//import * as main_function from './js/main-functions.js';
//window.main_function = main_function; 
// init select picker
if($('.selectpicker').length){
    $('.selectpicker').selectpicker();
}
// import main function 
//import './js/admin-post-modals.js';

/**
 * start js 
 */
$(document).ready(function(){
    // ouvrire le popup de selection image
    $(document).on('click','.open-media-action',function(e){
        e.preventDefault();
        main_function.ajaxOperation("/api/attachement/popup-media-selection",{},'GET').done((data)=>{
            console.log('data',data);
            $('body').append(data.popup_media_select);
        })
    });
    // selection 
    $(document).on('click','#__wp-uploader-id-2 li.attachment',function(e){
        e.preventDefault();
        let is_checked = $(this).hasClass('selected');
        if(is_checked){
            $(this).removeClass('selected details');
        }else{
            $(this).addClass('selected details');
            $(this).siblings('li').removeClass('selected details');
        }
    })
})
