$(document).ready(function(){
    // init file type list
    let list_file_types = ["","file","textarea","number","text","date","text riche","select","checkbox","radio"];
    // init html block
    let html_block = "";
    // start add blocks
    $("#btn-blocks").on('click',function(e){
        // prevent default fonctionalite
        e.preventDefault();
        // reset html
        html_block = "";
        // get number block
        let nombre_block = parseInt($(".nombre-blocks").val());
        // ajouter les block
        for(let i = 0;i<nombre_block;i++){
            html_block += "<fieldset class='block"+i+"'>";
                html_block += "<legend>Block "+(i+1)+"</legend>";
                html_block += "<select class='field-list'>";
                    for(let item of list_file_types){
                        html_block += "<option value='"+item+"'>"+item+"</option>";  
                    }
                html_block += "</select>";
                html_block += "<button class='add-field' id='fields-"+i+"'>+</button>";
                html_block += "<div class='fields-"+i+"'></div>";
            html_block += "</fieldset>";
        }
        console.log('html_block',html_block,nombre_block);
        // remplire les block
        $(".blocks-container").html(html_block);
    });
    // add new field
    $(document).on('click','.add-field',function(e){
        e.preventDefault();
        let field_type = $(this).parent('fieldset').find('select.field-list').val();
        let class_div = $(this).attr('id');
        let html_field = "";
        html_field += "<div class='filed'>";
            html_field += "<p>field:"+field_type+"</p>";
            html_field += "<p><label>titre:</label><input type='text' placeholder='titre' /></p>";
            if(["select","checkbox","radio"].indexOf(field_type)!==-1){
                // add option 
                html_field += "<p><label>List options(séparer par | ):</label><textarea></textarea></p>";
            }
        html_field += "</div>";
        // add field to list fiels
        $("."+class_div).append(html_field);
    })
})