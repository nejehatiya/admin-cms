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
    // init id_current_media_edit
    let id_current = 0;
    let load_actif = true;
    let page = 2;
    // ouvrire le popup de selection image
    $(document).on('click','.open-media-action',function(e){
        e.preventDefault();
        let this_button = $(this);
        this_button.addClass('load');
        main_function.ajaxOperation("/api/attachement/popup-media-selection",{},'GET').done((data)=>{
            console.log('data',data);
            $('body').append(data.popup_media_select);
            this_button.removeClass('load');
            // start loadmore 
            $(document).find("#__wp-uploader-id-2 .attachments-browser.has-load-more .attachments-wrapper").on('scroll',function(e){
                let $this = $(this);
                //console.log('$this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight',$this.scrollTop() + $this.innerHeight() , $this[0].scrollHeight);
                if ( (( ( $this.scrollTop() + $this.innerHeight() )+10 ) >= $this[0].scrollHeight) && load_actif) {
                    // set params serach 
                    $("#__wp-uploader-id-2 .attachments-browser .media-toolbar").addClass('load');
                    main_function.refreshListWithSearch(page).done((data)=>{
                        console.log('data',data)
                        if(data.success){
                            $("#__wp-uploader-id-2  .attachments").append(data.list_html);
                            $("p.no-media").addClass('d-none');
                            page = page + 1;
                        }else{
                            load_actif = false;
                        }
                        $("#__wp-uploader-id-2  .attachments-browser .media-toolbar").removeClass('load');
                    });
                }
            });
        })
    });
    // selection 
    $(document).on('click','#__wp-uploader-id-2 li.attachment',function(e){
        e.preventDefault();
        let is_checked = $(this).hasClass('selected');
        id_current = $(this).attr('data-id');
        if(is_checked){
            $(this).removeClass('selected details');
            $('#__wp-uploader-id-2 .attachment-details').remove();
        }else{
            $('#__wp-uploader-id-2 .attachment-details').remove();
            $(this).addClass('selected details');
            $(this).siblings('li').removeClass('selected details');
            // load popup detail
            $("#__wp-uploader-id-2  .media-sidebar").addClass('load');
            main_function.ajaxOperation("/api/attachement/attachment-detail/"+id_current,{},'GET').done((data)=>{
                console.log('data',data);
                $("#__wp-uploader-id-2  .media-sidebar").append(data.attachment_details);
                $("#__wp-uploader-id-2  .media-sidebar").removeClass('load');
            })
        }
        if($("#__wp-uploader-id-2 li.attachment.selected").length){
            $("#__wp-uploader-id-2 button.media-button-select").removeAttr('disabled');
        }else{
            $("#__wp-uploader-id-2 button.media-button-select").attr('disabled','disabled');
        }
    });
    // start update data
    $(document).on('change',"#__wp-uploader-id-2 .attachment-details input,#__wp-uploader-id-2 .attachment-details textarea",function(e){
        let data = {};
        data['description_image'] = $("#__wp-uploader-id-2 .attachment-details #attachment-details-description").val();
        data['name_image'] = $("#__wp-uploader-id-2 .attachment-details #attachment-details-title").val();
        data['alt_image'] = $("#__wp-uploader-id-2 .attachment-details #attachment-details-alt-text").val();
        if(id_current){
            $("#__wp-uploader-id-2 .attachment-details").addClass('save-waiting');
            let this_element = $(this);
            this_element.addClass('load');
            main_function.ajaxOperation("/api/attachement/edit/"+id_current,data,'POST').done((data)=>{
                console.log('data',data)
                if(data.success){
                    $("#__wp-uploader-id-2 .attachment-details").removeClass('save-waiting').addClass('save-complete');
                    setTimeout(()=>{
                        $("#__wp-uploader-id-2 .attachment-details").removeClass('save-complete').addClass('save-ready');
                    },1000)
                }
                this_element.removeClass('load');
            });
        }
    });
    // copier l'url dans le presse pappier
    $(document).on('click',"#__wp-uploader-id-2 .attachment-details  .copy-attachment-url",function(e){
        e.preventDefault();
        main_function.copierTexte($('#__wp-uploader-id-2 .attachment-details  .attachment-details-copy-link').val(),$(this));
    });
    // start search list
    $(document).on('keyup',"#__wp-uploader-id-2 #media-search-input",function(e){
        e.preventDefault();
        // reset page 
        page = 1;
        load_actif = true;
        // set params serach 
        $("#__wp-uploader-id-2  .attachments-browser .media-toolbar").addClass('load');
        $('#__wp-uploader-id-2 .attachment-details').remove();
        $("#__wp-uploader-id-2 button.media-button-select").attr('disabled','disabled');
        main_function.refreshListWithSearch(page).done((data)=>{
            console.log('data',data)
            if(data.success){
                $("#__wp-uploader-id-2  .attachments").html(data.list_html);
                $("#__wp-uploader-id-2 p.no-media").addClass('d-none');
                page = page + 1;
            }else{
                $("#__wp-uploader-id-2  .attachments").html("");
                $("#__wp-uploader-id-2 p.no-media").removeClass('d-none');
                load_actif = false;
            }
            $("#__wp-uploader-id-2  .attachments-browser .media-toolbar").removeClass('load');
        });
    });
    // switch tabs
    $(document).on('click',"#__wp-uploader-id-2  .media-router .media-menu-item",function(e){
        e.preventDefault();
        if($(this).hasClass('active')){
            return false;
        }
        let id_attr = $(this).attr('id');
        if(id_attr == "menu-item-upload"){
            $("#__wp-uploader-id-2  .uploader-inline-content").removeClass('d-none').siblings('div').addClass('d-none');
            $(this).addClass('active').siblings('button').removeClass('active');
        }else{
            $("#__wp-uploader-id-2  .uploader-inline-content").addClass('d-none').siblings('div').removeClass('d-none');
            $(this).addClass('active').siblings('button').removeClass('active');
        }
    });
    // téléverser les fichier
    $(document).on('click',"#__wp-uploader-id-2 #__wp-uploader-id-1",function(e){
        e.preventDefault();
        $(this).siblings('input[type=file]').trigger('click');
        //$("#__wp-uploader-id-2  .media-router .media-menu-item#menu-item-browse").trigger('click')
    })
    // start upload file
    $(document).on('change',"#__wp-uploader-id-2 .upload-image-input",function(e){
        $(".media-uploader-status .upload-errors").html('');
        
        $(".media-sidebar").removeClass('d-block');
        let files = $(this).prop('files');
        $("#__wp-uploader-id-2  .media-router .media-menu-item#menu-item-browse").trigger('click')
        if(files.length){
            for(let i =0;i<files.length;i++){
                let file = files[i];
                main_function.uploadFileFormData(file,i,'#__wp-uploader-id-2 ');
            }
        }
        
    })
})
