//import "../../css/sections/section-header.css";
$(document).ready(function () {
    /**
     * carousel avis
     */
    $(".slcik-carousel-form").slick({
        /**
         * Afficher 4 diapositives en même temps
         */
        slidesToShow: 1,
        /**
         * Faire défiler par 4 diapositives
         */
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        centerMode: false,
        infinite: false,
        draggable: false,
        lazyLoad: 'ondemand',
    });
    /**
     * slide to next
     */
    $(document).on('click', '.slcik-carousel-form .devis-gratuit', function (e) {
        e.preventDefault();
        $(".slcik-carousel-form").slick('slickNext');
    });
    $(document).on('click', '.slcik-carousel-form .infs-services .back-btn', function (e) {
        e.preventDefault();
        $(".slcik-carousel-form").slick('slickPrev');
    });

    /**
       * add loading after section header
       */
    let loading_devis_service = $('#loading-devis-service');
    loading_devis_service = $('<div id="loading-devis-service"></div>');
    $('.section-header-service .formulaire-service form[name="form-devis"]').append(loading_devis_service);

    /**
     * send mail
     */
    $(document).on('click', '.section-header-service .btn-send-devis', function (e) {
        e.preventDefault();
        /**
        * show loading
        */
        loading_devis_service.addClass('show');

        /**
         * get data 
         */
        let name = $(this).closest('.section-header-service').find('input[name="name"]').val();
        let phone = $(this).closest('.section-header-service').find('input[name="tel"]').val();
        let email = $(this).closest('.section-header-service').find('input[name="email"]').val();
        let code_postal = $(this).closest('.section-header-service').find('input[name="code_postal"]').val();
        let message = $(this).closest('.section-header-service').find('textarea[name="message"]').val();
        let page = $(this).closest('.section-header-service').find('input[name="page-title"]').val();
        console.log(name, phone, email, code_postal, message, page);

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
            $(this).closest('.section-header-service').find('input[name="name"]').after('<span class="error-message">Name is required</span>');
        }
        if (email === '') {
            $(this).closest('.section-header-service').find('input[name="email"]').after('<span class="error-message">Email is required</span>');
        }
        if (phone === '') {
            $(this).closest('.section-header-service').find('input[name="tel"]').after('<span class="error-message">Phone is required</span>');
        }
        if (code_postal === '') {
            $(this).closest('.section-header-service').find('input[name="code_postal"]').after('<span class="error-message">code is required</span>');
        }
        if (message === '') {
            $(this).closest('.section-header-service').find('textarea[name="message"]').after('<span class="error-message">Message is required</span>');
        }

        /**
         * Check if the email is valid
         */
        if (email !== '' && !emailRegex.test(email)) {
            $(this).closest('.section-header-service').find('input[name="email"]').after('<span class="error-message white">Please enter a valid email address</span>');
        }

        /**
         * Check if the phone number is valid
         */
        if (phone !== '' && !phoneRegex.test(phone)) {
            $(this).closest('.section-header-service').find('input[name="tel"]').after('<span class="error-message white">Please enter a valid 10-digit phone number</span>');
        }

        /**
         * If any error messages exist do ajax request not process
         */
        if ($('.error-message').length > 0) {
            loading_devis_service.removeClass('show');

            return;
        }

        /**
         * If all validations pass do ajax request process
         */
        $.ajax({
            url: "/api/service/mail",
            type: "POST",
            data: {
                name: name,
                email: email,
                phone: phone,
                message: message,
                code_postal: code_postal,
                page: page,
            },
            success: function (response) {
                loading_devis_service.removeClass('show');

                if (response.success) {
                    /**
                     * Display success message 
                     */
                    $('form[name="form-devis"]').after('<span class="success-message">Message sent successfully!</span>');
                    /**
                     * remove success message */
                    setTimeout(function () {
                        $('.success-message').fadeOut('slow', function () {
                            $(this).remove();
                            /**
                            * reset form
                            */
                            $('form[name="form-devis"]')[0].reset();

                        });
                    }, 1000);

                }
            },
            error: function (error) {
                //console.log(error);
            },
        });
    });

})