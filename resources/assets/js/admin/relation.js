$(document).ready(function() {
    $.fn.addAddRelationItemSelectedEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedItemId = $(object).val();
                console.log(selectedItemId);
                //$(object).val('');
                
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-relation-item',
                    data: {
                        model: $(object).data('model'),
                        model_id: $(object).data('model-id'),
                        field: $(object).data('relation'),
                        item_id: selectedItemId,
                        full_data: $(object).data('full-data'),
                        type: $(object).hasClass('tags-relation') ? 'tags' : ''
                    },
                    success: function (data) {
                        $('[id=selected-' + $(object).attr('id') + ']').append(data);
                        $('a[data-id=' + selectedItemId + ']', $('[id=selected-' + $(object).attr('id') + ']')).addRemoveSelectedEventsAndDisableSelected();
                        if($(object).data('sortable')) {
                            $('div[id=relation-item-' + $(object).attr('id') + '-' + selectedItemId + ']').setRelationItemsDraggableAndDroppable();
                        }
                        
                    }
                });
            });
        });
    };
    
    $.fn.addRemoveSelectedEventsAndDisableSelected = function() {
        $(this).each(function(index, object) {
            var selectFieldName = $(object).data('field');
            if(typeof $(object).data('level') !== 'undefined') {
                selectFieldName += '-' + $(object).data('level');
            }
            
            $(object).click(function() {
                $('option[value="' + $(object).data('id') + '"]', $('select[id=' + selectFieldName + ']')).removeAttr('disabled');
                $(object).parent().remove();

                $('select[id=' + $('[id=' + selectFieldName + ']').data('depending') + ']').populateItems();
                return false;
            });
            
            $('option[value="' + $(object).data('id') + '"]', $('select[id=' + selectFieldName + ']')).attr('disabled', 'disabled');
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
    
    $.fn.populateItems = function() {
        $(this).each(function(index, object) {
            $(object).empty();
            
            var dependsOnValues = {};
            $.each($(object).data('depends-on'), function( index, dependsOnField ) {
                dependsOnValues[dependsOnField] = $('[id=' + dependsOnField + ']').getValue();
            });

            $.ajax({
                type: 'GET',
                url: '/admin/model/populate-field',
                data: {
                    model: $(object).data('model'),
                    relation: $(object).data('relation'),
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
                var selectedId = $(removeSelectedObject).data('id');

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
    
    
    
    $.fn.setRelationItemsDraggableAndDroppable = function() {
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
                
                var element = e.target;
                var dropId = '';
                while(dropId === '') {
                    $.each(element.classList, function( index, className ) {
                        if(className === 'relation-item') {
                            dropId = element.id;
                        }
                    });
                    if(dropId === '') {
                        element = element.parentNode;
                    }
                }
                
                if(dragId !== dropId) {
                    if(e.originalEvent.layerY < 20) {
                        $('div[id=' + dropId + ']').before($('div[id=' + dragId + ']'));
                    } else {
                        $('div[id=' + dropId + ']').after($('div[id=' + dragId + ']'));
                    }
                }
            });
        });
    };
    
    $('.relation-multiple').addAddRelationItemSelectedEvents();
    $('.remove-selected').addRemoveSelectedEventsAndDisableSelected();
    
    $('.depending-field').addDependingEvents();
    $('.relation-multiple').showOrHide();
    
    $('.relation-item[draggable=true]').setRelationItemsDraggableAndDroppable();
    
    
    
    $.fn.setSelected = function() {
        $(this).each(function(index, object) {
            var selectedValue = $(object).data('selected-values');
            $(object).val(selectedValue);
            
             var selectedItemId = $(object).val();
                console.log(selectedItemId);
            $(object).trigger('change');
        });
    };
    $('.tags-relation').setSelected();
});