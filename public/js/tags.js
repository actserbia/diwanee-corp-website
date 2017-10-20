$(document).ready(function() {
    disableSelectedTags();
    addRemoveSelectedEvents();

    $('#type').change(function() {
        var parentsByType = {subcategory: 'category'};
        populateTags($(this).val(), parentsByType, $('#parents'), $('#selected-parents'));

        var childrenByType = {category: 'subcategory'};
        populateTags($(this).val(), childrenByType, $('#children'), $('#selected-children'));
    });

    $('#parents, #children').change(function() {
        $('#selected-' + $(this).attr('id')).append('<div>' + $(this).find('option:selected').text() + ' <a href="#" class="' + $(this).attr('id') + '-remove-selected" data-tags-type="' + $(this).attr('id') + '" data-id="' + $(this).val() + '">x</a></div>');
        $('option[value="' + $(this).val() + '"]', this).attr('disabled', 'disabled');

        $(this).val('');

        addRemoveSelectedEvents();
    });

    $('.btn-success').click(function() {
        $('.parents-remove-selected, .children-remove-selected').each(function(index, value) {
            var tagsType = $(this).data('tags-type'); // parents or children
            $('#selected-' + tagsType + '-hidden').empty();
            $('#selected-' + tagsType + '-hidden').append('<input type="hidden" name="' + tagsType + '[]" value="' + $(this).data('id') + '"/>');
        });
        return true;
    });

    function disableSelectedTags() {
        $('.parents-remove-selected, .children-remove-selected').each(function(index, value) {
            var tagsType = $(this).data('tags-type'); // parents or children
            $('#' + tagsType + ' option[value="' + $(this).data('id') + '"]').attr('disabled', 'disabled');
        });
    }

    function addRemoveSelectedEvents() {
        $('.parents-remove-selected, .children-remove-selected').click(function() {
            var tagsType = $(this).data('tags-type'); // parents or children
            $('#' + tagsType + ' option[value="' + $(this).data('id') + '"]').removeAttr('disabled');
            $(this).parent().remove();
            return false;
        });
    }


    function populateTags(type, tagsByType, divList, divSelected) {
        console.log(type);
        divList.empty();
        divSelected.empty();
        if(typeof tagsByType[type] !== 'undefined') {
            $.ajax({
                type: 'GET',
                url: '/ajax/tags/' + tagsByType[type],
                dataType: 'json',
                success: function (data) {
                    divList.append($('<option>', {
                        value: '',
                        text : ''
                    }));

                    $.each(data, function (i, tag) {
                        divList.append($('<option>', {
                            value: tag['id'],
                            text: tag['name']
                        }));
                    });
                }
            });
        }
    }

});