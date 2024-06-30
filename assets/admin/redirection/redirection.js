// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let id = null;
    let error_state = [true];
    let parsed_data_csv = [];
    if(typeof id_redirection !== "undefined"){
        id = id_redirection;
        error_state = [false];
    }else{
        $('form[name="redirection"] p.submit input').attr('disabled','disabled');
    }

    $('#redirection_file').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const contents = e.target.result;
                parsed_data_csv = parseCSV(contents);
                if(parsed_data_csv.length){
                    error_state = [false];
                    $('form[name="redirection"] p.submit input').removeAttr('disabled');
                }
            };
            reader.readAsText(file);
        }
    });
    function parseCSV(data) {
        const lines = data.split('\n');
        const result = [];
        const headers = lines[0].split(',');

        for (let i = 1; i < lines.length; i++) {
            const obj = {};
            const currentline = lines[i].split(',');

            for (let j = 0; j < headers.length; j++) {
                obj[$.trim(headers[j])] = currentline[j];
            }
            result.push(obj);
        }
        return result;
    }
    // on change old 
    $("#redirection_old_root").on('change',function(e){
        let old_url = $(this).val();
        let ele = $(this);
        if(old_url.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/redirection/check-url-old";
            let check_modele_post = main_function.ajaxOperation(url,{old_url:old_url,id:id},'POST');
            check_modele_post.done(function(data) {
                if(data.success){
                    ele.addClass('success').removeClass('error');
                    error_state[0] = false;
                    if(error_state.indexOf(true) === -1 )
                        $('form[name="redirection"] p.submit input').removeAttr('disabled');
                }else{
                    ele.addClass('error').removeClass('success');
                    error_state[0] = true;
                    $('form[name="redirection"] p.submit input').attr('disabled','disabled');
                }
                // end loadig
                ele.siblings('.lds-ellipsis').removeClass('show');
            });
        }else{
            error_state[0] = true;
            $('form[name="redirection"] p.submit input').attr('disabled','disabled');
        }
    });

    $(document).on('click','form[name="redirection"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let old_root = $("#redirection_old_root").val();
            let new_root = $("#redirection_new_root").val();

            if(old_root.length &&  new_root.length && new_root!==old_root || parsed_data_csv.length){
                let url = "/api/redirection/new";
                if(id){
                    url = "/api/redirection/edit-redirection/"+id;
                }
                let redirection_insert = main_function.ajaxOperation(url,{
                    old_root:old_root,
                    new_root:new_root,
                    parsed_data_csv:JSON.stringify(parsed_data_csv),
                    id:id
                },'POST');
                redirection_insert.done(function(data) {
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