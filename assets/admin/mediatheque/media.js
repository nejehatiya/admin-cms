import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let prefix_admin  = main_function.apiPrefix();
    //
    let allowed_extension = ['jpg', 'jpeg', 'gif', 'png', 'pdf', 'mp4', 'svg'];
    //
    let html_progress_bar = '<li tabindex="0" role="checkbox" aria-checked="false" class="attachment uploading save-ready">';
            html_progress_bar += '<div class="attachment-preview js--select-attachment type- subtype- landscape">';
                html_progress_bar += '<div class="thumbnail">';
                    html_progress_bar += '<div class="media-progress-bar"><div style="width: 0%"></div></div>';
                html_progress_bar += '</div>';
            html_progress_bar += '</div>';
    html_progress_bar += '</li>';
    // open upload inline media
    $(".open-upload-inline,.uploader-inline button.close").on('click',function(e){
        e.preventDefault();
        $(".uploader-inline").toggleClass('hidden');
    })
    //open file to upload
    $(".button-open-file").on('click',function(e){
        e.preventDefault();
        $(this).siblings('input[type=file]').trigger('click');
    });

    // start upload file
    $(document).on('change',".upload-image-input",function(e){
        let file = $(this).prop('files')[0];
        let file_name =file.name;
        let file_extension = file_name.split('.')[file_name.split('.').length - 1].toLowerCase();
        let file_size = file.size;
        file_size = (file_size / (1024 * 1024)).toFixed(2);
        if (file_size <= 40 && allowed_extension.indexOf(file_extension) != -1) {    
            $(".attachments-wrapper .attachments").prepend(html_progress_bar);
            let formData = new FormData();
            formData.append('file', file);
            $.ajax({
                url: prefix_admin+"/api/attachement/upload-file",
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
                            $(".attachment.uploading.save-ready").find('.media-progress-bar>div').css("width", percentComplete + "%");
                        }
                    }, false);
                    return xhr;
                },
                beforeSend: function () {
                    $(".attachment.uploading.save-ready").find('.media-progress-bar>div').css("width","0%");
                },
                success: function (res) {
                    console.log(res);
                    
                }, error: function (error) {
                    console.error(error);
                }
            });
        }else{
            if(file_size > 40){

            }

            if(allowed_extension.indexOf(file_extension) === -1){
                
            }
        }
    })
})