import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let serach_param = {};
    let id_current   = null;
    let prefix_admin  = main_function.apiPrefix();
    let page = 2;
    let load_actif = true;
    //
    let allowed_extension = ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'mp4', 'svg'];
    //
    let html_progress_bar = "";
    // open upload inline media
    $(".open-upload-inline,.uploader-inline button.close").on('click',function(e){
        e.preventDefault();
        $(".uploader-inline").toggleClass('hidden');
    })
    //open file to upload
    $(".button-open-file").on('click',function(e){
        e.preventDefault();
        $(this).siblings('input[type=file]').trigger('click');
    });

    // start upload file
    $(document).on('change',".upload-image-input",function(e){
        $(".media-uploader-status .upload-errors").html('');
        
        $(".media-sidebar").removeClass('d-block');
        let files = $(this).prop('files');
        if(files.length){
            for(let i =0;i<files.length;i++){
                let file = files[i];
                $.when(main_function.uploadFileFormData(file,i)).done(function(i){
                    console.log('oki');
                });
            }
        }
        
    })
    // start get data
    $(document).on('click',".media-frame.mode-grid .attachments-browser .attachments li",function(e){
        id_current  = parseInt($(this).attr('data-id'));
        $(".media-uploader-status .upload-errors").html('');
        $(".media-uploader-status").removeClass('d-block'); 
        $(".media-sidebar").removeClass('d-block');
        if(id_current){
            let this_element = $(this);
            this_element.addClass('load');
            main_function.ajaxOperation("/api/attachement/get-data/"+id_current,null,'GET').done((data)=>{
                if(data.success){
                    $('body').append(data.popup_form);
                }else{
                    $(".media-uploader-status .upload-errors").prepend(main_function.htmlErrorUpload('extension','',data.message)); 
                    $(".media-uploader-status").addClass('d-block');
                    $(".media-sidebar").addClass('d-block');
                }
                this_element.removeClass('load');
            });
        }
    });
    // close form update
    $(document).on('click',".upload-php .media-modal-close",function(e){
        e.preventDefault();
        $(".media-modal-popup").remove();
    })
    // start update data
    $(document).on('change',".media-modal-popup input,.media-modal-popup textarea",function(e){
        let data = {};
        data['description_image'] = $("#attachment-details-two-column-description").val();
        data['name_image'] = $("#attachment-details-two-column-title").val();
        data['alt_image'] = $("#attachment-details-two-column-alt-text").val();
        if(id_current){
            $(".media-modal-popup .attachment-details").addClass('save-waiting');
            let this_element = $(this);
            this_element.addClass('load');
            main_function.ajaxOperation("/api/attachement/edit/"+id_current,data,'POST').done((data)=>{
                console.log('data',data)
                if(data.success){
                    $(".media-modal-popup .attachment-details").removeClass('save-waiting').addClass('save-complete');
                    setTimeout(()=>{
                        $(".media-modal-popup .attachment-details").removeClass('save-complete').addClass('save-ready');
                    },1000)
                }else{
                    $(".media-uploader-status .upload-errors").prepend(main_function.htmlErrorUpload('extension','',data.message)); 
                    $(".media-uploader-status").addClass('d-block');
                    $(".media-sidebar").addClass('d-block');
                    $(".media-modal-popup .attachment-details").removeClass('save-waiting');
                }
                this_element.removeClass('load');
            });
        }
    });
    // copier l'url dans le presse pappier
    $(document).on('click',".media-modal-popup .copy-attachment-url",function(e){
        e.preventDefault();
        main_function.copierTexte($('.media-modal-popup .attachment-details-copy-link').val(),$(this));
    });

    // start search list
    $(document).on('change',".upload-php #media-attachment-filters,.upload-php #media-attachment-date-filters",function(e){
        e.preventDefault();
        // reset page 
        page = 1;
        load_actif = true;
        // set params serach 
        $(".media-frame.mode-grid .media-toolbar.wp-filter").addClass('load');
        main_function.refreshListWithSearch(page).done((data)=>{
            console.log('data',data)
            if(data.success){
                $(".media-frame.mode-grid .attachments-browser .attachments").html(data.list_html);
                $("p.no-media").addClass('d-none');
                page = page + 1;
            }else{
                $(".media-frame.mode-grid .attachments-browser .attachments").html("");
                $("p.no-media").removeClass('d-none');
                load_actif = false;
            }
            $(".media-frame.mode-grid .media-toolbar.wp-filter").removeClass('load');
        });
    });
    $(document).on('keyup',".upload-php #media-search-input",function(e){
        e.preventDefault();
        // reset page 
        page = 1;
        load_actif = true;
        // set params serach 
        $(".media-frame.mode-grid .media-toolbar.wp-filter").addClass('load');
        main_function.refreshListWithSearch(page).done((data)=>{
            console.log('data',data)
            if(data.success){
                $(".media-frame.mode-grid .attachments-browser .attachments").html(data.list_html);
                $("p.no-media").addClass('d-none');
                page = page + 1;
            }else{
                $(".media-frame.mode-grid .attachments-browser .attachments").html("");
                $("p.no-media").removeClass('d-none');
                load_actif = false;
            }
            $(".media-frame.mode-grid .media-toolbar.wp-filter").removeClass('load');
        });
    });
    // on scroll end of element
    $(window).on('scroll',function(e){
        let elem = $(".media-frame.mode-grid .attachments-browser .attachments .attachment:last-of-type");
        let windowHeight = $(window).height();
        let scrollPosition = $(window).scrollTop();
        let  divElement = $('.media-frame.mode-grid .attachments-browser .attachments .attachment:last-of-type');
        let divBottom = divElement.offset().top + divElement.height();
        if( divBottom <= scrollPosition + windowHeight && divBottom + 150 >= scrollPosition + windowHeight && load_actif) {
            console.log('Scrolled to end');
            // set params serach 
            $(".media-frame.mode-grid .attachments-browser").addClass('load');
            main_function.refreshListWithSearch(page).done((data)=>{
                console.log('data',data)
                if(data.success){
                    $(".media-frame.mode-grid .attachments-browser .attachments").append(data.list_html);
                    $("p.no-media").addClass('d-none');
                    page = page + 1;
                }else{
                    load_actif = false;
                }
                $(".media-frame.mode-grid .attachments-browser").removeClass('load');
            });
        }
    })
})