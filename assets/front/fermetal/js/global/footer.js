
//import "../../css/global/footer.css";
$(document).ready(function (e) {
    let active_link = $('#menu-menu_1 a.active');
    if (active_link.length) {
        active_link.each(function (index) {
            active_link.eq(index).parents('li').find('>a').addClass('active');
        });
    }
})