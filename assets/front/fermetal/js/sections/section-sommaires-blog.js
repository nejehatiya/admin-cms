//import "../../css/sections/section-sommaires-blog.css";

$(document).ready(function($){
    //stickyPosition();
    // update on window resize
    $(window).on('resize',function(e){
        //stickyPosition($(window).scrollTop());
    });
    $(window).on('scroll',function(){
        //stickyPosition($(window).scrollTop());
    });
    /** detect blog sidbar position on scroll */
    function stickyPosition(scroll){
        let height = $(".content-sommaire .sidebar>div").height();
        let height_window = $(window).height();
        if($("#style-specific").length){
            if(!scroll){
                $('#style-specific').html(".content-sommaire .sidebar>div{top: 0 }");
            }else{
                $('#style-specific').html(".content-sommaire .sidebar>div{top: calc( ( ( "+height_window+"px  - "+height+"px ) / 2 ) + 33.5px  )}");
            }
        }else{
            if(!scroll){
                $("body").append("<style id='style-specific'>.content-sommaire .sidebar>div{top: 0px;}</style>");
            }else{
                $("body").append("<style id='style-specific'>.content-sommaire .sidebar>div{top: calc( ( ( "+height_window+"  - "+height+"px ) / 2 ) + 33.5px;}</style>");
            }
            
        }
    }
})