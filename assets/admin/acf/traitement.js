// require ajax call
import * as ajax_call from './ajax-call.js';
// require main function
import * as main_function from '../../js/main-functions.js';
let id = null;
if(typeof id_acf !== "undefined"){
    id = id_acf;
}
$(document).ready(function(){
    
    // check name modele
    $("#post_meta_fields_name").on('change',function(e){
        let name = $(this).val();
        if(name.length){
            ajax_call.checkByName(name,$(this));
        }
    });
    // create or update modeles
    $("p.submit input").on('click',function(e){
        e.preventDefault();
        let name = $("#post_meta_fields_name").val();
        let post_type = $("#post_meta_fields_post_type").val();

        console.log(post_type);
        let status = $("#post_meta_fields_status").prop('checked');
        let blocks = JSON.stringify(main_function.getBlocks());
        if(name.length ){ 
            ajax_call.insertEdit({name:name,post_type:JSON.stringify(post_type),status:status,blocks:blocks},id,$(this));
        }
    });

})