$(document).ready(function() {
    if($('html').attr('lang') !== 'en') {
        if(typeof Html5Localization === 'undefined') {
            Html5Localization = {};
        }

        Html5CustomValidation = {
            setRequiredCustomValidity: function(object, type) {
                if($.trim($(object).val()) === '') {
                    object.setCustomValidity(Html5Localization[$('html').attr('lang')].validation.required[type]);
                } else {
                    object.setCustomValidity('');
                }
            },

            setEmailCustomValidity: function(object) {
                var checkEmail = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/);
                if(checkEmail.test($.trim($(object).val()))) {
                    object.setCustomValidity('');
                } else {
                    object.setCustomValidity(Html5Localization[$('html').attr('lang')].validation.email);
                }
            }
        };


        $('input[required]').each(function(index, object) {
            Html5CustomValidation.setRequiredCustomValidity(object, 'input');

            $(object).change(function() {
                Html5CustomValidation.setRequiredCustomValidity(this, 'input');
            });

            $(object).keyup(function() {
                Html5CustomValidation.setRequiredCustomValidity(this);
            });
        });

        $('select[required]').each(function(index, object) {
            Html5CustomValidation.setRequiredCustomValidity(object, 'select');
            $(object).change(function() {
                Html5CustomValidation.setRequiredCustomValidity(this, 'select');
            });
        });

        $('input[type=email]').each(function(index, object) {
            $(object).paste(function() {
                Html5CustomValidation.setEmailCustomValidity(this);
            });

            $(object).keyup(function() {
                Html5CustomValidation.setEmailCustomValidity(this);
            });
        });
    }
});