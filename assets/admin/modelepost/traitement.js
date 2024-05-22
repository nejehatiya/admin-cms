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
        let post_type = $("#modeles_post_used_in").val();
        let media_selected = $(".media-selected.image-preview").val();

        console.log(post_type);
        let status = $("#modeles_post_status_modele").prop('checked');
        let blocks = JSON.stringify(main_function.getBlocks());
        if(name.length ){ 
            ajax_call.insertEditModelePost({name:name,post_type:post_type,status:status,blocks:blocks,image:media_selected},id,$(this));
        }
    });

})