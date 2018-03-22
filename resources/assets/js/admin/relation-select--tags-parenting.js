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
                    $('a[id=' + $(object).data('field') + '-remove-selected]', $('div[id=selected-' + $(object).attr('id') + ']')).each(function(index, aObject) {
                        selectedValues.push($(aObject).data('id'));
                    });
                }

                var nextLevel = $(object).data('level') + 1;
                var nextLevelSelect = $(object).data('field') + '-' + nextLevel;
                if($('select[id=' + nextLevelSelect + ']').length) {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/tag/get-children',
                        data: {
                            tag_id: selectedValue
                        },
                        dataType: 'json',
                        success: function (data) {
                            if(data.length === 0) {
                                $('a.remove-selected', $('div[id=selected-' + nextLevelSelect + ']')).trigger('click');

                                $('select[id=' + nextLevelSelect + ']').parent().parent().remove();
                                $('div[id=selected-' + nextLevelSelect + ']').parent().parent().remove();
                                $('[id=separator-' + nextLevelSelect + ']').remove();
                            } else {
                                if(!$(object).hasClass('relation-multiple')) {
                                    $('select[id=' + nextLevelSelect + ']').empty();
                                    $('select[id=' + nextLevelSelect + ']').append($('<option>', {
                                        value: '',
                                        text: ''
                                    }));
                                }
                                $.each(data, function (index, item) {
                                    $('select[id=' + nextLevelSelect + ']').append($('<option>', {
                                        value: item.value,
                                        text: item.text
                                    }));
                                });
                                if(!$(object).hasClass('relation-multiple')) {
                                    $('a.remove-selected', '[id=selected-' + nextLevelSelect + ']').trigger('click');
                                    $('select[id=' + nextLevelSelect + ']').val('').trigger('change');
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
                                $.each($('option', $('select[id=' + nextLevelSelect + ']')), function (index, option) {
                                    if($(option).val() == item.value) {
                                        $(option).remove();
                                    }
                                });

                                $('a[data-id=' + item.value + ']', $('div[id=selected-' + nextLevelSelect + ']')).trigger('click');
                            });

                            if($('option', $('select[id=' + nextLevelSelect + ']')).length === 1) {
                                $('select[id=' + nextLevelSelect + ']').parent().parent().remove();
                                $('div[id=selected-' + nextLevelSelect + ']').parent().parent().remove();
                                $('[id=separator-' + nextLevelSelect + ']').remove();
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
                var nextLevelSelect = $(object).data('field') + '-' + nextLevel;
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/tags-parenting/add-tag-subtags',
                    data: {
                        data: $(object).data(),
                        tagsIds: selectedValues,
                        checkSelected: $('select[id=' + $(object).data('field') + '-1]').data('selected-values')
                    },
                    success: function (data) {
                        $('[id=separator-' + $(object).attr('id') + ']').after(data);
                        $('select[id=' + nextLevelSelect + ']').addAddSubtagsEvents();
                        $('select[id=' + nextLevelSelect + ']').addAddRelationItemSelectedEvents();
                        $('a.remove-selected', $('div[id=selected-' + nextLevelSelect + ']')).addRemoveSelectedEventsAndDisableSelected();
                        if($('select[id=' + nextLevelSelect + ']').data('sortable')) {
                            $('.relation-item[draggable=true]', $('div[id=selected-' + nextLevelSelect + ']')).setRelationItemsDraggableAndDroppable();
                        }
                        $('select[id=' + nextLevelSelect + ']').setSelectedValues();
                    }
                });
            });
        }
    };


    RelationsTagsParentingManager = {
        initialize: function() {
            $('select.tags-parenting-relation').addAddSubtagsEvents();
            $('a.remove-selected').addRemoveSubtagsEvents();
        },

        setSelectedValues: function() {
            $('select.tags-parenting-relation').setSelectedValuesFromData();
        }
    };
});