//import "../../css/sections/blog-list.css";
//import "../../css/sections/auteur.css";
//import LazyLoad from "vanilla-lazyload";
$(document).ready(function() {
    let current_page = 1 ;
    let is_loading = false;
    // Get the <div> element
    let  divElement = $('.section-blogs .list .item:last-of-type');
    // get posttype slug
    let post_type_slug = $("input.post_type").val();
    $("input.post_type").remove();
    // Function to check if the end of the <div> is reached
    function isEndOfDivVisible() {
        let windowHeight = $(window).height();
        let scrollPosition = $(window).scrollTop();
        let divBottom = divElement.offset().top + divElement.height();
        // Check if the bottom of the div is within the viewport
         return divBottom <= scrollPosition + windowHeight && divBottom + 150 >= scrollPosition + windowHeight;
    }
  
    // Event listener for scroll
    $(window).scroll(function() {
      if (isEndOfDivVisible()) {
        // If the end of the <div> is reached
        //console.log('End of div is visible');
        // Perform actions or load more content here
        if(!is_loading){
            is_loading = true;
            ajaxLoadMoreList();
        }
      }
    });


    function ajaxLoadMoreList(){
        if(is_loading){
            $(".section-blogs .loading-load").addClass('show');
            current_page = current_page+1;
            $.ajax({
                url:'/api/load-more-blog',
                method:'Post',
                data:{
                    current_page:current_page,
                    post_type_slug:post_type_slug,
                },
                success:function(res){
                    //console.log('res',res);
                    if(res.success){
                        $('.section-blogs .list').append(res.message);
                        divElement = $('.section-blogs .list .item:last-of-type');
                        is_loading = false;
                        let myLazyLoad = new LazyLoad();
                        myLazyLoad.update();
                    }
                    $(".section-blogs .loading-load").removeClass('show');
                },
                error:function(err){
                    //console.log('err',err);
                    is_loading = true;
                    $(".section-blogs .loading-load").removeClass('show');
                }
            })
        }

    }
});