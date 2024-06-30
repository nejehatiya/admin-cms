// require ajax call
//import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true];
    let  submit_botton = $('form[name="user"] p.submit input');
    if(typeof id_user !== "undefined"){
        id = id_user;
        error_state = [false];
    }else{
        submit_botton.attr('disabled','disabled');
    }
    // check name modele
    $("#user_email").on('change',function(e){
        let email = $(this).val();
        let ele = $(this);
        if(email.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/users/check-email";
            let check_modele_post = main_function.ajaxOperation(url,{email:email,id:id},'POST');
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


    $(document).on('click','form[name="user"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let email = $("#user_email").val();
            let nom = $("#user_first_name").val();
            let prenom = $("#user_last_name").val();
            let password = $("#user_password").val();
            let roles = $("#user_roles_user").val();
            let active = $("#user_status").val();
            let media_selected = $(".media-selected.image-preview").val();
            if(nom.length && prenom.length && (password.length && !id || id) && email.length){
                let url = "/api/users/new";
                if(id){
                    url = "/api/users/edit-user/"+id;
                }
                let role_insert = main_function.ajaxOperation(url,{
                    nom:nom,
                    prenom:prenom,
                    password:password,
                    email:email,
                    roles_user:JSON.stringify(roles),
                    active:active,
                    image_profile:media_selected,
                    id:id
                },'POST');
                role_insert.done(function(data) {
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