// require ajax call
//import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true];
    let  submit_botton = $('form[name="roles"] p.submit input');
    if(typeof id_role !== "undefined"){
        id = id_role;
        error_state = [false];
    }else{
        submit_botton.attr('disabled','disabled');
    }
    // check name modele
    $("#roles_role").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/roles/check-name";
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


    $(document).on('click','form[name="roles"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let name = $("#roles_role").val();
            if(name.length ){
                let url = "/api/roles/new";
                if(id){
                    url = "/api/roles/edit-role/"+id;
                }
                let role_insert = main_function.ajaxOperation(url,{
                    name:name,
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