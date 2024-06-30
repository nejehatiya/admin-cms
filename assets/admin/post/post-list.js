// post list css
import './post-list.css';
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function(){
    let element_loading = $("table.widefat");
    let filter_post = null;
    let post_type = "";
    let page_count_all = parseInt($(".tablenav .total-pages").html());
    let id_current = null;
    if(typeof post_type_slug !== "undefined"){
        post_type = post_type_slug;
    }
    let current_page = 1;
    // search on text change
    $("#search-input").on('change',function(e){
        current_page = 1;
        $(".tablenav-pages .current-page").val(current_page);
        filterListPosts();
    })
    // search by status
    $(".post-status a").on('click',function(e){
        e.preventDefault();
        if(!$(this).hasClass('current')){
            $(this).addClass('current').parent().siblings().find('a').removeClass('current');
            current_page = 1;
            $(".tablenav-pages .current-page").val(current_page);
            filterListPosts();
        }
    });
    // search by advanced filter
    $(".filter-advanced-list select").on('change',function(e){
        e.preventDefault();
        current_page = 1;
        $(".tablenav-pages .current-page").val(current_page);
        filterListPosts();
    });
    // search on click #post-query-submit or #search-submit
    $("#search-submit,#post-query-submit").on('click',function(e){
        e.preventDefault();
        current_page = 1;
        $(".tablenav-pages .current-page").val(current_page);
        filterListPosts();
    })
    // start action groupé
    $("#cb-select-all-1,#cb-select-all-1-foot").on('change',function(e){
        let is_checked = $(this).prop('checked');
        if(is_checked){
            $("input[type=checkbox][id^='cb-select-']").prop('checked',true).attr('checked','checked');  
        }else{
            $("input[type=checkbox][id^='cb-select-']").prop('checked',false).removeAttr('checked');
        }
    })
    // start pagination +
    $(".tablenav .tablenav-pages .button.next-page").on('click',function(e){
        e.preventDefault();
        if((current_page+1)<=page_count_all){
            current_page = current_page + 1;
            $(".tablenav-pages .current-page").val(current_page);
            $("#current-page-footer").html(current_page);
            filterListPosts();
        } 
    })
    // start pagination -
    $(".tablenav .tablenav-pages .button.prev-page").on('click',function(e){
        e.preventDefault();
        if(current_page>=2){
            current_page = current_page - 1;
            $(".tablenav-pages .current-page").val(current_page);
            $("#current-page-footer").html(current_page);
            filterListPosts();
        } 
    })
    // go  to first page
    $(".tablenav .tablenav-pages .button.first-page").on('click',function(e){
        e.preventDefault();
        current_page = 1;
        $(".tablenav-pages .current-page").val(current_page);
        $("#current-page-footer").html(current_page);
        filterListPosts(); 
    })
    // go  to last page
    $(".tablenav .tablenav-pages .button.last-page").on('click',function(e){
        e.preventDefault();
        current_page = page_count_all;
        $(".tablenav-pages .current-page").val(current_page);
        $("#current-page-footer").html(current_page);
        filterListPosts(); 
    })
    // on change input pagiation
    $(".tablenav-pages .current-page").on('change',function(e){
        let page_number = parseInt($(this).val());
        if(page_number>=1 && page_number<=page_count_all){
            current_page = page_number;
        }else if(page_number<1){
            current_page = 1;
        }else{
            current_page = page_count_all;
        }
        $(".tablenav-pages .current-page").val(current_page);
        $("#current-page-footer").html(current_page);
        filterListPosts(); 
    });
    // on delete posts
    $(document).on('click','.submitdelete',function(e){
        e.preventDefault();
        id_current = parseInt($(this).parents('tr').attr('id').replace ( /[^\d.]/g, '' ));
        console.log('id_current',id_current);
        $(".popup-delete-confirm").addClass('show');
    });
    // close popup confirmation corbeil
    $(document).on('click','.popup-delete-confirm .content .footer button.annuler',function(e){
        e.preventDefault();
        $(".popup-delete-confirm").removeClass('show');
    });
    // click confirmation to mettre dans le corbeil
    $(document).on('click','.popup-delete-confirm .content .footer button.confirmer',function(e){
        e.preventDefault();
        element_loading.addClass('load');
        main_function.ajaxOperation('/api/'+post_type+'/change-status/'+id_current,{status:'Corbeille'},'POST').done((data)=>{
            filterListPosts();
        })
        $(".popup-delete-confirm").removeClass('show');
    });
    // confirmation la supression définitivemet l'article
    $(document).on('click','.submitdeletesuprime',function(e){
        e.preventDefault();
        id_current = parseInt($(this).parents('tr').attr('id').replace ( /[^\d.]/g, '' ));
        console.log('id_current',id_current);
        $(".popup-suprimer-confirm").addClass('show');
    });
    // click confirmation suppréssion définitivement
    $(document).on('click','.popup-suprimer-confirm .content .footer button.confirmer',function(e){
        e.preventDefault();
        element_loading.addClass('load');
        main_function.ajaxOperation('/api/'+post_type+'/delete/'+id_current,{},'GET').done((data)=>{
            filterListPosts();
        })
        $(".popup-confirm").removeClass('show');
    });
    // function filter post
    function filterListPosts(){
        element_loading.addClass('load');
        let search_text = $("#search-input").val();
        let current_status = $(".post-status a.current").find('input[type=hidden]').val();
        let collect_filter_list = [];
        let select_filters = $(".filter-advanced-list select");
        // reset action groupé checkbox
        $("input[type=checkbox][id^='cb-select-']").prop('checked',false).removeAttr('checked');
        $.each(select_filters,function(index){
            let field = select_filters.eq(index).attr('name');
            let val_search = select_filters.eq(index).val();
            if(val_search.length){
                collect_filter_list.push({field:field,val:val_search});
            }
        });
        if(filter_post!==null){
            filter_post.abort();
        }
        element_loading.addClass('load');
        main_function.ajaxOperation(
            '/api/filter-post/',
            {
                search_text:search_text,
                post_type:post_type,
                current_page:current_page,
                post_status:current_status,
                collect_filter_list:collect_filter_list,
            },
            'POST'
        ).done((data)=>{
            console.log('data',data)
            if(data.success){
                $("#the-list").html(data.body_html);
                $(".subsubsub .all .count").html('('+data.posts_count_tous+')');
                $(".subsubsub .publish .count").html('('+data.posts_count_publie+')');
                $(".subsubsub .corbeille .count").html('('+data.posts_count_corbeille+')');
                $(".subsubsub .brouillon .count").html('('+data.posts_count_brouillon+')');
                $(".tablenav .total-pages").html(data.page_count);
                page_count_all =  parseInt(data.page_count)
                $(".tablenav .displaying-num").html(data.page_count_staus_current+' elements');
            }
            element_loading.removeClass('load');
        }).fail((error)=>{
            console.error('error',error);
            element_loading.removeClass('load');
        })
    }
})