import './fonts-elementor.css'
import './post.css'

// require main function
import * as main_function from '../../js/main-functions.js';

$(document).ready(function(){
    // display loading until data get
    $("#elementor-loading").addClass('d-block');
    let id = null;

    // check exitance of id post
    if(typeof id_post !== "undefined" ){
        id = id_post;
        
    }
    /**
     * structure json of model
     * {"is_model":true,"json_data":"{\"modele_id\":16,\"class_sortable\":\"bouton-element\",\"is_duplicated\":true,\"status\":true}"}]
     */
    // init id model current on ajout
    let id_model_current = null;
    let current_uuid = null;
    let class_sortable_current = null;
    // get button delete html
    let button_delete = main_function.deleteButtonHtml();
    // init list model
    let list_modele_content = [];
    //

    // editor var for post modélés
    let editor_var = {};
    // editor var for post meta
    let editor_var_meta = {};
    // set post type
    let post_type = $("#post_type_slug").val();
    $("#post_type_slug").remove();
    // base url api for all request
    let base_url = '/api/'+post_type+'/';
    // get list modeles
    if(id){
        $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
        main_function.ajaxOperation(base_url+'get/'+id).done((response)=>{
            if(response.success){
                if(response.post?.post_order_content && response.post?.post_order_content !== "null"){

                    list_modele_content = JSON.parse(response.post.post_order_content);
                }
                console.log('list_modele_content',list_modele_content);
                $("#elementor-loading").removeClass('d-block');
            }
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        }).fail((error)=>{
            $("#elementor-loading").removeClass('d-block');
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        })
    }else{
        $("#elementor-loading").removeClass('d-block');
        $("#elementor-preview-loading").addClass('d-none');
    }
    // toggle elelments panel category
    $(".elementor-panel-category>button").on('click',function(e){
        e.preventDefault();
        $(this).parent().addClass('open').siblings().removeClass('open');
    });

    // change tab 
    $(document).on('click',"#elementor-panel-elements-navigation .elementor-panel-navigation-tab",function(e){
        e.preventDefault();
        let id = $(this).attr('data-tab');
        console.log('id',id)
        $(this).addClass('elementor-active').siblings().removeClass('elementor-active');
        $("#"+id).removeClass('hide-pannel').siblings().addClass('hide-pannel');
        if(id.indexOf("elementor-panel-elements-modals") !==-1){
            $("#elementor-panel-elements-search-area").removeClass('d-none');
        }else{
            $("#elementor-panel-elements-search-area").addClass('d-none');
            $('.form-html-container .form-footer button.close').trigger('click');
        }
    });

    // load form and set data 
    $(document).on('click',"#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section .elementor-element",function(e){
        e.preventDefault();
        let id = $(this).find('.modele-id').val();
        id_model_current = id;
        let data = {data_set:[]};
        $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
        main_function.ajaxOperation(base_url+'get-form/'+id,data,'POST').done((response)=>{
            console.log('response',response.html_form)
            class_sortable_current  =  response.class_sortable;
            $("#elementor-panel-elements-search-area").addClass('d-none');
            $(".form-html-container").siblings().addClass('d-none');
            $(".form-html-container .form").html(response.html_form).parent().addClass('show');
            // create instance of ckeditor
            if($(".form-html-container .form .post-text-riche").length){
                $.each($(".form-html-container .form .post-text-riche"),function(ele){
                    let id = $(".form-html-container .form .post-text-riche").eq(ele).attr('id');
                    main_function.textareaToCkeidtor(id).then( editor => {
                        editor_var[id] = editor ;
                    } )
                    .catch( error => {
                        console.error( error );
                    } );
                })
            }
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            // set selectpicker
            $('.selectpicker').select2();
        }).fail((error)=>{
            console.error('error',error)
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        })
    });
    // switch between modele section
    $(document).on('click','.form-html-container legend',function(e){
        e.preventDefault();
        $(this).parent('fieldset').addClass('open').siblings('fieldset').removeClass('open');
    })
    // anuller ajout de modéle
    $(document).on('click','.form-html-container .form-footer button.close',function(e){
        if(!$("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-modals]").hasClass('elementor-active')){
            $("#elementor-panel-elements-search-area").addClass('d-none');
        }else{
            $("#elementor-panel-elements-search-area").removeClass('d-none');
        }
        $(".form-html-container").removeClass('show').siblings().removeClass('d-none');
        $(".form-html-container .form").html("");
        $(".form-html-container .form-footer .options-enregistrement input[type=checkbox]").prop('checked',false).removeAttr('checked');
        editor_var = {};
        id_model_current = class_sortable_current = current_uuid = null;
    })

    // clone  block in model
    $(document).on('click','.form-html-container legend button.clone-block,.elementor-panel-category.open .elementor-panel-category-items.post-meta-section legend button.clone-block',function(e){
        e.preventDefault();
        // check if is postmeta or moedele clone
        let is_modal = true;
        if($(this).parents('.form-html-container').length){
            is_modal = false;
        }
        // add button delete
        let ref_block = $(this).parents('fieldset').find('.ref-block-hidden').val();
        // destroy all text rich ebefore cloning
        let instances_destroy = $(this).parents('fieldset').find(".post-text-riche");
        $.each(instances_destroy,function(ele){
            let id = instances_destroy.eq(ele).attr('id');
            if(Object.keys(editor_var).indexOf(id)!=-1 && !is_modal || Object.keys(editor_var_meta).indexOf(id)!=-1 && is_modal){
 
                if(is_modal){
                    editor_var_meta[id].destroy();
                    delete editor_var_meta[id];

                }else{
                    editor_var[id].destroy();
                    delete editor_var[id];
                }
            }
        })
        let items = $(this).parents('fieldset.'+ref_block);
        $.each(items,function(ele){
           let  fieldset = items.eq(ele);
           if(!fieldset.find('button.delete-block').length){
                $(button_delete).insertBefore(fieldset.find('legend .clone-block '));
           }
        });
        $('.selectpicker').select2('destroy'); 
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
                //editor_var[id] = editor ;
                if(is_modal){
                    editor_var_meta[id] = editor ;
                }else{
                    editor_var[id] = editor ;
                }
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
                if(is_modal){
                    editor_var_meta[id] = editor ;
                }else{
                    editor_var[id] = editor ;
                }
            } )
            .catch( error => {
                console.error( error );
            } );
        })
        $('.selectpicker').select2();
        $("#elementor-preview-responsive-wrapper .preview-modal-list").sortable( "refresh" );
    })
    
    
    // enregistrer data 
    $(document).on('click','.form-html-container .form-footer button.enregister',function(e){
        e.preventDefault();
        let content_default = $(".form-html-container .form-footer #post-default-content-2").prop('checked');

        let parent_form_container = $(this).parents('.form-html-container ').find('.form');

        let blocks_data_element =  parent_form_container.find('>fieldset');
        // start collect data
        let data_form = main_function.collectData(blocks_data_element,editor_var);
        // get preview html 
        let new_model = {is_model:true,class_sortable:class_sortable_current,id_model:id_model_current,uuid:main_function.createUniqueID(),json_data:data_form};
        
        if(current_uuid !== null){
            new_model = {is_model:true,class_sortable:class_sortable_current,id_model:id_model_current,uuid:current_uuid,json_data:data_form};
            let index_cuurent_model = list_modele_content.findIndex((data)=>{return data.uuid == current_uuid;});
            if(index_cuurent_model>-1){
                list_modele_content[index_cuurent_model] = new_model;
            }
        }else{ 
            console.log('new_model',new_model)
            console.log('list_modele_content',list_modele_content)
            list_modele_content.push(new_model);
        }
        $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
        main_function.ajaxOperation(base_url+'get-preview-modele/'+id_model_current,{data:JSON.stringify(new_model),content_default:content_default},'POST').done((response)=>{
            if(current_uuid!==null){
                console.log('$("#elementor-preview-responsive-wrapper .preview-modal-list>section[data-uuid="+current_uuid+"]")',$("#elementor-preview-responsive-wrapper .preview-modal-list>section[data-uuid="+current_uuid+"]").length);
                console.log('current_uuid',current_uuid)
                $("#elementor-preview-responsive-wrapper .preview-modal-list>section[data-uuid="+current_uuid+"]").replaceWith(response.preview_html);
            }else{
                $("#elementor-preview-responsive-wrapper .preview-modal-list").append(response.preview_html);
                $("#elementor-navigator__elements .elementor-navigator__elements").append(response.structure_form);
            }
            $('.form-html-container .form-footer button.close').trigger('click');
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        }).fail((error)=>{
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        })
    });

    
    // delete block from list
    $(document).on("click",".form-html-container legend button.delete-block,.elementor-panel-category.open .elementor-panel-category-items.post-meta-section legend button.delete-block",function(e){
        e.preventDefault();
        let ref_block = $(this).parents('fieldset').find('.ref-block-hidden').val();
        $(this).parents('fieldset.'+ref_block).remove();
        if($('fieldset.'+ref_block).length == 1){
            $('fieldset.'+ref_block).find('button.delete-block').remove();
        }
    });
    
    // init editors for post meta
    console.log('$(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche").length',$(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche").length)
    if($(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche").length){
        let instances_editors_create = $(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche");
        $.each(instances_editors_create,function(ele){
            let id = instances_editors_create.eq(ele).attr('id');
            main_function.textareaToCkeidtor(id).then( editor => {
                editor_var_meta[id] = editor ;
                console.log('editor_var_meta',editor_var_meta)
            } )
            .catch( error => {
                console.error( error );
            } );
        })
    }

    // post-title
    $(document).on('keyup',"#post-title",function(e){
        let post_tile = $("#post-title").val();
        let parent_id = parseInt($("#post-parent").val());
        $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
        main_function.ajaxOperation(base_url+'check-slug',{slug:post_tile,parent_id:parent_id,id:id},'POST').done((data)=>{
            console.log('data',data)
            if(data.success){
                $("#post-name").val(data.slug);
            }
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        }).fail((error)=>{
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        })
    })
    // post-title
    $(document).on('change',"#post-title",function(e){
        let post_tile = $("#post-title").val();
        let parent_id = parseInt($("#post-parent").val());
        $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
        main_function.ajaxOperation(base_url+'post-titre-change',{slug:post_tile,parent_id:parent_id,id:id},'POST').done((data)=>{
            console.log('data',data)
            if(data.success){
                document.title = post_tile;
                window.history.pushState({"pageTitle":post_tile},"", data.url_page_edit);
                id = data.id;
            }
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        }).fail((error)=>{
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
        })
    });


    // mis à jour le  post
    $(document).on('click','.elementor-panel #elementor-panel-saver-button-publish',function(e){
        e.preventDefault();
        if(id){
            let titre = $("#post-title").val();
            let slug = $("#post-name").val();
            let excerpt = $("#post-excerpt").val();
            let template = $("#post-template").val();
            let page_prent = parseInt($("#post-parent").val());
            let status = $("#post-status").val();
            let image_feature = parseInt($(".media-selected.image-post-feature").val());
            let terms = [];
            let terms_selection = $(".elementor-taxonomy-selection").find('select');
            $.each(terms_selection,function(e){
                terms = terms.concat(terms_selection.eq(e).val());
            });
            console.log('terms',terms);
            let author = parseInt($("#post-title").val());
            // collect data post meta
            let parent_form_container = $('.elementor-panel-category.open .elementor-panel-category-items.post-meta-section');

            let blocks_data_element =  parent_form_container.find('>fieldset');
            // start collect data
            let data_form = main_function.collectData(blocks_data_element,editor_var_meta);
            console.log('data_form',data_form);
            /*let new_model = {is_model:true,id_model:id_model_current,uuid:main_function.createUniqueID(),json_data:data_form};
            list_modele_content.push(new_model);*/
            let post_meta = JSON.stringify(data_form);
            let model_content = JSON.stringify(list_modele_content);

            // log all data
            console.log({
                titre:titre,
                slug:slug,
                excerpt:excerpt,
                page_prent:page_prent,
                image_feature:image_feature,
                terms:JSON.stringify(terms),
                author:author,
                post_meta:post_meta,
                model_content:model_content,
                template:template,
                status:status,
            });
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
            main_function.ajaxOperation(base_url+'edit/'+id,{
                titre:titre,
                slug:slug,
                excerpt:excerpt,
                page_prent:page_prent,
                image_feature:image_feature,
                terms:JSON.stringify(terms),
                author:author,
                post_meta:post_meta,
                model_content:model_content,
                template:template,
                status:status,
            },'POST').done(function(response){
                console.log('response',response);
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            }).fail((error)=>{
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            })
        }
    }) 
    // delete model
    $(document).on('click','#elementor-preview-responsive-wrapper .preview-modal-list>section .elementor-editor-section-settings li.elementor-editor-element-remove',function(e){
        e.preventDefault();
        let uuid = $(this).parents('section.elementor-element').attr('data-uuid');
        $(this).parents('section.elementor-element').remove();
        $(".elementor-navigator__elements>.elementor-navigator__element-section[data-uuid="+uuid+"]").remove();
        list_modele_content = list_modele_content.filter((data)=>{
            return data.uuid != uuid;
        });
        $('.form-html-container .form-footer button.close').trigger('click');
        console.log('list_modele_content',list_modele_content);
        $("#elementor-preview-responsive-wrapper .preview-modal-list,#elementor-navigator__elements .elementor-navigator__elements").sortable( "refresh" );
    })
    // dupliquer model
    $(document).on('click','#elementor-preview-responsive-wrapper .preview-modal-list>section .elementor-editor-section-settings li.elementor-editor-element-add',function(e){
        e.preventDefault();
        let cloned_element = $(this).parents('section.elementor-element').clone();
        
        let new_uuid = main_function.createUniqueID();
        cloned_element.attr('data-uuid',new_uuid);
        let uuid = $(this).parents('section.elementor-element').attr('data-uuid');

        let cloned_element_2 = $(".elementor-navigator__elements>.elementor-navigator__element-section[data-uuid="+uuid+"]").clone();
        cloned_element_2.attr('data-uuid',new_uuid);
        let get_index = list_modele_content.findIndex((data)=>{
            return data.uuid == uuid;
        });
        let modele_post_dupliquer = list_modele_content.filter((data)=>{
            return data.uuid == uuid;
        });
        console.log('modele_post_dupliquer',modele_post_dupliquer,get_index);
        if(modele_post_dupliquer.length && get_index>-1){
            $('.form-html-container .form-footer button.close').trigger('click');
            let modele_post_dupliquer_clone = JSON.stringify(modele_post_dupliquer[0]);
            let modele_post_dupliquer_clone_new = JSON.parse(modele_post_dupliquer_clone);
            modele_post_dupliquer_clone_new.uuid = new_uuid;
            list_modele_content.splice(get_index+1, 0, modele_post_dupliquer_clone_new);
            cloned_element.insertAfter($(this).parents('section.elementor-element'));
            cloned_element_2.insertAfter($(".elementor-navigator__elements>.elementor-navigator__element-section[data-uuid="+uuid+"]"));
            $("#elementor-preview-responsive-wrapper .preview-modal-list").sortable( "refresh" );
        }
        console.log('list_modele_content',list_modele_content);
    })
    // modifier model
    $(document).on('click','#elementor-preview-responsive-wrapper .preview-modal-list>section .elementor-editor-section-settings li.elementor-editor-element-edit',function(e){
        e.preventDefault();
        id_model_current = parseInt($(this).parents('section.elementor-element').attr('data-id'));
        let uuid = $(this).parents('section.elementor-element').attr('data-uuid');
        current_uuid = uuid;
        let modele_data = list_modele_content.filter((data)=>{
            return data.uuid == uuid;
        });
        if(modele_data.length){
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
            main_function.ajaxOperation(base_url+'get-form/'+id_model_current,{data_set:JSON.stringify(modele_data[0].json_data)},'POST')
            .done((response)=>{
                if(response.success){
                    class_sortable_current  =  response.class_sortable;
                    $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-modals]").trigger('click');
                    $("#elementor-panel-elements-search-area").addClass('d-none');
                    $(".form-html-container").siblings().addClass('d-none');
                    $(".form-html-container .form").html(response.html_form).parent().addClass('show');
                    // create instance of ckeditor
                    if($(".form-html-container .form .post-text-riche").length){
                        $.each($(".form-html-container .form .post-text-riche"),function(ele){
                            let id = $(".form-html-container .form .post-text-riche").eq(ele).attr('id');
                            main_function.textareaToCkeidtor(id).then( editor => {
                                editor_var[id] = editor ;
                            } )
                            .catch( error => {
                                console.error( error );
                            } );
                        })
                    }
                    // set selectpicker
                    $('.selectpicker').select2();
                }
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            }).fail((error)=>{
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            })
        }
    })

    
    // set sartable list modéles
    $("#elementor-preview-responsive-wrapper .preview-modal-list,#elementor-navigator__elements .elementor-navigator__elements").sortable({
        change: function(event, ui) {
            //console.log('eee',list_modele_content);
        },
        update: function(event, ui) {
            // Check if the update event was triggered for list2
            if (this.id === 'list2') {
                // Get the order of elements in list2
                var order = $(this).sortable('toArray', { attribute: 'data-uuid' });

                // Reorder elements in list1 based on the order of list2
                var list1 = $("#elementor-preview-responsive-wrapper .preview-modal-list");
                var items = list1.children().get();
                items.sort(function(a, b) {
                    console.log('a',$(a).attr('data-uuid'));
                    console.log('b',b);
                    return order.indexOf($(a).attr('data-uuid')) - order.indexOf($(b).attr('data-uuid'));
                });
                $.each(items, function(idx, itm) { list1.append(itm); });
            }else{
                var order = $(this).sortable('toArray', { attribute: 'data-uuid' });

                // Reorder elements in list1 based on the order of list2
                var list1 = $("#elementor-navigator__elements .elementor-navigator__elements");
                var items = list1.children().get();
                items.sort(function(a, b) {
                    console.log('a',$(a).attr('data-uuid'));
                    console.log('b',b);
                    return order.indexOf($(a).attr('data-uuid')) - order.indexOf($(b).attr('data-uuid'));
                });
                $.each(items, function(idx, itm) { list1.append(itm); });
            }
            //console.log('teet',list_modele_content);
            let new_lis_modele = [];
            $.each($("#elementor-preview-responsive-wrapper .preview-modal-list>section"),function(index){
                let uuid = $("#elementor-preview-responsive-wrapper .preview-modal-list>section").eq(index).attr('data-uuid');
                let modele_data = list_modele_content.filter((data)=>{
                    return data.uuid == uuid;
                });
                if(modele_data){
                    new_lis_modele.push(modele_data[0]);
                }
            })

            list_modele_content = new_lis_modele;
            console.log('list_modele_content',list_modele_content);
        },
        handle:'.elementor-editor-element-move',
    }).disableSelection();

    // 
    $(document).on('click','#elementor-navigator__header__title',function(e){
        $(this).parents('aside').toggleClass('expand')
    })
    //  search in modele list
    $(document).on('keyup',"#elementor-panel-elements-search-input",function(e){
        let name = $(this).val()
        if(name.length){
            $.each($("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div"),function(index){
                let name_model = $("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div").eq(index).find('.title-wrapper .title').text();
                if(name_model.toLowerCase().indexOf(name.toLowerCase())===-1){
                    $("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div").eq(index).addClass('d-none');
                }else{
                    $("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div").eq(index).removeClass('d-none');
                }
            })
        }else{
            $.each($("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div"),function(index){
                $("#elementor-panel-elements-modals.elementor-panel-category.open .elementor-panel-category-items.post-meta-section>div").eq(index).removeClass('d-none');
            })
        }
    });

    // toggle aside
    $(document).on('click','#elementor-mode-switcher-preview-input',function(e){
        if($('body').hasClass('elementor-editor-preview')){
            $('body').removeClass('elementor-editor-preview');
        }else{
            $('body').addClass('elementor-editor-preview');
        }
    });

    // load history
    $(document).on('click','.elementor-panel .elementor-panel-footer-tool#elementor-panel-footer-history',function(e){
        e.preventDefault();
        if(id){
            $("#elementor-preview-loading,#elementor-preview-loading-side-bar").addClass('d-block').removeClass('d-none');
            main_function.ajaxOperation(base_url+'get-historique-post/'+id+'/1',{},'GET').done(function(response){
                console.log('response',response);
                $("#elementor-panel-elements-history").html(response.historique_html);
                $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-history]").addClass('elementor-active').removeClass('d-none').siblings().addClass('d-none');
                $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-history]").trigger('click');
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            }).fail((error)=>{
                $("#elementor-preview-loading,#elementor-preview-loading-side-bar").removeClass('d-block').addClass('d-none');
            })
        }
    });
    // show first tab
    $(document).on('click','#elementor-panel-header-add-button',function(e){
        $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-categories]").addClass('elementor-active').removeClass('d-none').siblings().removeClass('d-none');
        $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-history]").addClass('d-none')
        $("#elementor-panel-elements-navigation .elementor-panel-navigation-tab[data-tab=elementor-panel-elements-categories]").trigger('click');
        $("#elementor-panel-elements-history").html("");
    })
})