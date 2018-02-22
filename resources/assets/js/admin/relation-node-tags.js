$(document).ready(function() {
    $.fn.addAddSubtagsEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedTagId = $(object).val();
                var selectedTagsIds = [];

                if(selectedTagId !== '') {
                    selectedTagsIds.push(selectedTagId);
                }

                if($(object).hasClass('relation-multiple')) {
                    $('a[id=' + $(object).data('relation') + '-remove-selected]', $('[id=selected-' + $(object).attr('id') + ']')).each(function(index, aObject) {
                        selectedTagsIds.push($(aObject).data('id'));
                    });
                }

                var nextLevel = $(object).data('level') + 1;
                var nextLevelSelect = $(object).data('relation') + '-' + nextLevel;

                if($('#' + nextLevelSelect).length) {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/tag/get-children',
                        data: {
                            tag_id: selectedTagId
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
                                    $('#' + nextLevelSelect).html('');
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
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/node-tags/add-tag-subtags',
                        data: {
                            data: $(object).data(),
                            tagsIds: selectedTagsIds,
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
    
    
    $.fn.setSelectedValues = function() {
        $(this).each(function(index, object) {
            var selectedValues = $(object).data('selected-values');
            $.each(selectedValues, function (index, selectedValue) {
                $(object).val(selectedValue).trigger('change');
            });
            
            $(object).click(function() {
                $(object).data('selected-values', '');
            });
        });
    };
    
    
    RelationsNodeTagsManager = {
        initialize: function() {
            $('.node-tags-relation').addAddSubtagsEvents();
            $('.remove-selected').addRemoveSubtagsEvents();
        },
        
        setSelectedValues: function() {
            $('.node-tags-relation').setSelectedValues();
        }
    };
});