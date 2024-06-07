// require ajax call
//import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true];
    let  submit_botton = $('form[name="emplacement"] p.submit input');
    if(typeof id_emplacement !== "undefined"){
        id = id_emplacement;
        error_state = [false];
    }else{
        submit_botton.attr('disabled','disabled');
    }
    // check name modele
    $("#emplacement_key_emplacement").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/emplacement/check-name";
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


    $(document).on('click','form[name="emplacement"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let name = $("#emplacement_key_emplacement").val();
            let emplacement_menu = $("#emplacement_menu").val();
            let emplacement_status = $("#emplacement_status").prop('checked');
            if(name.length ){
                let url = "/api/emplacement/new";
                if(id){
                    url = "/api/emplacement/edit/"+id;
                }
                let emplacement_insert = main_function.ajaxOperation(url,{
                    name:name,
                    emplacement_menu:emplacement_menu,
                    emplacement_status:emplacement_status,
                    id:id
                },'POST');
                emplacement_insert.done(function(data) {
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