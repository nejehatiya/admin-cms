// main function
import * as main_function from './main-functions.js';

$(document).ready(function(){
    // init structure fields [{refblock:,name:,is_duplicatable:,fields:[{type:,titre:,options:,required:,uuid:}]}]
    let blocks = [];
    // init file type list
    let list_file_types = ["file","textarea","number","text","date","text riche","select","checkbox","radio"];
    // init html block
    let html_block = "";
    // start add blocks
    $("#btn-blocks").on('click',function(e){
        // prevent default fonctionalite
        e.preventDefault();
        // reset html
        html_block = "";
        // get name block
        let name_block = $(".name-blocks").val();
        if(name_block.length){
            // create ref block
            let ref_block = main_function.slugify(name_block)+main_function.createUniqueID();
            // ajouter les block
            let is_duplicated_html = '<div class="form-check duplicated-option"><input class="duplicate-check-input" type="checkbox" value="" id="duplicated-option-'+ref_block+'"><label class="form-check-label" for="duplicated-option-'+ref_block+'">duplicated</label></div>';
            html_block += "<fieldset class='"+ref_block+" card' >";
                html_block += "<legend>"+name_block+is_duplicated_html+" <button type='button' class='delete-block btn btn-danger' id='"+ref_block+"'>X</button></legend>";
                html_block += "<div class='fields-select'><select class='field-list custom-select'>";
                    html_block += "<option value=''>champs list</option>";  
                    for(let item of list_file_types){
                        html_block += "<option value='"+item+"'>"+item+"</option>";  
                    }
                html_block += "</select>";
                html_block += "<button type='button' class='add-field btn btn-info' id='fields-"+ref_block+"'>+</button></div>";
                html_block += "<div class='fields-"+ref_block+"'></div>";
            html_block += "</fieldset>";
            // remplire les block
            $(".blocks-container").append(html_block);
            $(".name-blocks").val("");
            // push element to list
            blocks.push({refblock:ref_block,name:name_block,is_duplicatable:false,fields:[]});
            // log blocks
            console.log('blocks 39',blocks);
        }
    });
    // update duplicate option block
    $(document).on('change',".duplicate-check-input",function(e){
        e.preventDefault();
        let ref_block = $(this).attr('id').replace('duplicated-option-','');
        // update block duplicate options
        let check_block_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_block_index>-1){
            // update duplicate option
            blocks[check_block_index].is_duplicatable = $(this).prop('checked');
        }
        // log blocks
        console.log('blocks 55',blocks);
    })
    // delete block 
    $(document).on('click',".delete-block",function(e){
        e.preventDefault();
        let ref_block = $(this).attr('id');
        $(this).parents("."+ref_block).remove();
        // remove from field lits
        blocks = blocks.filter((ele)=>{
            return ele.refblock != ref_block;
        })
        // log blocks
        console.log('blocks 67',blocks);
    })
    // add new field
    $(document).on('click','.add-field',function(e){
        e.preventDefault();
        // init field type and class div
        let field_type = $(this).parent().find('select.field-list').val();
        if(field_type.length){
            // create unique uuid
            let uuid = main_function.createUniqueID();
            let class_div = $(this).attr('id');
            // parse ref block
            let ref_block = class_div.replace('fields-','');
            // create html fieald blcok
            let required_html = '<div class="form-check"><input class="required-check-input" type="checkbox" value="" id="required-option-'+uuid+'"><label class="form-check-label" for="required-option-'+uuid+'">required</label></div>';
            let html_field = "";
            html_field += "<div class='filed card p-2 mt-3 mb-3' style='background:#eee'>";
                html_field += "<input data-refblock='"+ref_block+"' value='"+uuid+"' type='hidden' class='uuid-field' />";
                html_field += "<button type='button' class='delete-field btn btn-danger'>X</button>";
                html_field += "<p>field type :<strong>"+field_type+"</strong></p>";
                html_field += "<p><label>titre:</label><input type='text' class='form-control titre-field' placeholder='titre' /></p>";
                if(["select","checkbox","radio"].indexOf(field_type)!==-1){
                    // add option 
                    html_field += "<p><label>List options(séparer par | ):</label><textarea class='options-list'></textarea></p>";
                }
                html_field += required_html;
            html_field += "</div>";
            // add field to list fiels
            $("."+class_div).append(html_field);
            // add field to list 
            let check_field_index = blocks.findIndex((ele)=>{
                return ele.refblock == ref_block;
            });
            if(check_field_index>-1){
                blocks[check_field_index].fields.push({type:field_type,titre:'',options:'',required:false,uuid:uuid})
            }
            // log blocks
            console.log('blocks 103',blocks);
        }
    })
    // update required field
    $(document).on('change',"input.required-check-input",function(e){
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).parent('.form-check').siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).parent('.form-check').siblings('.uuid-field').attr('data-refblock');
        // get field titre
        let checked_prop = $(this).prop('checked');
        // update field in list
        let check_block_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_block_index>-1){
            let fields_block = blocks[check_block_index].fields;
            let field_index = fields_block.findIndex((ele)=>{
                return ele.uuid == uuid_field;
            })
            fields_block[field_index].required = checked_prop;
            // update field list
            blocks[check_block_index].fields = fields_block;
        }
        // log blocks
        console.log('blocks 128',blocks);
    })
    // update tittre field
    $(document).on('keyup',"input.titre-field",function(e){
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).parent().siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).parent().siblings('.uuid-field').attr('data-refblock');
        // get field titre
        let titre = $(this).val();
        console.log('titre 139',titre)
        // update field in list
        let check_block_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_block_index>-1){
            let fields_block = blocks[check_block_index].fields;
            let field_index = fields_block.findIndex((ele)=>{
                return ele.uuid == uuid_field;
            })
            fields_block[field_index].titre = titre;
            // update field list
            blocks[check_block_index].fields = fields_block;
            console.log('titre 152',fields_block)
        }
        // log blocks
        console.log('blocks 152',blocks);
    })
    // update options list
    $(document).on('keyup',"textarea.options-list",function(e){
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).parent().siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).parent().siblings('.uuid-field').attr('data-refblock');
        // get field option
        let option_list = $(this).val();
        // convert text to array
        option_list = option_list.split('|');
        // remove empty value from list
        option_list = option_list.filter((ele)=>{
            return ele != null && ele.length;
        });
        // remove  space from after and before string
        option_list = option_list.map(function (el) {
            return el.trim();
        });
        // update field in list
        let check_block_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_block_index>-1){
            let fields_block = blocks[check_block_index].fields;
            let field_index = fields_block.findIndex((ele)=>{
                return ele.uuid == uuid_field;
            })
            fields_block[field_index].options = option_list;
            // update field list
            blocks[check_block_index].fields = fields_block;
        }
        // log blocks
        console.log('blocks 184',blocks);
    })
    // delete field
    $(document).on('click',".delete-field",function(e){
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).siblings('.uuid-field').attr('data-refblock');
        // delete field html
        $(this).parent(".filed").remove();
        // delete field from list
        let check_field_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_field_index>-1){
            let fields_block = blocks[check_field_index].fields;
            fields_block = fields_block.filter((ele)=>{
                return ele.uuid != uuid_field;
            })
            // update field list
            blocks[check_field_index].fields = fields_block;
        }
        // log blocks
        console.log('blocks 208',blocks);
    })
})