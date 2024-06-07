//import '../../css/modeles/content-element.css';
$(document).ready(function () {
    $('.lire-suite-text').on('click', function (e) {
        e.preventDefault();
        if ($('.overflow-text').hasClass('show')) {
            $('.overflow-text').removeClass('show').addClass('hidden');
            $('.lire-suite-text').text('Lire moins');
        } else {
            $('.overflow-text').removeClass('hidden').addClass('show');
            $('.lire-suite-text').text('Lire la suite');
        }
    });

});
