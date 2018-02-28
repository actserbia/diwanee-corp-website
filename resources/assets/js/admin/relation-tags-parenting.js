$(document).ready(function() {
    $.fn.addAddSubtagsEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedValue = $(object).val();
                var selectedValues = [];

                if(selectedValue !== '') {
                    selectedValues.push(selectedValue);
                }

                if($(object).hasClass('relation-multiple')) {
                    $('a[id=' + $(object).data('relation') + '-remove-selected]', $('[id=selected-' + $(object).attr('id') + ']')).each(function(index, aObject) {
                        selectedValues.push($(aObject).data('id'));
                    });
                }

                var nextLevel = $(object).data('level') + 1;
                var nextLevelSelect = $(object).data('relation') + '-' + nextLevel;
                if($('#' + nextLevelSelect).length) {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/tag/get-children',
                        data: {
                            tag_id: selectedValue
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.length === 0) {
                                $('.remove-selected', '#selected-' + nextLevelSelect).trigger('click');

                                $('#' + nextLevelSelect).parent().parent().remove();
                                $('#selected-' + nextLevelSelect).parent().parent().remove();
                                $('#separator-' + nextLevelSelect).remove();
                            } else {
                                if(!$(object).hasClass('relation-multiple')) {
                                    $('#' + nextLevelSelect).empty();
                                    $('#' + nextLevelSelect).append($('<option>', {
                                        value: '',
                                        text: ''
                                    }));
                                }
                                $.each(data, function (index, item) {
                                    $('#' + nextLevelSelect).append($('<option>', {
                                        value: item.value,
                                        text: item.text
                                    }));
                                });
                                if(!$(object).hasClass('relation-multiple')) {
                                    $('.remove-selected', '#selected-' + nextLevelSelect).trigger('click');
                                    $('#' + nextLevelSelect).val('').trigger('change');
                                }
                            }
                        }
                    });
                } else {
                    $(object).setSelectedValues(selectedValues);
                }
            });
        });
    };

    $.fn.addRemoveSubtagsEvents = function() {
        $(this).each(function(index, object) {
            $(object).click(function() {
                var nextLevel = $(object).data('level') + 1;
                var nextLevelSelect = $(object).data('field') + '-' + nextLevel;

                $.ajax({
                    type: 'GET',
                    url: '/admin/model/tag/get-children',
                        data: {
                            tag_id: $(object).data('id')
                        },
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, item) {
                                $.each($('option', '#' + nextLevelSelect), function (index, option) {
                                    if($(option).val() == item.value) {
                                        $(option).remove();
                                    }
                                });

                                $('a[data-id=' + item.value + ']', '#selected-' + nextLevelSelect).trigger('click');
                            });

                            if($('option', '#' + nextLevelSelect).length === 1) {
                                $('#' + nextLevelSelect).parent().parent().remove();
                                $('#selected-' + nextLevelSelect).parent().parent().remove();
                                $('#separator-' + nextLevelSelect).remove();
                            }
                        }
                });
            });
        });
    };


    $.fn.setSelectedValuesFromData = function() {
        $(this).each(function(index, object) {
            var selectedValues = $(object).data('selected-values');
            $(object).setSelectedValues(selectedValues);

            $(object).click(function() {
                $(object).data('selected-values', '');
            });
        });
    };

    $.fn.setSelectedValues = function(selectedValues) {
        if(typeof selectedValues !== 'undefined' && selectedValues.length > 0) {
            $(this).each(function(index, object) {
                var nextLevel = $(object).data('level') + 1;
                var nextLevelSelect = $(object).data('relation') + '-' + nextLevel;
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/tags-parenting/add-tag-subtags',
                    data: {
                        data: $(object).data(),
                        tagsIds: selectedValues,
                        checkSelected: $('#' + $(object).data('relation') + '-1').data('selected-values')
                    },
                    success: function (data) {
                        $('[id=separator-' + $(object).attr('id') + ']').after(data);
                        $('#' + nextLevelSelect).addAddSubtagsEvents();
                        $('#' + nextLevelSelect).addAddRelationItemSelectedEvents();
                        $('.remove-selected', '#selected-' + nextLevelSelect).addRemoveSelectedEventsAndDisableSelected();
                        $('#' + nextLevelSelect).setSelectedValues();
                    }
                });
            });
        }
    };


    RelationsTagsParentingManager = {
        initialize: function() {
            $('.tags-parenting-relation').addAddSubtagsEvents();
            $('.remove-selected').addRemoveSubtagsEvents();
        },

        setSelectedValues: function() {
            $('.tags-parenting-relation').setSelectedValuesFromData();
        }
    };
});