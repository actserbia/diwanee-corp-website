$(document).ready(function() {
    $.fn.addAddRelationItemSelectedEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedItemId = $(object).val();
                if($(object).hasClass('relation-multiple')) {
                    $(object).val('');
                }

                if(selectedItemId !== null && selectedItemId !== '') {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/add-relation-item',
                        data: {
                            itemId: selectedItemId,
                            type: $(object).hasClass('tags-parenting-relation') ? 'tags_parenting' : '',
                            relation: $(object).data('relation'),
                            model: $(object).data('model'),
                            modelId: $(object).data('model-id'),
                            modelType: $(object).data('model-type'),
                            fullData: $(object).data('full-data'),
                            level: $(object).data('level')
                        },
                        success: function (data) {
                            $('div[id=selected-' + $(object).attr('id') + ']').append(data);
                            $('a[data-id=' + selectedItemId + ']', $('div[id=selected-' + $(object).attr('id') + ']')).addRemoveSelectedEventsAndDisableSelected();
                            if($(object).data('sortable')) {
                                $('div[id=relation-item-' + $(object).attr('id') + '-' + selectedItemId + ']').setRelationItemsDraggableAndDroppable();
                            }
                            $('.add-checkbox', '#selected-' + $(object).attr('id')).addAddCheckboxEvents();
                            $('input.hierarchy[type=checkbox]', '#selected-' + $(object).attr('id')).setHierarchyCheckboxEvents();
                            $('input[type=checkbox]', '#selected-' + $(object).attr('id')).beautifyInputField();
                        }
                    });
                }
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

                $('select[id=' + $('select[id=' + selectFieldName + ']').data('depending') + ']').populateItems();
                
                return false;
            });

            $('option[value="' + $(object).data('id') + '"]', $('select[id=' + selectFieldName + ']')).attr('disabled', 'disabled');
        });

        $(this).addRemoveSubtagsEvents();
    };


    $.fn.addDependingEvents = function() {
        $(this).each(function(index, dependingObject) {
            $.each($(dependingObject).data('depends-on'), function( index, dependsOnField ) {
                $('[id=' + dependsOnField + ']').data('depending', $(dependingObject).attr('id'));
                $('[id=' + dependsOnField + ']').change(function() {
                    $(dependingObject).populateItems();
                });
            });
        });
    };

    $.fn.populateItems = function() {
        $(this).each(function(index, object) {
            console.log($(object));
            $(object).empty();

            var dependsOnValues = {};
            $.each($(object).data('depends-on'), function( index, dependsOnField ) {
                dependsOnValues[dependsOnField] = $('[id=' + dependsOnField + ']').getValue();
            });
            
            $.ajax({
                type: 'GET',
                url: '/admin/model/populate-field',
                data: {
                    data: $(object).data(),
                    dependsOnValues: dependsOnValues
                },
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, item) {
                        var option = {
                            value: item.value,
                            text: item.text
                        };
                        if(item.selected === 'selected') {
                            option.selected = 'selected';
                        }

                        $(object).append($('<option>', option));
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
            $('a[id=' + $(this).attr('id') + '-remove-selected]').each(function(index, removeSelectedObject) {
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
                    $('div[id=selected-' + field + ']').parent().parent().fadeIn('slow');
                }
            } else {
                if($(object).parent().parent().is(":visible")) {
                    $(object).parent().parent().fadeOut('slow');
                    $('div[id=selected-' + field + ']').parent().parent().fadeOut('slow');
                }
            }
        });
    };

    $.fn.syncronizeSelectedMultipleItems = function() {
        $(this).each(function(index, object) {
            $('a[id=' + $(object).attr('id') + '-remove-selected]').each(function(index, removeSelectedObject) {
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
    
    
    $.fn.addAddNewRelationItemEvents = function() {
        $(this).each(function(index, object) {
            $(object).click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-new-relation-item',
                    data: {
                        data: $(object).data()
                    },
                    success: function (data) {
                        $(object).before(data);
                        $(object).data('last-index', $(object).data('last-index') + 1);
                        
                        $('a.remove-added-relation-item', $(object).parent()).addRemoveAddedRelationItemEvents();
                        
                        $('.add-checkbox', $(object).parent()).addAddCheckboxEvents();
                        $('input.hierarchy[type=checkbox]', $(object).parent()).setHierarchyCheckboxEvents();
                        $('input[type=checkbox]', $(object).parent()).beautifyInputField();
                    }
                });
                return false;
            });
        });
    };
    
    $.fn.addRemoveAddedRelationItemEvents = function() {
        $(this).each(function(index, object) {
            $(object).click(function() {
                $(object).parent().remove();
                return false;
            });
        });
    };



    RelationsManager = {
        initialize: function() {
            $('select.relation-multiple').addAddRelationItemSelectedEvents();
            $('a.remove-selected').addRemoveSelectedEventsAndDisableSelected();

            $('select.depending-field').addDependingEvents();
            $('select.relation-multiple').showOrHide();

            $('.relation-item[draggable=true]').setRelationItemsDraggableAndDroppable();
            
            $('a.add-new-relation-item').addAddNewRelationItemEvents();
            $('a.remove-added-relation-item').addRemoveAddedRelationItemEvents();
        }
    };
});