$(document).ready(function(){
    // open upload inline media
    $(".open-upload-inline").on('click',function(e){
        e.preventDefault();
        $(".uploader-inline").toggleClass('hidden');
    })
})