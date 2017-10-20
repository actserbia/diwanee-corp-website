$(document).ready(function() {
    disableSelectedSubcategories();
    addRemoveSubcategoryEvents();

    $('#category').change(function() {
        $.ajax({
            type: 'GET',
            url: '/ajax/subcategories/' + $(this).val(),
            dataType: 'json',
            success: function (data) {
                $('#subcategories').empty();
                $('#selected-subcategories').empty();

                $('#subcategories').append($('<option>', {
                    value: '',
                    text : ''
                }));

                $.each(data, function (i, subcategory) {
                    $('#subcategories').append($('<option>', {
                        value: subcategory['id'],
                        text: subcategory['name']
                    }));
                });
            }
        });
    });

    $('#subcategories').change(function() {
        $('#selected-subcategories').append('<div>' + $(this).find('option:selected').text() + ' <a href="#" class="subcategories-remove-selected" data-id="' + $(this).val() + '">x</a></div>');
        $('option[value="' + $(this).val() + '"]', this).attr('disabled', 'disabled');

        $(this).val('');

        addRemoveSubcategoryEvents();
    });

    $('.btn-success').click(function() {
        $('.subcategories-remove-selected').each(function(index, value) {
            $('#selected-subcategories-hidden').empty();
            $('#selected-subcategories-hidden').append('<input type="hidden" name="subcategories[]" value="' + $(this).data('id') + '"/>');
        });
        return true;
    });

    function disableSelectedSubcategories() {
        $('.subcategories-remove-selected').each(function(index, value) {
            $('#subcategory option[value="' + $(this).data('id') + '"]').attr('disabled', 'disabled');
        });
    }

    function addRemoveSubcategoryEvents() {
        $('.subcategories-remove-selected').click(function() {
            $('#subcategories option[value="' + $(this).data('id') + '"]').removeAttr('disabled');
            $(this).parent().remove();
            return false;
        });
    }

});