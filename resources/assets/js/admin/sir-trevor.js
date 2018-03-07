$(document).ready(function() {
    $.fn.stPopulateTypeheadFromList = function() {
        $(this).each(function(index, object) {
            var listName = SirTrevorManager.getListName(object);
            var typeId = SirTrevorManager.getNodeTypeId(object);
            var inputHidden = $(object).parent().find('input[name="item_id"]');
            
            $(object).typeahead('destroy');
            $(object).typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
                source: SirTrevorManager.typeaheadList[listName][typeId],
                afterSelect: function(selected) {
                    inputHidden.val(selected.id);
                }
            });
        });
    };
    
    SirTrevorManager = {
        initialize: function() {
            $('form.node-form .btn-success').click(function() {
                var isValid = true;

                $('.type_options').each(function(index, object) {
                    if(!SirTrevorManager.setElementItemValidity(SirTrevorManager.DiwaneeNode, $(object))) {
                        isValid = false;
                    }
                });

                $('.st-nodes-list-block').each(function(index, object) {
                    if(!SirTrevorManager.setElementItemValidity(SirTrevorManager.DiwaneeList, $(object))) {
                        isValid = false;
                    }
                });

                return isValid;
            });
        },
        
        getListName: function(object) {
            if($(object).parent().hasClass('type_options')) {
                return SirTrevorManager.DiwaneeNode;
            } else if($(object).parent().hasClass('st-nodes-list-block')) {
                return SirTrevorManager.DiwaneeList;
            }

            return '';
        },

        getNodeTypeId: function(object) {
            if($(object).parent().hasClass('type_options')) {
                return $(object).parent().find('select').val();
            }

            return 'global';
        },

        getAjaxUrl: function(listName, typeId) {
            return SirTrevorManager.settings[listName].ajaxUrl.replace('[TYPE_ID]', typeId);
        },

        setElementItemValidity: function(listName, object) {
            $(object).removeClass('has-error');
            $('span.help-block', object).empty();
            
            var itemName = $('input[name="item_name"]', object).val();
            var itemId = $('input[name="item_id"]', object).val();
            var typeId = $('select[name="type"]', object).val();
            
            var itemFromListById = SirTrevorManager.typeaheadList[listName][typeId].inArray(itemId, 'id');
            var itemFromListByName = SirTrevorManager.typeaheadList[listName][typeId].inArray(itemName, 'name');
            
            var message = Localization[$('html').attr('lang')].element_item_not_valid;
            if(itemFromListById === -1 || itemFromListByName === -1 || itemFromListById.id !== itemFromListByName.id) {
                if(!object.hasClass('has-error')) {
                    object.addClass('has-error');
                }
                object.append('<span class="help-block">' + message + '</span>');
                return false;
            }
            
            return true;
        },
        
        setDiwaneeElementItemContentFromList: function(blockId, nodeData, elementType) {
            var itemName = (nodeData.item_name !== undefined) ? nodeData.item_name : '';
            
            var content = '';
            if(SirTrevorManager.settings[elementType].hasNodeTypeSelect) {
                content += SirTrevorManager.getDiwaneeElementNodeTypeSelectContent(blockId, nodeData);
            }
            if(typeof Localization[$('html').attr('lang')].diwanee_elements_labels[elementType] !== 'undefined') {
                content += '<label>' + Localization[$('html').attr('lang')].diwanee_elements_labels[elementType] + '</label>';
            }
            
            content += '<input type="text" name="item_name" id="node-' + blockId + '"' + 'data-provide="typeahead" class="typeahead node" value="' + itemName + '">';
            content += '<input type="hidden" name="item_id" class="node-id" id="node-id-' + blockId + '"' + ' value="' + nodeData.item_id + '">';
            $(SirTrevorManager.settings[elementType].container, $('div[id=' + blockId + ']'))[0].innerHTML = content;
            
            SirTrevorManager.addTypeahead(blockId);
        },
        
        getDiwaneeElementNodeTypeSelectContent: function(blockId, nodeData) {
            var list = '<select id="node-type-' + blockId + '" name="type" onChange="SirTrevorManager.addTypeahead(\'' + blockId + '\')">';
            list += '<option value="0">' + Localization[$('html').attr('lang')].node_type_select__choose + '</option>';
            
            $.each(SirTrevorManager.nodeTypesList, function(i, element) {
                list += '<option value="' + element.id + '"';
                if(element.id == nodeData.type) {
                    list += ' selected';
                }
                list += '>' + element.name + '</option>';
            });
            
            list += '</select>';
            
            return list;
        },
        
        addTypeahead: function(blockId) {
            $('.typeahead', $('div[id=' + blockId + ']')).each(function(index, object) {
                var listName = SirTrevorManager.getListName(object);
                var typeId = SirTrevorManager.getNodeTypeId(object);
                if(typeof SirTrevorManager.typeaheadList[listName][typeId] !== 'undefined') {
                    $(object).stPopulateTypeheadFromList();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: SirTrevorManager.getAjaxUrl(listName, typeId),
                        dataType: 'json',
                        context: object,
                        success: function (data) {
                            SirTrevorManager.typeaheadList[listName][typeId] = data;
                            $(object).stPopulateTypeheadFromList();
                        }
                    });
                }
            });
        }
    };
    
    SirTrevorManager.nodeTypesList = [];

    SirTrevorManager.typeaheadList = {};
    SirTrevorManager.typeaheadList['diwanee node'] = {};
    SirTrevorManager.typeaheadList['diwanee node'][0] = [];
    SirTrevorManager.typeaheadList['diwanee list'] = {};
    
    SirTrevorManager.settings = {
        "diwanee node": {
            "ajaxUrl": "/api/nodes/typeahead/[TYPE_ID]",
            "hasNodeTypeSelect": true,
            "container": ".type_options"
        },
        
        "diwanee list": {
            "ajaxUrl": "/api/lists/typeahead",
            "hasNodeTypeSelect": false,
            "container": ".st-nodes-list-block"
        }
    };
    
    SirTrevorManager.DiwaneeNode = 'diwanee node';
    SirTrevorManager.DiwaneeList = 'diwanee list';
    
    SirTrevorManager.initialize();
});

function setDiwaneeElementItemContent(blockId, nodeData, elementType) {
    if(!SirTrevorManager.settings[elementType].hasNodeTypeSelect || SirTrevorManager.nodeTypesList.length) {
        SirTrevorManager.setDiwaneeElementItemContentFromList(blockId, nodeData, elementType);
    } else {
        $.ajax({
            dataType: 'json',
            url: '/api/types/typeahead',
            success: function (types) {
                SirTrevorManager.nodeTypesList = types;
                SirTrevorManager.setDiwaneeElementItemContentFromList(blockId, nodeData, elementType);
            }
        });
    }
}