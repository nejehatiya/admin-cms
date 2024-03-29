// init ajax operation
let ajax_operation = null;
// set blocks list items
let blocks = [];
// get blocks function
export function getBlocks(){
    return blocks;
}
// set bloks items
export function setBlocks(list){
    blocks = list;
}
// get api prefix value
let api_prefix = document.getElementById('prefix-admin')?.value;
//document.getElementById('prefix-admin')?.remove();
export function apiPrefix() {
    return '/'+api_prefix;
}
/**
 * slugify words
 */
export function slugify(str) {
    return String(str)
    .normalize('NFKD') // split accented characters into their base characters and diacritical marks
    .replace(/[\u0300-\u036f]/g, '') // remove all the accents, which happen to be all in the \u03xx UNICODE block.
    .trim() // trim leading or trailing whitespace
    .toLowerCase() // convert to lowercase
    .replace(/[^a-z0-9 -]/g, '') // remove non-alphanumeric characters
    .replace(/\s+/g, '-') // replace spaces with hyphens
    .replace(/-+/g, '-'); // remove consecutive hyphens
}
/**
 * create unique uuid
 */
export function createUniqueID(){
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
}
/**
 * function ajax
 * @param [data,action,url]
 */
export function ajaxOperation(url,data,method){
    if (ajax_operation != null) {
        ajax_operation.abort();
    }
    // start ajax call
    ajax_operation = $.ajax({
        type: method,
        dataType: 'JSON',
        url: apiPrefix()+url,
        data: data,
    });
    // return operation 
    return ajax_operation;
}