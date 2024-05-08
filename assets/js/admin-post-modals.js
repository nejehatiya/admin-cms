// main function
import * as main_function from './main-functions.js';
import * as ajax_call from '../admin/modelepost/ajax-call.js';
let id = null;
if(typeof id_modele !== "undefined"){
    id = id_modele;
}
//console.log('fields',JSON.parse(fields));
$(document).ready(function(){
    let blocks = [];
    // if us edt 
    if(id){
        ajax_call.getModelePost(id).done(function(response) {
            console.log('data',response)
            if(response?.model_post){
                main_function.setBlocks(JSON.parse(response.model_post.fields));
                blocks = JSON.parse(response.model_post.fields) ;
            }
        });;
    }
    // init structure fields [{refblock:,name:,is_duplicatable:,fields:[{type:,titre:,options:,required:,uuid:,old_var_name:',is_old:}]}]
    //blocks = main_function.getBlocks();
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
                html_block += "<input value='"+ref_block+"' type='hidden' class='ref-block-hidden' />"; 
                html_block += "<legend>"+name_block+is_duplicated_html+" <button type='button' class='delete-block btn btn-danger' id='"+ref_block+"'>X</button></legend>";
                html_block += "<div class='fields-select'><select class='field-list custom-select'>";
                    html_block += "<option value=''>champs list</option>";  
                    for(let item of list_file_types){
                        html_block += "<option value='"+item+"'>"+item+"</option>";  
                    }
                html_block += "</select>";
                html_block += "<button type='button' class='add-field btn btn-info' id='fields-"+ref_block+"'>+</button></div>";
                html_block += "<div class='fields-"+ref_block+" fields-element'></div>";
            html_block += "</fieldset>";
            // remplire les block
            $(".blocks-container").append(html_block);
            $(".name-blocks").val("");
            // push element to list
            blocks.push({refblock:ref_block,name:name_block,is_duplicatable:false,fields:[]});
            // log blocks
            console.log('blocks 39',blocks);
            // update sortable list
            $( "#blocks-container" ).sortable( "refresh" );
            // update blocks
            main_function.setBlocks(blocks);
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
        // update blocks
        main_function.setBlocks(blocks);
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
        // update sortable list
        $( "#blocks-container" ).sortable( "refresh" );
        // update blocks
        main_function.setBlocks(blocks);
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
            let old_html = '<div class="form-check"><input class="old-check-input" type="checkbox" value="" id="old-option-'+uuid+'"><label class="form-check-label" for="old-option-'+uuid+'">old</label><div class="old d-none"><input type="text" class="form-control old-var-name" placeholder="old var name" /></div></div>';
            let multiple_html = '<div class="form-check"><input class="multiple-check-input" type="checkbox" value="" id="multiple-option-'+uuid+'"><label class="form-check-label" for="multiple-option-'+uuid+'">multiple</label></div>';
            let html_field = "";
            html_field += "<div class='filed card p-2 mt-3 mb-3' style='background:#eee'>";
                html_field += "<input data-refblock='"+ref_block+"' value='"+uuid+"' type='hidden' class='uuid-field' />";
                html_field += "<button type='button' class='delete-field btn btn-danger'>X</button>";
                html_field += "<p class='titre'>field type : <strong>"+field_type+"</strong></p>";
                html_field += "<p><label>titre:</label><input type='text' class='form-control titre-field' placeholder='titre' /></p>";
                if(["select","checkbox","radio"].indexOf(field_type)!==-1){
                    // add option 
                    html_field += "<p><label>List options(séparer par | ):</label><textarea class='options-list'></textarea></p>";
                }
                // is multiple 
                if(["select"].indexOf(field_type)!==-1){
                    html_field += multiple_html;
                }
                // is required
                html_field += required_html;
                // is old vars
                html_field += old_html;
            html_field += "</div>";
            // add field to list fiels
            $("."+class_div).append(html_field);
            // add field to list 
            let check_field_index = blocks.findIndex((ele)=>{
                return ele.refblock == ref_block;
            });
            if(check_field_index>-1){
                blocks[check_field_index].fields.push({type:field_type,titre:'',options:'',required:false,uuid:uuid,old_var_name:'',is_old:false})
            }
            // log blocks
            console.log('blocks 103',blocks);
            // update blocks
            main_function.setBlocks(blocks);
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
        // update blocks
        main_function.setBlocks(blocks);
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
        // update blocks
        main_function.setBlocks(blocks);
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
        // update blocks
        main_function.setBlocks(blocks);
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
        // update blocks
        main_function.setBlocks(blocks);
    });
    /** init block is ordererd */
    $( "#blocks-container" ).sortable();
    // update list blocks on sort change
    $( "#blocks-container" ).on( "sortupdate", function( event, ui ) {
        let elements = $("#blocks-container").find('>fieldset');
        let new_blocks = [];
        for(let index = 0;index<elements.length;index++){
            let ref_block = elements.eq(index).find('input.ref-block-hidden').val();
            console.log('ref_block',elements.eq(index).find('input.ref-block-hidden'),ref_block)
            let check_block = blocks.filter((ele)=>{
                return ele.refblock == ref_block;
            });
            if(check_block.length){
                new_blocks.push(check_block[0]); 
            }
        }
        // update finaly blocks list
        blocks = new_blocks;
        console.log('blocks',blocks)
        // update blocks
        main_function.setBlocks(blocks);
    });
    /***
     * add old variable to field
     */
    $(document).on('change','input.old-check-input',function(e){
        let is_checked = $(this).prop('checked');
        if(is_checked){
            $(this).siblings('div.old').removeClass('d-none');
        }else{
            $(this).siblings('div.old').addClass('d-none');
        }
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).parents('.form-check').siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).parents('.form-check').siblings('.uuid-field').attr('data-refblock');
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
            fields_block[field_index].is_old = checked_prop;
            // update field list
            blocks[check_block_index].fields = fields_block;
        }
        // log blocks
        console.log('blocks 128',blocks);
        // update blocks
        main_function.setBlocks(blocks);
    })

    /***
     * add old variable to field
     */
    $(document).on('keyup','input.old-var-name',function(e){
        let val = $(this).val();
        e.preventDefault();
        // get uuid field
        let uuid_field = $(this).parents('.form-check').siblings('.uuid-field').val();
        // get ref block  parent
        let ref_block = $(this).parents('.form-check').siblings('.uuid-field').attr('data-refblock');
        // update field in list
        let check_block_index = blocks.findIndex((ele)=>{
            return ele.refblock == ref_block;
        });
        if(check_block_index>-1){
            let fields_block = blocks[check_block_index].fields;
            let field_index = fields_block.findIndex((ele)=>{
                return ele.uuid == uuid_field;
            })
            fields_block[field_index].old_var_name = val;
            // update field list
            blocks[check_block_index].fields = fields_block;
        }
        // log blocks
        console.log('blocks 128',blocks);
        // update blocks
        main_function.setBlocks(blocks);
    })
})