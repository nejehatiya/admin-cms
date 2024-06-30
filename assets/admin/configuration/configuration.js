import './configuration.css';
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(e){
    // init editors for post meta
    let editor_var_meta = [];
    let url = '/api/configuration/';
    if(typeof is_options !== "undefined"){
        url = '/api/options/';
    }
    console.log('$(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche").length',$(".elementor-panel-category .elementor-panel-category-items.post-meta-section").find(".post-text-riche").length)
    if($(".modal.template-menu .model-content .forms-generate").find(".post-text-riche").length){
        let instances_editors_create = $(".modal.template-menu .model-content .forms-generate").find(".post-text-riche");
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
    // get data from form
    $(document).on('click','.modal.template-menu .model-content .forms-generate input[type=submit]',function(e){
        e.preventDefault();
        let parent_form_container = $(this).parents('.modal.template-menu .model-content .forms-generate');

        let blocks_data_element =  parent_form_container.find('>fieldset');
        // start collect data
        let data_config =  main_function.collectData(blocks_data_element,editor_var_meta);
        main_function.ajaxOperation(url,{config_data:JSON.stringify(data_config)},'POST').done((data)=>{

        }).fail((error)=>{

        })
    });
})