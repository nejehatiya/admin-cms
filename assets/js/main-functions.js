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
export function ajaxOperation(url,data,method,is_same=true){
    if (ajax_operation != null && is_same) {
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

/***
 * convert text riche to ckeidtor
 */
export async function textareaToCkeidtor(id,data=""){
    return  ClassicEditor
    .create( document.querySelector( '#'+id ) );
}

/***
 * convert text riche to ckeidtor
 */
export function structureMenu(data_template){
    //console.log('testeee is pko');
    /**
     * structure menu item
     * {id:item_id,parent:item_parent,position:item_position,type:item_type,titre:titre_navigation}
     
     * let object = {
        id:item_id,
        parent:item_parent,
        position:item_position,
        type:item_type,
        titre:titre_navigation,
        'description':'',
        target_blanck:'',
        attr_title:'',
        css_class:'',
        id_class:'',
        template_parent:'',
        data_json:{},
        'child':[]
    };*/
    let structure_json = [];
    let eles_menu = $("#menu-to-edit").find('li');
    for(let i = 0;i<eles_menu.length;i++){
        //
        let ele = eles_menu.eq(i).find('.menu-item-settings.wp-clearfix');
        let item_id = ele.find(".menu-item-data-db-id").val();
        let description = ele.find(".edit-menu-item-description").val();
        let item_parent = ele.find(".menu-item-data-parent-id").val();
        let item_position = ele.find(".menu-item-data-position").val();
        let item_type = ele.find(".menu-item-data-type").val();
        let titre_navigation = ele.find(".edit-menu-item-title").val();
        let target_blanck = ele.find(".edit-menu-item-target").prop('checked');
        let no_follow = ele.find(".edit-menu-no-follow-attr").prop('checked');
        let attr_title = ele.find(".edit-menu-item-attr-title").val();
        let css_class = ele.find(".edit-menu-item-classes").val();
        let id_class = ele.find(".edit-menu-item-id").val();
        let template_parent = ele.find(".edit-menu-mega-template").val();
        let url =  ele.find(".edit-menu-item-url").val();
        //console.log('data_template',data_template);
        let data_jon = [];
        console.log('Object.keys(data_template)',Object.keys(data_template));
        console.log('data_template',data_template);
        if(Object.keys(data_template).indexOf(item_id)!==-1){
            /*let keys = Object.keys(data_template[item_id]);
            for(let index_key=0;keys.length<index_key;index_key++){
                
            }*/
            data_jon = data_template[item_id];
            console.log('data_jon',data_jon);
        }
        // object
        let object = {
            id:item_id,
            parent:item_parent,
            position:parseInt(item_position),
            type:item_type,
            titre:titre_navigation,
            description:description,
            target_blanck:target_blanck,
            attr_title:attr_title,
            css_class:css_class,
            id_class:id_class,
            template_parent:template_parent,
            data_json:data_jon,
            child:[],
            url:url,
            no_follow:no_follow,
        };
        console.log('object',object);
        // structure json
        structure_json.push(object);
    }
    console.log('structure_json',structure_json);
    // organiser json
    return organizeJSON(structure_json);
    
}
function organizeJSON(structure_json) {
    let map = {};
    let new_json = [];

    // Créer une map des éléments par ID pour un accès plus rapide
    structure_json.forEach(ele => {
        ele.child = [];
        map[ele.id] = ele;
    });

    // Organiser la structure hiérarchique
    structure_json.forEach(ele => {
        if (ele.parent == 0 || ele.parent == "0") {
            new_json.push(ele);
        } else if (map[ele.parent]) {
            map[ele.parent].child.push(ele);
        }
    });
    console.log('new_json',new_json);
    // new_json
    return new_json;
}
export function flattenJSON(json, parentIndex = "0", depth = 0, result = []) {
    json.forEach((item, index) => {
        const currentIndex = `${parentIndex}.${index}`;
        result.push({
            ...item,
            parentIndex: parentIndex === "0" ? "0" : parentIndex,
            currentIndex: currentIndex,
            depth: depth
        });

        if (item.child && item.child.length > 0) {
            flattenJSON(item.child, currentIndex, depth + 1, result);
        }
    });
    return result;
}
// colect data from fields list
export function collectData(fields_ele,editor_var){
    let data_json = [];
    if(fields_ele.length){
        for (let index = 0; index < fields_ele.length; index++) {
            let item_container = fields_ele.eq(index);
            let key_block =  item_container.find('.ref-block-hidden').val();
            let data_key_val = {};
            let inputs = item_container.find('input,textarea,select');
            $.each(inputs,function(ele){
                let var_name = inputs.eq(ele).attr('data-var-name');
                if(var_name !== "undefined" && var_name?.length){
                    let val = inputs.eq(ele).val();
                    if(inputs.eq(ele).is('textarea') && inputs.eq(ele).hasClass('post-text-riche')){
                        let id = inputs.eq(ele).attr('id');
                        if(Object.keys(editor_var).indexOf(id)!==-1){
                            val = editor_var[id]?.getData();
                            
                            data_key_val[var_name]  = val;
                        }else{
                            data_key_val[var_name]  = "";
                        }
                            
                    }else if(inputs.eq(ele).is('input') && inputs.eq(ele).attr('type')=="checkbox"){
                        if(inputs.eq(ele).prop('checked')){
                            if(Object.keys(data_key_val).indexOf(var_name) !== -1){
                                data_key_val[var_name] = data_key_val[var_name].concat(val);
                            }else{
                                data_key_val[var_name] = [val];
                            }
                        }
                    }else if(inputs.eq(ele).is('input') && inputs.eq(ele).attr('type')=="radio"){
                        if(inputs.eq(ele).prop('checked')){
                            data_key_val[var_name] = val;
                        }else if(Object.keys(data_key_val).indexOf(var_name) === -1){
                            data_key_val[var_name] = "";
                        }
                    }else if(inputs.eq(ele).is('input') && inputs.eq(ele).hasClass('lien-button-lien')){
                        if(Object.keys(data_key_val).indexOf(var_name) !== -1){
                            data_key_val[var_name] = Object.assign(data_key_val[var_name], {'lien':val});
                        }else{
                            data_key_val[var_name]={'lien':val};
                        }
                    }else if(inputs.eq(ele).is('input') && inputs.eq(ele).hasClass('titre-button-titre')){
                        if(Object.keys(data_key_val).indexOf(var_name) !== -1){
                            data_key_val[var_name] = Object.assign(data_key_val[var_name], {'titre':val});
                        }else{
                            data_key_val[var_name]={'titre':val};
                        }
                    }else{
                        data_key_val[var_name]  = val;
                    }
                }
            });
            data_key_val['ref_block']  = key_block;
            
            data_json.push(data_key_val);
        }
    }
    return data_json;
}
// delete delete deleteButton Html
export function deleteButtonHtml(){
    let html = "<button type='button' class='delete-block button button-primary'>";
            html += '<img src="http://localhost:8500/assets/images/delete-icon.png"  />';
    html += '</button>';
    return html;
}