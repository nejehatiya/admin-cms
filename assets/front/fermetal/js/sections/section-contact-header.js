//import "../../css/sections/section-contact-header.css";
$(document).ready(function () {
    /**
     * add loading after div contact
     */
    let loadingSpinner = $('#loading-spinner');
    loadingSpinner = $('<div id="loading-spinner"></div>');
    $('form[name="contact"]').append(loadingSpinner);
    /**
     * action send mail 
     */
    $(document).on('click', '#contact_save', function (e) {
        e.preventDefault();
        /**
         * show loading
         */
        loadingSpinner.addClass('show');
        /**
         * get data 
         */
        let name = $('#contact_name').val();
        let phone = $('#contact_phone').val();
        let email = $('#contact_email').val();
        let objet = $('#contact_objet').val();
        let message = $('#contact_message').val();

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
            $('#contact_name').after('<span class="error-message red">Name is required</span>');
        }
        if (email === '') {
            $('#contact_email').after('<span class="error-message red">Email is required</span>');
        }
        if (phone === '') {
            $('#contact_phone').after('<span class="error-message red">Phone is required</span>');
        }
        if (objet === '') {
            $('#contact_objet').after('<span class="error-message red">Object is required</span>');
        }
        if (message === '') {
            $('#contact_message').after('<span class="error-message red">Message is required</span>');
        }

        /**
         * Check if the email is valid
         */
        if (email !== '' && !emailRegex.test(email)) {
            $('#contact_email').after('<span class="error-message red">Please enter a valid email address</span>');
        }

        /**
         * Check if the phone number is valid
         */
        if (phone !== '' && !phoneRegex.test(phone)) {
            $('#contact_phone').after('<span class="error-message red">Please enter a valid 10-digit phone number</span>');
        }

        /**
         * If any error messages exist do ajax request not process
         */
        if ($('.error-message').length > 0) {
            loadingSpinner.removeClass('show');
            return;
        }

        /**
         * If all validations pass do ajax request process
         */
        $.ajax({
            url: "/api/send/contact",
            type: "POST",
            data: {
                name: name,
                email: email,
                phone: phone,
                message: message,
                objet: objet,
            },
            success: function (response) {
                loadingSpinner.removeClass('show');
                if (response.success) {
                    //console.log(response);
                    /**
                     * Display success message
                     */
                    $('form[name="contact"]').append('<span class="success-message">Message sent successfully!</span>');
                    /**
                     * remove success message */
                    setTimeout(function () {
                        $('.success-message').fadeOut('slow', function () {
                            $(this).remove();
                            /**
                            * reset form
                            */
                            $('form[name="contact"]')[0].reset();

                        });
                    }, 500);

                }
            },
            error: function (error) {
                //console.log(error);
            },
        });
    });
});
