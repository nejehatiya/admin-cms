//import "../../css/global/global.css";
//import "../../css/global/header.css";
$(window).on("load", function () {
  /**
   * add attribut id "list-" in li
   */
  $(".zone_intervention ul li").each(function () {
    let text = $(this).children().text();
    let text_id = text.split(" ");
    let id = text_id[text_id.length - 1];
    ////console.log(text_id.length, text_id, id);
    $(this).attr("id", "list-" + id);
  });
  /**
   *if hover path (maps) so li hover "font-weight":"bold"???
   */
  $(".img_svg .allPaths").on("mouseenter mouseleave", function (e) {
    let text_id = e.target.id;
    let id = text_id.split("-");
    let hover_path = $("#list-" + id[1] + ">a");
    let font = "#list-" + id[1];
    var href = $("#list-" + id[1] + ">a").attr("href");
    ////console.log(href);
    // //console.log(href);
    $(this).on("click", function (e) {
      window.location.href = href;
    });
    if (e.type === "mouseenter") {
      hover_path.css({ color: "#EE2436", "font-weight": "bold " });
    } else {
      hover_path.css({ color: "", "font-weight": "" });
    }
  });
  /**
   * if hover li so hover  path (maps)
   */
  $(".children-section ul li a").on("mouseenter mouseleave", function (e) {
    ////console.log('parent', $(this).parent());
    let text = $(this).text();
    let text_id = text.split(" ");
    let id = parseInt(text_id[text_id.length - 1]);
    if (id) {
      let hover_department = $("#FR-" + id);
      hover_department.css("fill", e.type === "mouseenter" ? "#EE2436" : "");
    }
  });
  /**
   * if div maps vivible so  parent hover
   */
  $("#menu-menu_1 li.has-children").on("mouseenter mouseleave", function (e) {
    var dim = $(".zone_intervention").is(":visible");
    ////console.log($(this).children().eq(0));
    if (
      dim == true &&
      $(this).children()[0].innerText == "Zone D'intervention"
    ) {
      $(this).children().eq(0).css("color", "#EE2436 !important");
      //$(":after").css("filter","invert(13%) sepia(94%) saturate(7466%) hue-rotate(0deg) brightness(94%) contrast(115%)");
    } else {
      $(this).children().eq(0).css("color", "#172746 !important");
    }
  });

  $(".menu-mobile").on("click", function (e) {
    $(".menu-mobile").addClass("open");
  });
  $(document).on("click", ".menu-backdrop", function (e) {
    $(".menu-mobile").removeClass("open");
  });
  $(window).on("scroll", function (e) {
    let window_scroll_top = window.pageYOffset;
    if (window_scroll_top > 200) {
      $(".main-header").addClass("fixed");
    } else {
      $(".main-header").removeClass("fixed");
    }
  });
});