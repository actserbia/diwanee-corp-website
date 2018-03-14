$(document).ready(function() {
    $.fn.relationPopulateTypeheadFromList = function() {
        $(this).each(function(index, object) {
            var relation = $(object).data('relation');

            $(object).typeahead('destroy');
            $(object).typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
                source: RelationInputsManager.typeaheadList[relation]
            });
        });
    };

    $.fn.relationPopulateTypehead = function() {
        $(this).each(function(index, object) {
            var relation = $(object).data('relation');

            if(typeof RelationInputsManager.typeaheadList[relation] !== 'undefined') {
                $(object).relationPopulateTypeheadFromList();
                $(object).riSyncronizeData();
                $(object).riAddRelationEvents();
                $(object).riShowOrHide();
            } else {
                var dependsOnValues = {};
                $.each($(object).data('depends-on'), function( index, dependsOnField ) {
                    dependsOnValues[dependsOnField] = $('[id=' + dependsOnField + ']').getValue();
                });

                $.ajax({
                    type: 'GET',
                    url: '/admin/model/typeahead/model-relation-items',
                    data: {
                        relation: $(object).data('relation'),
                        model: $(object).data('model'),
                        modelId: $(object).data('model-id'),
                        modelType: $(object).data('model-type'),
                        fullData: $(object).data('full-data'),
                        column: $(object).data('column'),
                        dependsOnValues: dependsOnValues
                    },
                    dataType: 'json',
                    context: object,
                    success: function (data) {
                        RelationInputsManager.typeaheadList[relation] = data;
                        $(object).relationPopulateTypeheadFromList();
                        $(object).riSyncronizeData();
                        $(object).riAddRelationEvents();
                        $(object).riShowOrHide();
                    }
                });
            }
        });
    };

    $.fn.riAddRemoveSelectedEvents = function() {
        $(this).each(function(index, object) {
            var relationFieldName = $(object).data('field');
            if(typeof $(object).data('level') !== 'undefined') {
                relationFieldName += '-' + $(object).data('level');
            }

            var relation = $('input[id=' + relationFieldName + ']').data('relation');

            $(object).click(function() {
                if(typeof RelationInputsManager.typeaheadListSelected[relation] !== 'undefined') {
                    var index = RelationInputsManager.typeaheadListSelected[relation].inArray($(object).data('id'), 'id');
                    RelationInputsManager.typeaheadListSelected[relation].splice(index, 1);
                }

                $(object).parent().remove();

                $('input[id=' + $('[id=' + relationFieldName + ']').data('depending') + ']').riPopulateItems();
                return false;
            });

            var index = RelationInputsManager.typeaheadList[relation].inArray($(object).data('id'), 'id');
            var selectedItem = RelationInputsManager.typeaheadList[relation][index];

            if(typeof RelationInputsManager.typeaheadListSelected[relation] === 'undefined') {
                RelationInputsManager.typeaheadListSelected[relation] = [];
            }
            RelationInputsManager.typeaheadListSelected[relation].push(selectedItem);
        });
    };


    $.fn.riAddDependingEvents = function() {
        $(this).each(function(index, dependingObject) {
            $.each($(dependingObject).data('depends-on'), function( index, dependsOnField ){
                $('[id=' + dependsOnField + ']').data('depending', $(dependingObject).attr('id'));
                $('[id=' + dependsOnField + ']').change(function() {
                    dependingObject.riPopulateItems();
                });
            });
        });
    };

    $.fn.riPopulateItems = function() {
        $(this).each(function(index, object) {
            $(object).empty();

            var dependsOnValues = {};
            $.each($(object).data('depends-on'), function( index, dependsOnField ) {
                dependsOnValues[dependsOnField] = $('[id=' + dependsOnField + ']').riGetValue();
            });

            var relation = $(object).data('relation');

            $.ajax({
                type: 'GET',
                url: '/admin/model/typeahead/model-relation-items',
                data: {
                    relation: $(object).data('relation'),
                    model: $(object).data('model'),
                    modelId: $(object).data('model-id'),
                    modelType: $(object).data('model-type'),
                    fullData: $(object).data('full-data'),
                    column: $(object).data('column'),
                    dependsOnValues: dependsOnValues
                },
                dataType: 'json',
                context: object,
                success: function (data) {
                    RelationInputsManager.typeaheadList[relation] = data;
                    $(object).relationPopulateTypeheadFromList();
                    $(object).riSyncronizeData();
                    $(object).riAddRelationEvents();
                    $(object).riShowOrHide();
                }
            });
        });
    };

    $.fn.riGetValue = function() {
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

    $.fn.riShowOrHide = function() {
        $(this).each(function(index, object) {
            var relation = $(object).data('relation');

            var visible = false;

            if(typeof RelationInputsManager.typeaheadList[relation] !== 'undefined' && RelationInputsManager.typeaheadList[relation].length > 0) {
                visible = true;
            }

            var field = $(object).attr('id');
            if(visible) {
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

    $.fn.riSyncronizeData = function() {
        $(this).each(function(index, object) {
            var relation = $(object).data('relation');

            var selectedId = $('input[type=hidden][id=' + relation + ']').val();
            var index = RelationInputsManager.typeaheadList[relation].inArray(selectedId, 'id');
            if(index === -1) {
                $('input[id=' + relation + '-input]').val('');
                $('input.single-relation[type=hidden][id=' + relation + ']').val('');
            }


            $('a[id=' + relation + '-remove-selected]').each(function(index, removeSelectedObject) {
                var selectedId = $(removeSelectedObject).data('id');

                var index = RelationInputsManager.typeaheadList[relation].inArray(selectedId, 'id');

                if(index === -1) {
                    $(removeSelectedObject).parent().remove();
                } else {
                    if(typeof RelationInputsManager.typeaheadListSelected[relation] === 'undefined') {
                        RelationInputsManager.typeaheadListSelected[relation] = [];
                    }
                    RelationInputsManager.typeaheadListSelected[relation].push(RelationInputsManager.typeaheadList[relation][index]);
                }
            });
        });
    };

    $.fn.riAddRelationEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var relation = $(object).data('relation');

                var index = RelationInputsManager.typeaheadList[relation].inArray($(object).val(), 'name');
                if(index !== -1) {
                    var item = RelationInputsManager.typeaheadList[relation][index];
                    RelationInputsManager.addRelationItem(item.id, object);
                    $('input.single-relation[type=hidden][id=' + relation + ']').val(item.id);
                } else {
                    $('input[id=' + relation + '-input]').val('');
                    $('input.single-relation[type=hidden][id=' + relation + ']').val('');
                }
            });
        });
    };

    RelationInputsManager = {
        initialize: function() {
            RelationInputsManager.typeaheadList = {};
            RelationInputsManager.typeaheadListSelected = {};

            $('input.depending-field').riAddDependingEvents();

            $('input.relation').relationPopulateTypehead();
        },

        addRelationItem: function(relationItemId, object) {
            if($(object).hasClass('.relation-multiple')) {
                $(object).val('');
            }

            if(relationItemId !== null && relationItemId !== '') {
                var relation = $(object).data('relation');

                if(typeof RelationInputsManager.typeaheadListSelected[relation] !== 'undefined') {
                    if(RelationInputsManager.typeaheadListSelected[relation].inArray(relationItemId, 'id') !== -1) {
                        return;
                    }
                }


                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-relation-item',
                    data: {
                        itemId: relationItemId,
                        type: $(object).hasClass('tags-parenting-relation') ? 'tags_parenting' : '',
                        relation: $(object).data('relation'),
                        model: $(object).data('model'),
                        modelId: $(object).data('model-id'),
                        modelType: $(object).data('model-type'),
                        fullData: $(object).data('full-data')
                    },
                    success: function (data) {
                        $('div[id=selected-' + relation + ']').append(data);
                        $('a[data-id=' + relationItemId + ']', $('div[id=selected-' + relation + ']')).riAddRemoveSelectedEvents();
                        if($(object).data('sortable')) {
                            $('div[id=relation-item-' + $(object).attr('id') + '-' + relationItemId + ']').setRelationItemsDraggableAndDroppable();
                        }
                        $('.add-checkbox', '#selected-' + $(object).attr('id')).addAddCheckboxEvents();
                        FormManager.beautifyCheckboxes();
                    }
                });
            }
        }
    };

    RelationInputsManager.initialize();
});