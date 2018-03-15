$(document).ready(function() {
    $('#data_form').submit(function () {
        var form = $(this);

        form.find('input[type=checkbox]').each( function () {
            var checkbox = $(this);
            
            if(!checkbox.is(":disabled")) {
                if(checkbox.is(":checked")) {
                    checkbox.attr('value', '1');
                } else {
                    checkbox.attr('value', '0');
                }
            }
            
            checkbox.prop('disabled', false);
            checkbox.prop('checked', true);
        });
    });
    
    $.fn.beautifyInputField = function() {
        $(this).each(function(index, object) {
            $(object).iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    };
    
    FormManager = {
        beautifyInputFields: function() {
            $('input').beautifyInputField();
        }
    }
});