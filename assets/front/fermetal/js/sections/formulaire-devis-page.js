
$(document).ready(function () {
    $(".section-qui-somme-nous .button .btn-one-devis").click(function (e) {
        e.preventDefault();
        $(".formulaire-service.popup-devis").addClass('show')
    });
    $(".formulaire-service.popup-devis .content-form button.close").click(function (e) {
        e.preventDefault();
        $('.error-message').remove();
        $('.formulaire-popup.formulaire-service form[name="form-devis"] input, .formulaire-popup.formulaire-service form[name="form-devis"] textarea').val('');
        $(".formulaire-service.popup-devis").removeClass('show')
    });
    /**
      * add loading after section header
      */
    let loading_devis_sommaire = $('#loading-devis-sommaire');
    loading_devis_sommaire = $('<div id="loading-devis-sommaire"></div>');
    $('.formulaire-popup.formulaire-service form[name="form-devis"]').append(loading_devis_sommaire);
    /**
         * action send devis from popup
         */
    $(document).on('click', '.formulaire-popup.formulaire-service .btn-send-devis', function (e) {
        e.preventDefault();
        /**
        * show loading
        */
        loading_devis_sommaire.addClass('show');
        /**
         * get all data
         */
        //let name = $("#name").val();
        let name = $('.formulaire-popup.formulaire-service').find('input[name="name"]').val();
        let phone = $('.formulaire-popup.formulaire-service').find('input[name="tel"]').val();
        let email = $('.formulaire-popup.formulaire-service').find('input[name="email"]').val();
        let code_postal = $('.formulaire-popup.formulaire-service').find('input[name="code_postal"]').val();
        let message = $('.formulaire-popup.formulaire-service').find('textarea[name="message"]').val();

        //console.log(name, phone, email, code_postal, message);
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
            $('.formulaire-popup.formulaire-service').find('input[name="name"]').after('<span class="error-message white">Name is required</span>');
        }
        if (email === '') {
            $('.formulaire-popup.formulaire-service').find('input[name="email"]').after('<span class="error-message white">Email is required</span>');
        }
        if (phone === '') {
            $('.formulaire-popup.formulaire-service').find('input[name="tel"]').after('<span class="error-message white">Phone is required</span>');
        }
        if (code_postal === '') {
            $('.formulaire-popup.formulaire-service').find('input[name="code_postal"]').after('<span class="error-message white">code is required</span>');
        }
        if (message === '') {
            $('.formulaire-popup.formulaire-service').find('textarea[name="message"]').after('<span class="error-message white">Message is required</span>');
        }

        /**
        * Check if the email is valid
        */
        if (email !== '' && !emailRegex.test(email)) {
            $('.formulaire-popup.formulaire-service').find('input[name="email"]').after('<span class="error-message white">Please enter a valid email address</span>');
        }

        /**
         * Check if the phone number is valid
         */
        if (phone !== '' && !phoneRegex.test(phone)) {
            $('.formulaire-popup.formulaire-service').find('input[name="tel"]').after('<span class="error-message white">Please enter a valid 10-digit phone number</span>');
        }
        /**
          * If any error messages exist do ajax request not process
          */
        if ($('.error-message').length > 0) {
            loading_devis_sommaire.removeClass('show');
            return;
        }

        /**
         * If all validations pass do ajax request process
         */
        $.ajax({
            url: "/api/send/devis",
            type: "POST",
            data: {
                name: name,
                email: email,
                phone: phone,
                message: message,
                code_postal: code_postal,
            },
            success: function (response) {
                loading_devis_sommaire.removeClass('show');
                //console.log(response);
                /**
                  * Display success message
                  */
                $('.formulaire-popup.formulaire-service form[name="form-devis"]').before('<span class="success-message">Message sent successfully!</span>');
                /**
                 * remove success message */
                setTimeout(function () {
                    $('.success-message').fadeOut('slow', function () {
                        $(this).remove();
                        /**
                        * reset form
                        */

                        $('.formulaire-popup.formulaire-service form[name="form-devis"] input, .formulaire-popup.formulaire-service form[name="form-devis"] textarea').val('');
                        /**
                         * close the popup
                         */
                        $(".formulaire-popup.formulaire-service.popup-devis").removeClass('show');

                    });
                }, 1000);

            },
            error: function (error) {
                //console.log(error);
            },
        });


    })
})