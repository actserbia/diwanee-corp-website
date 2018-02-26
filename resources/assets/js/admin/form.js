$(document).ready(function() {
    $('#data_form').submit(function () {
        var form = $(this);

        form.find('input[type=checkbox]').each( function () {
            var checkbox = $(this);
            
            if(checkbox.is(":checked") || checkbox.is(":disabled")) {
                checkbox.attr('value', '1');
            } else {
                checkbox.attr('value', '0');
            }
            checkbox.prop('disabled', false);
            checkbox.prop('checked', true);
        });
    });
    
    FormManager = {
        beautifyCheckboxes: function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        }
    }
});