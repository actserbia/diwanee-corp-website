$(document).ready(function() {
    disableSelectedSubcategories();
    addRemoveSubcategoryEvents();

    $('#category').change(function() {
        $.ajax({
            type: 'GET',
            url: '/ajax/subcategories/' + $(this).val(),
            dataType: 'json',
            success: function (data) {
                $('#subcategory').empty();
                $('#selected-subcategories').empty();

                $('#subcategory').append($('<option>', {
                    value: '',
                    text : ''
                }));

                $.each(data, function (i, subcategory) {
                    $('#subcategory').append($('<option>', {
                        value: subcategory['id'],
                        text: subcategory['name']
                    }));
                });
            }
        });
    });

    $('#subcategory').change(function() {
        $('#selected-subcategories').append('<div class="subcategory">' + $(this).find('option:selected').text() + ' <a href="#" class="subcategory-remove" data-id="' + $(this).val() + '">x</a></div>');
        $('option[value="' + $(this).val() + '"]', this).attr('disabled', 'disabled');

        $(this).val('');

        addRemoveSubcategoryEvents();
    });

    $('.btn-success').click(function() {
        $('.subcategory-remove').each(function(index, value) {
            $('#selected-subcategories-hidden').append('<input type="hidden" name="subcategories[]" value="' + $(this).data('id') + '"/>');
        });
        return true;
    });

    function disableSelectedSubcategories() {
        $('.subcategory-remove').each(function(index, value) {
            $('#subcategory option[value="' + $(this).data('id') + '"]').attr('disabled', 'disabled');
        });
    }

    function addRemoveSubcategoryEvents() {
        $('.subcategory-remove').click(function() {
            $('#subcategory option[value="' + $(this).data('id') + '"]').removeAttr('disabled');
            $(this).parent().remove();
            return false;
        });
    }

});