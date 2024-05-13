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
// html progress bar
export function htmlProgressUpload(){
    let html_progress_bar = '<li tabindex="0" role="checkbox" aria-checked="false" class="attachment uploading save-ready">';
            html_progress_bar += '<div class="attachment-preview js--select-attachment type- subtype- landscape">';
                html_progress_bar += '<div class="thumbnail">';
                    html_progress_bar += '<div class="media-progress-bar"><div style="width: 0%"></div></div>';
                html_progress_bar += '</div>';
            html_progress_bar += '</div>';
    html_progress_bar += '</li>';
    return html_progress_bar;
}
// html error html
export function htmlErrorUpload(type_error,file_name,message=""){
    let html = '<div class="upload-error">'
            html += '<span class="upload-error-filename">'+file_name+'</span>';
            if(type_error=="size"){
                html += '<span class="upload-error-message">'+file_name+' Dépasse la limite de téléversement de ce site.</span>';
            }else if(message.length){
                html += '<span class="upload-error-message">'+message+'</span>';
            }else{
                html += '<span class="upload-error-message">Désolé, vous n’avez pas l’autorisation de téléverser ce type de fichier.</span>';
            }
        html += '</div>';
    return html;
}

// set data form image for edit
export function  setDataForm(data){

}
// refresh list with search
export function  refreshListWithSearch(page){
    // set params serach 
    let serach_param = {};
    serach_param['date']=$(".upload-php #media-attachment-date-filters").val();
    serach_param['mime_type']=$(".upload-php #media-attachment-filters").val();
    serach_param['search']=$(".upload-php #media-search-input").val();
    $(".media-frame.mode-grid .media-toolbar.wp-filter").addClass('load');
    return ajaxOperation("/api/attachement/list/"+page,serach_param,'POST');
}
// Copier le texte
export function  copierTexte(text,ele){
    // On désactive l'action du formulaire
    // 1. Si le <textarea> n'est pas vide
    if (text.length) {
        // 2. On copie le texte dans le presse-papier
        navigator.clipboard.writeText(text).then(() => {
            // 4. On affiche l'alert
            ele.siblings('.success').removeClass('hidden');
            setTimeout(()=>{
                ele.siblings('.success').addClass('hidden');
            },1000)
        })
    }
}