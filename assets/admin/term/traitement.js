// require ajax call
//import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    // convert description to  ckeidtor
    let editor_var = null;
    if($("#terms_description_terms").length){
        main_function.textareaToCkeidtor("terms_description_terms").then( editor => {
            editor_var =  editor ;
        } )
        .catch( error => {
            console.error( error );
        } );
    }
    let id = null;
    let current_index = null;
    let id_taxo = null;
    if(typeof id_taxonomy !== "undefined"){
        id_taxo = id_taxonomy;
    }
    let error_state = [true,true];
    let  submit_botton = $('form[name="terms"] p.submit input');

    // check name modele
    $("#terms_name_terms").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/terms/check-name";
            let check_modele_post = main_function.ajaxOperation(url,{name:name,id:id},'POST');
            check_modele_post.done(function(data) {
                if(data.success){
                    ele.addClass('success').removeClass('error');
                    error_state[0] = false;
                    if(error_state.indexOf(true) === -1 )
                        submit_botton.removeAttr('disabled');
                }else{
                    ele.addClass('error').removeClass('success');
                    error_state[0] = true;
                    submit_botton.attr('disabled','disabled');
                }
                // end loadig
                ele.siblings('.lds-ellipsis').removeClass('show');
            });
        }else{
            error_state[0] = true;
            submit_botton.attr('disabled','disabled');
        }
    });


    // check slug modele
    $("#terms_slug_terms").on('change',function(e){
        let slug = $(this).val();
        let ele = $(this);
        if(slug.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/terms/check-slug";
            let check_modele_post = main_function.ajaxOperation(url,{slug:slug,id:id},'POST');
            check_modele_post.done(function(data) {
                if(data.success){
                    ele.addClass('success').removeClass('error');
                    ele.val(data.slug);
                    error_state[1] = false;
                    if(error_state.indexOf(true) === -1 )
                        submit_botton.removeAttr('disabled');
                }else{
                    ele.addClass('error').removeClass('success');
                    error_state[1] = true;
                    submit_botton.attr('disabled','disabled');
                }
                // end loadig
                ele.siblings('.lds-ellipsis').removeClass('show');
            });
        }else{
            error_state[1] = true;
            submit_botton.attr('disabled','disabled');
        }
    });
    $(document).on('click','form[name="terms"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let name = $("#terms_name_terms").val();
            let slug = $("#terms_slug_terms").val();
            let parent = $("#terms_parentTerms").val();
            let media_selected = $(".media-selected.image-preview").val();
            let description = editor_var?.getData();
            let terms_is_draft = $("#terms_is_draft").prop('checked');
            if(name.length && slug.length ){
                let url = "/api/terms/new";
                if(id){
                    url = "/api/terms/edit/"+id;
                }
                let term_insert = main_function.ajaxOperation(url,{
                    name:name,
                    slug:slug,
                    media_selected:media_selected,
                    parent:parent,
                    terms_is_draft:terms_is_draft,
                    description:description,
                    id_taxo:id_taxo,
                    id:id
                },'POST');
                term_insert.done(function(data) {
                    if(data.success){
                        if(Object.keys('tr_html') !== -1){
                            if(id){
                                $("#the-list").find('tr').eq(current_index).replaceWith(data.tr_html);
                            }else{
                                $("#the-list").prepend(data.tr_html);
                            }
                            
                            viderForms();
                            $('html, body').animate({
                                scrollTop: $('#the-list').offset().top - 100
                            }, 10); 
                        }
                        
                    }else{
                        ele.parent().addClass('error').removeClass('success');
                    }
                    // end loadig
                    ele.parent().siblings('.lds-ellipsis').removeClass('show');
                });
            }
        }
    });
    // set the form
    $(document).on('click','#the-list .row-actions .edit a',function(e){
        e.preventDefault();
        current_index = $(this).parents('tr').index();
        id = $(this).parents('tr').find('>th>input[type=checkbox]').val();
        if(id){
            let url = "/api/terms/get/"+id;
            let term_data = main_function.ajaxOperation(url,{},'GET');
            term_data.done(function(data) {
                if(data.success){
                    viderForms();
                    setForms(data.term,data.image_preview);
                    
                }else{
                    //ele.parent().adata.ddClass('error').removeClass('success');
                }
                // end loadig
                //ele.parent().siblings('.lds-ellipsis').removeClass('show');
            });
        }
    })
    // vider la form
    function viderForms(){
        $("#terms_name_terms").removeClass('error success').val('')
        $("#terms_slug_terms").removeClass('error success').val('');
        $("#terms_parentTerms").val('').trigger('change').select2();;
        $(".media-selected.image-preview").val('');
        $(".previewimage").html('');
        editor_var?.setData('');
        $("#terms_is_draft").prop('checked',false);
        id=null;
        submit_botton.val('Enregistrer');
        error_state = [true,true];
        current_index = null;
    }
    // remplire la form
    function setForms(data,image){
        $("#terms_name_terms").removeClass('error success').val(data.name_terms)
        $("#terms_slug_terms").removeClass('error success').val(data.slug_terms);
        $("#terms_parentTerms").val(data.parentTerms?data.parentTerms:'').trigger('change').select2();;
        $(".media-selected.image-preview").val(data?.image?.id);
        $(".previewimage").html(image.length?'<img src="'+image+'" />':'');
        editor_var?.setData(data.description_terms);
        $("#terms_is_draft").prop('checked',data.is_draft);
        submit_botton.val('Modifier');
        id=data.id;
        error_state = [false,false];
    }
})