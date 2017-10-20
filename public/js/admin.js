$(document).ready(function() {
    var tagsByType = {
        parents: { subcategory: 'category' },
        children: { category: 'subcategory' }
    };

    function getAjaxUrl(mainValue, tagsName) {
        if(tagsName === 'subcategories') {
            return '/ajax/subcategories/' + mainValue;
        }

        if(tagsName === 'parents' || tagsName === 'children') {
            if(typeof tagsByType[tagsName][mainValue] !== 'undefined') {
                return '/ajax/tags/' + mainValue;
            }
        }

        return '';
    }

    $.fn.populateTags = function(mainValue) {
        $(this).each(function(index, object) {
            var tagsName = $(this).attr('id'); // parents or children or subcategories
            
            $(this).empty();
            $('#selected-' + tagsName).empty();
            
            var ajaxUrl = getAjaxUrl(mainValue, tagsName);
            if(ajaxUrl !== '') {
                var thisObject = $(this);
                $.ajax({
                    type: 'GET',
                    url: ajaxUrl,
                    dataType: 'json',
                    context: this,
                    success: function (data) {
                        thisObject.append($('<option>', {
                            value: '',
                            text : ''
                        }));

                        $.each(data, function (i, tag) {
                            thisObject.append($('<option>', {
                                value: tag['id'],
                                text: tag['name']
                            }));
                        });
                    }
                });
            }
        });
    };

    $.fn.addAddSelectedEvents = function() {
        $(this).each(function(index, object) {
            $(this).change(function() {
                $('#selected-' + $(this).attr('id')).append('<div>' + $(this).find('option:selected').text() + ' <a href="#" class="' + $(this).attr('id') + '-remove-selected" data-tags-type="' + $(this).attr('id') + '" data-id="' + $(this).val() + '">x</a></div>');
                $('option[value="' + $(this).val() + '"]', this).attr('disabled', 'disabled');

                $(this).val('');

                $('.' + $(this).attr('id') + '-remove-selected').addRemoveSelectedEvents();
            });
        });
    };

    $.fn.disableSelectedTags = function() {
        $(this).each(function(index, object) {
            var tagsName = $(this).data('tags-type'); // parents or children or subcategories
            $('#' + tagsName + ' option[value="' + $(this).data('id') + '"]').attr('disabled', 'disabled');
        });
    };

    $.fn.addRemoveSelectedEvents = function() {
        $(this).each(function(index, object) {
            $(this).click(function() {
                var tagsName = $(this).data('tags-type'); // parents or children or subcategories
                $('#' + tagsName + ' option[value="' + $(this).data('id') + '"]').removeAttr('disabled');
                $(this).parent().remove();
                return false;
            });
        });
    };

    $.fn.populateMultipleTagsFormData = function() {
        $(this).each(function(index, object) {
            var tagsName = $(this).data('tags-type'); // parents or children or subcategories
            if(index === 0) {
                $('#selected-' + tagsName + '-hidden').empty();
            }
            $('#selected-' + tagsName + '-hidden').append('<input type="hidden" name="' + tagsName + '[]" value="' + $(this).data('id') + '"/>');
        });
    };


    $('#type').change(function() {
        $('#parents, #children').populateTags($(this).val());
    });

    $('#category').change(function() {
        $('#subcategories').populateTags($(this).val());
    });

    $('#subcategories, #parents, #children').addAddSelectedEvents();
    $('.subcategories-remove-selected, .parents-remove-selected, .children-remove-selected').addRemoveSelectedEvents();

    $('.subcategories-remove-selected, .parents-remove-selected, .children-remove-selected').disableSelectedTags();

    $('.btn-success').click(function() {
        $('.subcategories-remove-selected, .parents-remove-selected, .children-remove-selected').populateMultipleTagsFormData();
    });

});