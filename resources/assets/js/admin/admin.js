$(document).ready(function() {
    $.fn.populateItems = function() {
        $(this).each(function(index, object) {
            var field = $(object).attr('id');
            
            $(object).empty();
            
            var dependsOnValues = {};
            $.each($(object).data('depends-on'), function( index, dependsOnField ) {
                dependsOnValues[dependsOnField] = $('[id=' + dependsOnField + ']').getValue();
            });

            $.ajax({
                type: 'GET',
                url: '/ajax/model/populate-field',
                data: {
                    model: $(object).data('model'),
                    relation: field,
                    dependsOnValues: dependsOnValues,
                    column: $(object).data('column')
                },
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, item) {
                        $(object).append($('<option>', {
                            value: item.value,
                            text: item.text
                        }));
                    });

                    $(object).syncronizeSelectedMultipleItems();
                        
                    $(object).showOrHide();
                },
                error: function () {
                    $(object).showOrHide();
                }
            });
        });
    };

    $.fn.addAddSelectedEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedItemId = $(object).val();
                var selectedItemText= $(object).find('option:selected').text();
                $(object).val('');
                
                $.ajax({
                    type: 'GET',
                    url: '/ajax/model/add-selected-item',
                    data: {
                        field: $(object).attr('id'),
                        item: {
                            'id': selectedItemId,
                            'name': selectedItemText
                        },
                        sortable: $(object).data('sortable')
                    },
                    success: function (data) {
                        $('[id=selected-' + $(object).attr('id') + ']').append(data);
                        $('a[data-id=' + selectedItemId + ']', $('[id=selected-' + $(object).attr('id') + ']')).addRemoveSelectedEventsAndDisableSelected();
                        if($(object).data('sortable')) {
                            $('div[id=selected-item-' + $(object).attr('id') + '-' + selectedItemId + ']').setSelectedItemsDraggableAndDroppable();
                        }
                        
                    }
                });
            });
        });
    };

    $.fn.addRemoveSelectedEventsAndDisableSelected = function() {
        $(this).each(function(index, object) {
            var selectFieldId = $(object).data('field');
            
            $(object).click(function() {
                $('option[value="' + $(object).data('id') + '"]', $('select[id=' + selectFieldId + ']')).removeAttr('disabled');
                $(object).parent().remove();

                $('select[id=' + $('[id=' + selectFieldId + ']').data('depending') + ']').populateItems();
                return false;
            });
            
            $('option[value="' + $(object).data('id') + '"]', $('select[id=' + selectFieldId + ']')).attr('disabled', 'disabled');
        });
    };

    $.fn.populateMultipleItemsFormData = function() {
        $(this).each(function(index, object) {
            var field = $(object).data('field');
            if(index === 0) {
                $('[id=selected-' + field + '-hidden]').empty();
            }
            $('[id=selected-' + field + '-hidden]').append('<input type="hidden" name="' + field + '[]" value="' + $(object).data('id') + '"/>');
        });
    };
    
    $.fn.getValue = function() {
        var value = [];
        if($(this).hasClass('relation-multiple')) {
            $('[id=' + $(this).attr('id') + '-remove-selected]').each(function(index, removeSelectedObject) {
                value.push($(removeSelectedObject).data('id'));
            });
            if($(this).val() !== "") {
                value.push($(this).val());
            }
        } else {
            value = $(this).val();
        }
        return value;
    };

    $.fn.showOrHide = function() {
        $(this).each(function(index, object) {
            var field = $(object).attr('id');
            
            if($(object).children('option').length > 1) {
                if(!$(object).parent().parent().is(":visible")) {
                    $(object).parent().parent().fadeIn('slow');
                    $('[id=selected-' + field + ']').parent().parent().fadeIn('slow');
                }
            } else {
                if($(object).parent().parent().is(":visible")) {
                    $(object).parent().parent().fadeOut('slow');
                    $('[id=selected-' + field + ']').parent().parent().fadeOut('slow');
                }
            }
        });
    };

    $.fn.addDependingEvents = function() {
        $(this).each(function(index, dependingObject) {
            $.each($(dependingObject).data('depends-on'), function( index, dependsOnField ){
                $('[id=' + dependsOnField + ']').data('depending', $(dependingObject).attr('id'));
                $('[id=' + dependsOnField + ']').change(function() {
                    $('[id=' + $(dependingObject).attr('id') + ']').populateItems();
                });
            });
        });
    };

    $.fn.syncronizeSelectedMultipleItems = function() {
        $(this).each(function(index, object) {
            $('[id=' + $(object).attr('id') + '-remove-selected]').each(function(index, removeSelectedObject) {
                selectedId = $(removeSelectedObject).data('id');

                var found = false;
                $(object).children('option').each(function(index, option) {
                    if($(option).val() == selectedId) {
                        $(option).attr('disabled', 'disabled');
                        found = true;
                    }
                });

                if(found === false) {
                    $(removeSelectedObject).parent().remove();
                }
            });
        });
    };
    
    $.fn.setSelectedItemsDraggableAndDroppable = function() {
        $(this).each(function(index, object) {
            $(object).on('dragstart', function(e) {
                e.originalEvent.dataTransfer.setData('id', e.target.id);
            });
            
            $(object).on('dragover', function(e) {
                e.preventDefault();
            });
            
            $(object).on('drop', function(e) {
                e.preventDefault();
                var dragId = e.originalEvent.dataTransfer.getData('id');
                if(e.originalEvent.layerY < 10) {
                    $('div[id=' + e.target.id + ']').before($('div[id=' + dragId + ']'));
                } else {
                    $('div[id=' + e.target.id + ']').after($('div[id=' + dragId + ']'));
                }
            });
        });
    };
    
    $('.selected-item[draggable=true]').setSelectedItemsDraggableAndDroppable();
    
    $('.depending-field').addDependingEvents();

    $('.relation-multiple').addAddSelectedEvents();
    $('.remove-selected').addRemoveSelectedEventsAndDisableSelected();

    $('.relation-multiple').showOrHide();

    $('#data_form .btn-success').click(function() {
        $('.remove-selected').populateMultipleItemsFormData();
    });
});