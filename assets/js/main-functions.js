// init ajax operation
let ajax_operation = null;
// set blocks list items
let blocks = [];
// init allowed file to uplad
let allowed_extension = ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'mp4', 'svg'];
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
export function htmlProgressUpload(index){
    let html_progress_bar = '<li tabindex="0" role="checkbox" aria-checked="false" class="attachment uploading save-ready" data-index="'+index+'">';
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
    serach_param['search']=$(".upload-php #media-search-input,#__wp-uploader-id-2 #media-search-input").val();
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


// function to uload a file
export function uploadFileFormData(file,i,selector=""){
    let file_name =file.name;
    let file_extension = file_name.split('.')[file_name.split('.').length - 1].toLowerCase();
    let file_size = file.size;
    file_size = (file_size / (1024 * 1024)).toFixed(2);
    
    $(selector+".media-uploader-status").removeClass('d-block'); 
    if (file_size <= 40 && allowed_extension.indexOf(file_extension) != -1) {
        let html_progress_bar = htmlProgressUpload(i);
        $(selector+".media-frame.mode-grid .uploader-inline").addClass('load');  
        $(selector+".attachments-wrapper .attachments").prepend(html_progress_bar);
        let formData = new FormData();
        formData.append('file', file);
        $.ajax({
            url: apiPrefix()+"/api/attachement/upload-file",
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        $(selector+".attachment.uploading.save-ready").find('.media-progress-bar>div').css("width", percentComplete + "%");
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                $(selector+".attachment.uploading.save-ready").find('.media-progress-bar>div').css("width","0%");
            },
            success: function (res) {
                console.log(res);
                res = JSON.parse(res);
                if(res.success){
                    $(selector+".attachment.uploading.save-ready[data-index="+i+"]").replaceWith(res.preview_image);
                }else{
                    $(selector+".attachment.uploading.save-ready[data-index="+i+"]").remove();
                    $(selector+".media-uploader-status .upload-errors").prepend(htmlErrorUpload('extension','',res.message));
                    $(selector+".media-uploader-status").addClass('d-block');
                    $(selector+".media-sidebar").addClass('d-block');
                }
                $(selector+".media-frame.mode-grid .uploader-inline").removeClass('load');
            }, error: function (error) {
                console.error(error);
                $(selector+".attachment.uploading.save-ready[data-index="+i+"]").remove();
                $(selector+".media-uploader-status .upload-errors").prepend(htmlErrorUpload('extension','',error.message));
                $(selector+".media-frame.mode-grid .uploader-inline").removeClass('load');
                $(selector+".media-uploader-status").addClass('d-block');
                $(selector+".media-sidebar").addClass('d-block');
            }
        });
    }else{
        if(file_size > 40){
            $(selector+".media-uploader-status .upload-errors").prepend(htmlErrorUpload('size',file_name));
        }
        if(allowed_extension.indexOf(file_extension) === -1){
            $(selector+".media-uploader-status .upload-errors").prepend(htmlErrorUpload('extension',file_name));
        }
        $(selector+".media-uploader-status").addClass('d-block');
        $(selector+".media-sidebar").addClass('d-block');
        $(selector+".media-frame.mode-grid .uploader-inline").removeClass('load');
    }
}