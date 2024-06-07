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

// import main function 
//import './js/admin-post-modals.js';

/**
 * start js 
 */
$(document).ready(function(){
    if($('.selectpicker').length){
        $('.selectpicker').select2({
            placeholder: 'selectionner une option',
            allowClear: true
        });
    }
    // init id_current_media_edit
    let id_current = 0;
    let load_actif = true;
    let page = 2;
    let open_button = null;
    let is_multiple = false;
    let max_select = 1;
    // ouvrire le popup de selection image
    $(document).on('click','.open-media-action',function(e){
        e.preventDefault();
        let this_button = $(this);
        open_button = this_button;
        this_button.addClass('load');
        let list_selectionner = open_button.siblings('input.media-selected').val().split(',');
        let check_multiple = open_button.siblings('input.media-selected').attr("data-multiple");
        if(check_multiple === true || check_multiple === "true"){
            is_multiple = true;
            if(parseInt(open_button.siblings('input.media-selected').attr("data-max-select"))){
                max_select = parseInt(open_button.siblings('input.media-selected').attr("data-max-select"));
            }
        }
        main_function.ajaxOperation("/api/attachement/popup-media-selection",{list_selectionner:list_selectionner},'POST').done((data)=>{
            console.log('data',data);
            $('body').append(data.popup_media_select);
            this_button.removeClass('load');
            if(Object.keys(data).indexOf('id_current')!== -1){
                id_current = parseInt(data.id_current);
            }
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
            if(!is_multiple || is_multiple && max_select> $("#__wp-uploader-id-2 li.attachment.selected").length){
                $('#__wp-uploader-id-2 .attachment-details').remove();
                $(this).addClass('selected details');
                if(!is_multiple){
                    $(this).siblings('li').removeClass('selected details');
                }
                // load popup detail
                $("#__wp-uploader-id-2  .media-sidebar").addClass('load');
                main_function.ajaxOperation("/api/attachement/attachment-detail/"+id_current,{},'GET').done((data)=>{
                    console.log('data',data);
                    $("#__wp-uploader-id-2  .media-sidebar").append(data.attachment_details);
                    $("#__wp-uploader-id-2  .media-sidebar").removeClass('load');
                })
            }
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
                $.when(main_function.uploadFileFormData(file,i,'#__wp-uploader-id-2 ')).done(function(i){
                    console.log('oki',i);
                });
            }
        }
    })    
    // set selecionner click button
    $(document).on('click','#__wp-uploader-id-2 .media-button-select',function(e){
        e.preventDefault();
        let src=[];
        let ids = [];
        let selectionner_image = $('#__wp-uploader-id-2 li.attachment.selected');
        if(selectionner_image.length){
            for(let i = 0;i<selectionner_image.length;i++){
                console.log('selectionner_image',selectionner_image.eq(i).find('img').attr('src'));
                if(open_button.find('.elementor-control-media__preview').length){
                    src.push(selectionner_image.eq(i).find('img').attr('src'));
                }else{
                    src.push("<img src='"+selectionner_image.eq(i).find('img').attr('src')+"' />");
                }
                
                ids.push(selectionner_image.eq(i).attr('data-id'));
            }
            if(open_button!=null){
                open_button.siblings('input.media-selected').val(ids.join(','));
                console.log("src.join('')",src.join(''));
                if(open_button.find('.elementor-control-media__preview').length){
                    open_button.find('.elementor-control-media__preview').css({'backgroundImage':'url('+src[0]+')'})
                }else{
                    open_button.siblings('div.previewimage').html(src.join(''));
                }
                $("#__wp-uploader-id-2").remove();
                open_button = null;
                id_current = 0;
            }
        }

    })
    // close button popup media
    $(document).on('click','#__wp-uploader-id-2 .media-modal-close',function(e){
        $("#__wp-uploader-id-2").remove();
        open_button = null;
        id_current = 0;
    })
})
