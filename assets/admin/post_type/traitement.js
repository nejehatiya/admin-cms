// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true,true];
    if(typeof id_post_type !== "undefined"){
        id = id_post_type;
        error_state = [false,false];
    }else{
        $('form[name="post_type"] p.submit input').attr('disabled','disabled');
    }
    // check name modele
    $("#post_type_name_post_type").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/post-type/check-name";
            let check_modele_post = main_function.ajaxOperation(url,{name:name,id:id},'POST');
            check_modele_post.done(function(data) {
                if(data.success){
                    ele.addClass('success').removeClass('error');
                    error_state[0] = false;
                    if(error_state.indexOf(true) === -1 )
                        $('form[name="post_type"] p.submit input').removeAttr('disabled');
                }else{
                    ele.addClass('error').removeClass('success');
                    error_state[0] = true;
                    $('form[name="post_type"] p.submit input').attr('disabled','disabled');
                }
                // end loadig
                ele.siblings('.lds-ellipsis').removeClass('show');
            });
        }else{
            error_state[0] = true;
            $('form[name="post_type"] p.submit input').attr('disabled','disabled');
        }
    });


    // check slug modele
    $("#post_type_slug_post_type").on('change',function(e){
        let slug = $(this).val();
        let ele = $(this);
        if(slug.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/post-type/check-slug-post-type";
            let check_modele_post = main_function.ajaxOperation(url,{slug:slug,id:id},'POST');
            check_modele_post.done(function(data) {
                if(data.success){
                    ele.addClass('success').removeClass('error');
                    ele.val(data.slug);
                    error_state[1] = false;
                    if(error_state.indexOf(true) === -1 )
                        $('form[name="post_type"] p.submit input').removeAttr('disabled');
                }else{
                    ele.addClass('error').removeClass('success');
                    error_state[1] = true;
                    $('form[name="post_type"] p.submit input').attr('disabled','disabled');
                }
                // end loadig
                ele.siblings('.lds-ellipsis').removeClass('show');
            });
        }else{
            error_state[1] = true;
            $('form[name="post_type"] p.submit input').attr('disabled','disabled');
        }
    });
    $(document).on('click','form[name="post_type"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            $(this).removeAttr('disabled');
        }
    })
})