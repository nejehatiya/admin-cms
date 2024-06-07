// require ajax call
import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
let id = null;
if(typeof id_modele !== "undefined"){
    id = id_modele;
}
$(document).ready(function(){
    
    // check name modele
    $("#modeles_post_name_modele").on('change',function(e){
        let name = $(this).val();
        if(name.length){
            ajax_call.checkModelePostByName(name,$(this));
        }
    });
    // create or update modeles
    $("p.submit input").on('click',function(e){
        e.preventDefault();
        let name = $("#modeles_post_name_modele").val();
        let class_sortable = $("#modeles_post_class_sortable").val();
        let post_type = $("#modeles_post_used_in").val();
        let media_selected = $(".media-selected.image-preview").val();

        console.log(post_type);
        let status = $("#modeles_post_status_modele").prop('checked');
        let is_new = $("#modeles_post_is_new").prop('checked');
        
        let blocks = JSON.stringify(main_function.getBlocks());
        if(name.length && class_sortable.length  ){
            $(this).parent().addClass('load');
            main_function.ajaxOperation('/api/post/modals/forms-post-get',{fields:blocks},'POST').done((response)=>{
                if(response.success){
                    $(".form-preview").html(response.html_form);
                    let blocks_data_element = $(".form-preview").find('>fieldset');
                    let data_form = main_function.collectData(blocks_data_element,{});
                    data_form.push({"css_class":"erferf","id_css":"ferferf","css_bg":"","ref_block":"block-ref-style-advanced"});
                    data_form = JSON.stringify(data_form);
                    ajax_call.insertEditModelePost({name:name,post_type:JSON.stringify(post_type),is_new:is_new,status:status,blocks:blocks,image:media_selected,class_sortable:class_sortable,data_form:data_form},id,$(this));
                }
            })
            
        }
    });

})