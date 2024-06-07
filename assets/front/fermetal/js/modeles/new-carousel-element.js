//import "../../css/modeles/modele-carousel.css";
$(document).ready(function () {
  if ($(window).width() < 768) {
    $('.slick-slider').slick('unslick');
  }

  /**
   * carousel slide carousel
   */
  $(".slide-carousel-formulaire").slick({

    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
    centerMode: false,
    draggable: false,
    infinite: false,
    swipe: false,
    lazyLoad: 'ondemand',
    prevArrow: $('.back-btn'),
    nextArrow: $('.devis-gratuit'),

  });
  /**
    * slide  next
    */
  $(document).on('click', '.devis-gratuit', function (e) {
    e.preventDefault();
    $('.formulaire-service:not(.popup-devis)').css('visibility', 'visible');
    $(".slide-carousel-formulaire").slick('slickNext');
  });
  /**
    * slide  prev
    */
  $(document).on('click', '.back-btn', function (e) {
    e.preventDefault();
    $(".slide-carousel-formulaire").slick('slickPrev');
  });
  /**
   * add loading after section header
   */
  loading_devis = $('<div class="loading-devis-header "></div>');
  $('.main-slider .formulaire-service form[name="form-devis"]').append(loading_devis);

  /**
   * send mail
   */
  $(document).on('click', '.main-slider  .btn-send-devis', function (e) {
    e.preventDefault();
    /**
    * show loading
    */
    console.log('loading_devis', loading_devis);
    $('.loading-devis-header').addClass('show');

    /**
     * get data 
     */
    let name = $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="name"]').val();
    let phone = $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="tel"]').val();
    let email = $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="email"]').val();
    let code_postal = $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="code_postal"]').val();
    let message = $('.main-slider .formulaire-service form[name="form-devis"]').find('textarea[name="message"]').val();
    console.log(name, phone, email, code_postal, message);

    /**
     * test validating email 
     */
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    /**
    * test validating phone number 
    */
    let phoneRegex = /^\d{10}$/;

    /**
     * remove error messages
     */
    $('.error-message').remove();
    /**
    * remove success messages
    */
    $('.success-message').remove();

    /**
     * Check if the fields are empty
     */
    if (name === '') {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="name"]').after('<span class="error-message white">Name is required</span>');
    }
    if (email === '') {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="email"]').after('<span class="error-message white">Email is required</span>');
    }
    if (phone === '') {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="tel"]').after('<span class="error-message white">Phone is required</span>');
    }
    if (code_postal === '') {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="code_postal"]').after('<span class="error-message white">code is required</span>');
    }
    if (message === '') {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('textarea[name="message"]').after('<span class="error-message white">Message is required</span>');
    }

    /**
     * Check if the email is valid
     */
    if (email !== '' && !emailRegex.test(email)) {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="email"]').after('<span class="error-message white">Please enter a valid email address</span>');
    }

    /**
     * Check if the phone number is valid
     */
    if (phone !== '' && !phoneRegex.test(phone)) {
      $('.main-slider .formulaire-service form[name="form-devis"]').find('input[name="tel"]').after('<span class="error-message white">Please enter a valid 10-digit phone number</span>');
    }

    /**
     * If any error messages exist do ajax request not process
     */
    if ($('.error-message').length > 0) {
      $('.loading-devis-header').removeClass('show');

      return;
    }

    /**
     * If all validations pass do ajax request process
     */
    $.ajax({
      url: "/api/send/devis_header",
      type: "POST",
      data: {
        name: name,
        email: email,
        phone: phone,
        message: message,
        code_postal: code_postal,
      },
      success: function (response) {

        $('.loading-devis-header').removeClass('show');

        if (response.success) {
          //console.log(response);
          /**
           * Display success message
           */
          $('form[name="form-devis"]').parent().append('<span style="color:#fff;" class="success-message">Message sent successfully!</span>');
          /**
           * remove success message */
          setTimeout(function () {
            $('.success-message').fadeOut('slow', function () {
              $(this).remove();
              /**
              * reset form
              */
              //$('form[name="form-devis"]')[0].reset();
              $('.main-slider .formulaire-service form[name="form-devis"] input, .main-slider .formulaire-service form[name="form-devis"] textarea').val('');


            });
          }, 1000);

        }
      },
      error: function (error) {
        //console.log(error);
      },
    });
  });
});