// require main function
import * as main_function from '../../js/main-functions.js';
// require main function
import * as mainadmin_post_modals from '../../js/admin-post-modals.js';
/***
 * function to check modele post by name
 */
export function checkByName(name="",ele){
    // start loadig
    ele.siblings('.lds-ellipsis').addClass('show');
    let url = "/api/acf-meta/check-name";
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
export function insertEdit(data,id,ele){
    // start loadig
    ele.parent().addClass('load');
    let url = id?"/api/acf-meta/edit/"+id:"/api/acf-meta/new";
    let check_modele_post = main_function.ajaxOperation(url,data,'POST');
    check_modele_post.done(function(response) {
        console.log('data',response)
        // end loadig
        ele.parent().removeClass('load');
    });
}
/***
 * function to delete modele post
 */
export function deleteAcf(data){
    
}
/***
 * function to insert|edit modele post
 */
export function getDataAcf(id){
    let url = "/api/acf-meta/get/"+id;
    let check_modele_post = main_function.ajaxOperation(url,{},'GET');
    return check_modele_post;
}