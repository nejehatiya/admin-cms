// require main function
import * as menu_avant from './menu-avant.js';
import * as menu from './menu.js';
import * as main_function from '../../js/main-functions.js';
//wpNavMenu.init();
$(document).ready(function(){
    let button_delete = main_function.deleteButtonHtml();
    let current_template_id = null;
    let data_template = {};
    let id = null;
    let error_state = [true];
    // editor var 
    let editor_var = {};
    let  submit_botton = $('#save_menu_footer12');
    if(typeof id_menu !== "undefined"){
        id = id_menu
        error_state = [false];
        submit_botton.html('mis à jour le menu');
        let url = "/api/menu-builder/get/"+id;
        let check_menu = main_function.ajaxOperation(url,{},'GET');
        check_menu.done(function(data) {
            if(data.success){
                let data_menu = JSON.parse(data?.menu?.menu_content);
                data_menu = main_function.flattenJSON(data_menu);
                for(let index in data_menu){
                    data_template[data_menu[index].id] = data_menu[index].data_json;
                }
            }else{
                console.log('data',data);
            }
        });
    }
    //
    // check name menu
    $("#menu-name").on('change',function(e){
        let name = $(this).val();
        let ele = $(this);
        if(name.length){
            ele.siblings('.lds-ellipsis').addClass('show');
            let url = "/api/menu-builder/check-name";
            let check_menu = main_function.ajaxOperation(url,{name:name,id:id},'POST');
            check_menu.done(function(data) {
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
    $(document).on('click','#save_menu_footer12',function(e){
        e.preventDefault();
        if(error_state.indexOf(true)!==-1){
            e.preventDefault();
        }else{
            let json_menu = main_function.structureMenu(data_template);
            let name_menu = $("#menu-name").val();
            if(name_menu.length){
                let url = "/api/menu-builder/new";
                if(id){
                    url = "/api/menu-builder/edit/"+id;
                }
                let menu_insert = main_function.ajaxOperation(url,{
                    name:name_menu,
                    json_menu:JSON.stringify(json_menu),
                    id:id
                },'POST');
                menu_insert.done(function(data) {
                    if(data.success){
                        if(!id){
                            $("#select-menu-to-edit").append(data.html_option)
                        }
                        
                    }else{
                        //ele.parent().addClass('error').removeClass('success');
                    }
                    // end loadig
                    //ele.parent().siblings('.lds-ellipsis').removeClass('show');
                });
            }
        }
    })
    // start create click button
    $(document).on('click','.nav-menus-php .add-new-menu-action',function(e){
        e.preventDefault();
        id = null;
        $("#menu-to-edit").html('');
        error_state = [true];
        submit_botton.html('enregistrer le menu');
        $("#menu-name").val("");
    })
    // load menu data
    $(document).on('change','#select-menu-to-edit',function(e){
        let ele_id = $(this).val();
        let url = "/api/menu-builder/get/"+ele_id;
        let menu_get_data = main_function.ajaxOperation(url,{},'GET');
        menu_get_data.done(function(data) {
            console.log('data',data);
            if(data.success){
                $("#menu-to-edit").html(data.html_edit);
                id =  ele_id;
                error_state = [false];
                submit_botton.html('mis à jour le menu');
                $("#menu-to-edit").sortable('refresh');
                $("#menu-name").val(data.menu.nameMenu); 
                $(".theme-location-set .emplacements-name").html(data.emplacement_menu)
            }
        });
    });

    // load template forms fields
    $(document).on('change','.edit-menu-mega-template',function(e){
        e.preventDefault();
        let this_element = $(this).hasClass('edit-menu-mega-template')?$(this):$(this).siblings('.edit-menu-mega-template');
        let ele_id = this_element.val();
        
        let ele_name = this_element.find('option:selected').text();
        
        if(ele_id){
            current_template_id = this_element.parents('fieldset').siblings('input.menu-item-data-db-id').val();
            let data_set_form = [];
            if(Object.keys(data_template).indexOf(current_template_id)!==-1){
                data_set_form = data_template[current_template_id];
            }
            let url = "/api/menu-builder/forms/"+ele_id;
            let menu_get_data = main_function.ajaxOperation(url,{data_set:JSON.stringify(data_set_form)},'POST');
            menu_get_data.done(function(data) {
                console.log('data',data);
                if(data.success){
                    current_template_id = this_element.parents('fieldset').siblings('input.menu-item-data-db-id').val();
                    $(".modal.template-menu .model-content .titre").html(ele_name);
                    $('.modal.template-menu .model-content .forms-generate').html(data.html_form);//sortable();
                    $('.modal.template-menu .model-content .forms-generate').animate({
                        scrollTop: $('.modal.template-menu .model-content .forms-generate').offset().top - 20 //#DIV_ID is an example. Use the id of your destination on the page
                    }, 'slow');
                    $('.modal.template-menu').addClass('show');
                    
                    //
                    if($(".modal.template-menu .model-content .forms-generate .post-text-riche").length){
                        $.each($(".modal.template-menu .model-content .forms-generate .post-text-riche"),function(ele){
                            let id = $(".modal.template-menu .model-content .forms-generate .post-text-riche").eq(ele).attr('id');
                            main_function.textareaToCkeidtor(id).then( editor => {
                                editor_var[id] = editor ;
                            } )
                            .catch( error => {
                                console.error( error );
                            } );
                        })
                    }
                    $('.modal.template-menu .model-content .forms-generate').sortable({
                        handle: ".legend"
                    });
                    $('.modal.template-menu .model-content .forms-generate .selectpicker').select2({
                        dropdownParent: $('.modal.template-menu .model-content .forms-generate')
                    });
                }
            });
        }else{
            this_element.parent().siblings('.form').html("");
        }
    });
    
    $(document).on('click','button.edit-template-name-btn',function(e){
        e.preventDefault();
        $(this).siblings('.edit-menu-mega-template').trigger('change');
    })
    // get data from form
    $(document).on('click','.modal.template-menu .model-content .form-footer button.enregister',function(e){
        e.preventDefault();
        let parent_form_container = $(this).parents('.modal.template-menu').find('.forms-generate');

        let blocks_data_element =  parent_form_container.find('>fieldset');
        // start collect data
        data_template[current_template_id] = main_function.collectData(blocks_data_element,editor_var);
        closePoppup();
    });
    // get data from form
    $(document).on('click','.modal.template-menu .model-content .form-footer button.close',function(e){
        e.preventDefault();
        closePoppup();
    })
    // dupliquer fields
    $(document).on('click','.modal.template-menu .model-content .forms-generate>fieldset>legend button.clone-block',function(e){
        e.preventDefault();
        // add button delete
        let ref_block = $(this).parents('fieldset').find('.ref-block-hidden').val();
        // destroy all text rich ebefore cloning
        let instances_destroy = $(this).parents('fieldset').find(".post-text-riche");
        $.each(instances_destroy,function(ele){
            let id = instances_destroy.eq(ele).attr('id');
            if(Object.keys(editor_var).indexOf(id)!=-1){
                editor_var[id].destroy();
                delete editor_var[id];
            }
        })
        $('.selectpicker').select2('destroy'); 
        let items = $(this).parents('fieldset.'+ref_block);
        $.each(items,function(ele){
           let  fieldset = items.eq(ele);
           console.log('fieldset',fieldset,ele);
           if(!fieldset.find('button.delete-block').length){
                $(button_delete).insertBefore(fieldset.find('legend .clone-block '));
           }
        });
        let elem_clone = $(this).parents('fieldset').clone();
        let new_uuid = main_function.createUniqueID();
        $.each(elem_clone.find('input,textarea,select'),function(ele){
            let id = elem_clone.find('input,textarea,select').eq(ele).attr('id');
            if(id != undefined && id.length){  
                elem_clone.find('input,textarea,select').eq(ele).attr('id',id+'-'+new_uuid);
            }
            if(elem_clone.find('input,textarea,select').eq(ele).is('input') && ['checkbox','radio'].indexOf(elem_clone.find('input,textarea,select').eq(ele).attr('type'))!==-1){
                let name = elem_clone.find('input,textarea,select').eq(ele).attr('name');
                if(name != undefined && name.length){  
                    elem_clone.find('input,textarea,select').eq(ele).attr('name',name+'-'+new_uuid);
                }
            }
        })
        // recreate ckeditor instance
        $.each(instances_destroy,function(ele){
            let id = instances_destroy.eq(ele).attr('id');
            main_function.textareaToCkeidtor(id).then( editor => {
                editor_var[id] = editor ;
            } )
            .catch( error => {
                console.error( error );
            } );
        })
        let instances_destroy_cloned = elem_clone.find(".post-text-riche");
        // recreate ckeditor instance
        /**/
        elem_clone.insertAfter($(this).parents('fieldset'));

        $.each(instances_destroy_cloned,function(ele){
            let id = instances_destroy_cloned.eq(ele).attr('id');
            main_function.textareaToCkeidtor(id).then( editor => {
                editor_var[id] = editor ;
            } )
            .catch( error => {
                console.error( error );
            } );
        })
        $('.selectpicker').select2(); 
    })
    // closePoppup 
    function closePoppup(){
        $(".modal.template-menu .model-content .titre").html('');
        $('.modal.template-menu .model-content .forms-generate').html('')
        $('.modal.template-menu').removeClass('show');
        current_template_id = null;
    }
    // toogle open blocks
    $(document).on('click','.modal.template-menu .model-content .forms-generate>fieldset legend',function(e){
        $(this).parent('').toggleClass('open').siblings().removeClass('open');
    });
    // delete block from list
    $(document).on("click",".modal.template-menu .model-content .forms-generate>fieldset>legend button.delete-block",function(e){
        e.preventDefault();
        let ref_block = $(this).parents('fieldset').find('.ref-block-hidden').val();
        let index_element = $(this).parents('fieldset.'+ref_block).index();
        let items = data_template[current_template_id].filter((ele,index)=>{
            return index_element != index;
        })
        data_template[current_template_id] = items;
        
        $(this).parents('fieldset.'+ref_block).remove();
        if(items.length == 1){
            $('fieldset.'+ref_block).find('button.delete-block').remove();
        }
        console.log('data_template[current_template_id]',);
        console.log('index_element',index_element);
    });
})