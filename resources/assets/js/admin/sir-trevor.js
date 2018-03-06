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
        }
    };
    
    SirTrevorManager.typeaheadList = {};
    SirTrevorManager.typeaheadList.diwanee_node = {};
    SirTrevorManager.typeaheadList.diwanee_node[0] = [];
    SirTrevorManager.typeaheadList.diwanee_list = {};
    
    SirTrevorManager.settings = {
        "diwanee_node": {
            "ajaxUrl": "/api/nodes/typeahead/[TYPE_ID]"
        },
        
        "diwanee_list": {
            "ajaxUrl": "/api/lists/typeahead"
        }
    };
    
    SirTrevorManager.DiwaneeNode = 'diwanee_node';
    SirTrevorManager.DiwaneeList = 'diwanee_list';
    
    SirTrevorManager.initialize();
});

function addTypeahead() {
    $('.typeahead').each(function(index, object) {
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