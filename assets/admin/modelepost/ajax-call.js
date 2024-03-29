// require main function
import * as main_function from '../../js/main-functions.js';
/***
 * function to check modele post by name
 */
export function checkModelePostByName(name="",ele){
    // start loadig
    ele.siblings('.lds-ellipsis').addClass('show');
    let url = "/api/post/modals/check-name";
    let check_modele_post = main_function.ajaxOperation(url,{name:name,id:null},'POST');
    check_modele_post.done(function(data) {
        if(data.success){
            ele.addClass('success').removeClass('error');
        }else{
            ele.addClass('error').removeClass('success');
        }
        // end loadig
        ele.siblings('.lds-ellipsis').removeClass('show');
    });
}
/***
 * function to insert|edit modele post
 */
export function insertEditModelePost(data,id,ele){
    // start loadig
    ele.siblings('.lds-ellipsis').addClass('show');
    let url = id?"/api/post/modals/edit/"+id:"/api/post/modals/new";
    let check_modele_post = main_function.ajaxOperation(url,data,'POST');
    check_modele_post.done(function(response) {
        console.log('data',response)
        // end loadig
        ele.siblings('.lds-ellipsis').removeClass('show');
    });
}
/***
 * function to delete modele post
 */
export function deleteModelePost(data){
    
}
/***
 * function to insert|edit modele post
 */
export function getModelePost(id){
    let url = "/api/post/modals/get/"+id;
    let check_modele_post = main_function.ajaxOperation(url,{},'GET');
    check_modele_post.done(function(response) {
        console.log('data',response)
        if(response?.model_post){
            main_function.setBlocks(JSON.parse(response.model_post.fields));
        }
    });
}