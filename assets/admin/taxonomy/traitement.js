// require ajax call
//import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    // convert description to  ckeidtor
    let editor_var = null;
    if($("#taxonomy_description_taxonomy").length){
        main_function.textareaToCkeidtor("taxonomy_description_taxonomy").then( editor => {
            editor_var =  editor ;
            /*console.log('editor_var',editor_var)
            if(editor_var){
                editor_var.setData( '<p>Some text.</p>' );
            }*/
        } )
        .catch( error => {
            console.error( error );
        } );
    }
    let id = null;
    let error_state = [true,true];
    let  submit_botton = $('form[name="taxonomy"] p.submit input');
    if(typeof id_taxonomy !== "undefined"){
        id = id_taxonomy;
        error_state = [false,false];
    }else{
        submit_botton.attr('disabled','disabled');
    }
    // check name modele
    $("#taxonomy_name_taxonomy").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/taxonomy/check-name";
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
    $("#taxonomy_slug_taxonomy").on('change',function(e){
        let slug = $(this).val();
        let ele = $(this);
        if(slug.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/taxonomy/check-slug";
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
    $(document).on('click','form[name="taxonomy"] p.submit input',function(e){
        if(error_state.indexOf(true) !== -1){
            e.preventDefault();
            $(this).attr('disabled','disabled');
        }else{
            e.preventDefault();
            $(this).removeAttr('disabled');
            let ele =$(this);
            ele.parent().siblings('.lds-ellipsis').addClass('show');
            let name = $("#taxonomy_name_taxonomy").val();
            let slug = $("#taxonomy_slug_taxonomy").val();
            let post_type = $("#taxonomy_Posttype").val();
            let order = $("#taxonomy_OrderTaxonomy").val();
            let taxonomy_statutMenu = $("#taxonomy_statutMenu").prop('checked');
            let taxonomy_is_draft = $("#taxonomy_is_draft").prop('checked');
            let description = editor_var?.getData();
            console.log(post_type);
            let status_in_sidebar = $("#taxonomy_StatutSideBar").prop('checked');
            if(name.length && slug.length ){
                let url = "/api/taxonomy/new";
                if(id){
                    url = "/api/taxonomy/edit/"+id;
                }
                let taxonomy_insert = main_function.ajaxOperation(url,{
                    name:name,
                    slug:slug,
                    post_type:JSON.stringify(post_type),
                    order:order,
                    taxonomy_statutMenu:taxonomy_statutMenu,
                    taxonomy_is_draft:taxonomy_is_draft,
                    description:description,
                    status_in_sidebar:status_in_sidebar,
                    id:id
                },'POST');
                taxonomy_insert.done(function(data) {
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