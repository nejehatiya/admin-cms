// require ajax call
import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true,true];
    let  submit_botton = $('form[name="template_page"] p.submit input');
    if(typeof id_template_page !== "undefined"){
        id = id_template_page;
        error_state = [false,false];
    }else{
        submit_botton.attr('disabled','disabled');
    }
    // check name modele
    $("#template_page_name").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/template-page/check-name";
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
    $("#template_page_slug").on('change',function(e){
        let slug = $(this).val();
        let ele = $(this);
        if(slug.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/template-page/check-slug";
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
    $(document).on('click','form[name="template_page"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let name = $("#template_page_name").val();
            let slug = $("#template_page_slug").val();
            let post_type = $("#template_page_post_type").val();

            console.log(post_type);
            let status = $("#template_page_status").prop('checked');
            if(name.length ){
                let url = "/api/template-page/new";
                if(id){
                    url = "/api/template-page/edit/"+id;
                }
                let template_page_insert = main_function.ajaxOperation(url,{name:name,slug:slug,post_type:JSON.stringify(post_type),status:status,id:id},'POST');
                template_page_insert.done(function(data) {
                    if(data.success){
                        ele.parent().addClass('success').removeClass('error');
                        
                    }else{
                        ele.parent().addClass('error').removeClass('success');
                    }
                    // end loadig
                    ele.parent().siblings('.lds-ellipsis').removeClass('show');
                });
            }
        }
    })
})