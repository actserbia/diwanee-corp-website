$(document).ready(function() {
    $.fn.stPopulateTypeheadFromList = function() {
        $(this).each(function(index, object) {
            var elementType = SirTrevorManager.getElementType(object);
            var filterValue = SirTrevorManager.getFilterValue(object);
            var inputHidden = $(object).parent().find('input[name="item_id"]');
            
            $(object).typeahead('destroy');
            $(object).typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
                source: SirTrevorManager.typeaheadList[elementType][filterValue],
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

                $('.diwanee-element-item').each(function(index, object) {
                    if(!SirTrevorManager.setElementItemValidity($(object))) {
                        isValid = false;
                    }
                });

                return isValid;
            });
            
            SirTrevorManager.filterList = {};
            SirTrevorManager.typeaheadList = {};
        },
        
        getElementType: function(object) {
            return $(object).parent().parent().parent().data('type');
        },

        getFilterValue: function(object) {
            return $(object).parent().find('select').val();
        },

        setElementItemValidity: function(object) {
            object.removeClass('has-error');
            $('span.help-block', object).empty();
            
            var elementType = $(object).parent().parent().data('type');
            
            var itemName = $('input[name="item_name"]', object).val();
            var itemId = $('input[name="item_id"]', object).val();
            var filterValue = $('select[name="filter"]', object).val();
            
            var itemFromListById = SirTrevorManager.typeaheadList[elementType][filterValue].inArray(itemId, 'id');
            var itemFromListByName = SirTrevorManager.typeaheadList[elementType][filterValue].inArray(itemName, 'name');
            
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
        
        setDiwaneeElementItemContent: function(blockId, nodeData, hasFilterSelect) {
            var elementType = $('div[id=' + blockId + ']').data('type');
            var itemName = (nodeData.item_name !== undefined) ? nodeData.item_name : '';
            
            var content = '';
            if(typeof Localization[$('html').attr('lang')].diwanee_elements_labels[elementType] !== 'undefined') {
                content += '<label>' + Localization[$('html').attr('lang')].diwanee_elements_labels[elementType] + '</label>';
            }
            if(hasFilterSelect) {
                content += SirTrevorManager.getDiwaneeElementNodeTypeSelectContent(elementType, nodeData);
            }
            
            content += '<input type="text" name="item_name" data-provide="typeahead" value="' + itemName + '">';
            content += '<input type="hidden" name="item_id" value="' + nodeData.item_id + '">';
            $('.diwanee-element-item', $('div[id=' + blockId + ']'))[0].innerHTML = content;
            
            $('select[name=filter]', $('div[id=' + blockId + ']')).change(function(){
                SirTrevorManager.addTypeahead(blockId);
            });
            $('select[name=filter]', $('div[id=' + blockId + ']')).val(nodeData.filter).trigger('change');
        },
        
        getDiwaneeElementNodeTypeSelectContent: function(elementType, nodeData) {
            var list = '<select name="filter">';
            
            list += '<option value="">' + Localization[$('html').attr('lang')].node_type_select__choose + '</option>';
            $.each(SirTrevorManager.filterList[elementType], function(i, element) {
                list += '<option value="' + element.id + '">' + element.name + '</option>';
            });
            
            list += '</select>';
            
            return list;
        },
        
        addTypeahead: function(blockId) {
            var elementType = $('div[id=' + blockId + ']').data('type');
            
            $('input[name=item_name]', $('div[id=' + blockId + ']')).each(function(index, object) {
                var filterValue = SirTrevorManager.getFilterValue(object);
                
                if(typeof SirTrevorManager.typeaheadList[elementType][filterValue] !== 'undefined') {
                    $(object).stPopulateTypeheadFromList();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/model/typeahead/diwanee-element/items',
                        data: {
                           elementType: elementType.replace(' ', '_'),
                           filterValue: filterValue
                        },
                        dataType: 'json',
                        context: object,
                        success: function (data) {
                            SirTrevorManager.typeaheadList[elementType][filterValue] = data;
                            $(object).stPopulateTypeheadFromList();
                        }
                    });
                }
            });
        }
    };
    
    SirTrevorManager.initialize();
});

function setDiwaneeElementItemContent(blockId, nodeData, hasFilterSelect) {
    var elementType = $('div[id=' + blockId + ']').data('type');
    
    if(typeof SirTrevorManager.typeaheadList[elementType] === 'undefined') {
        SirTrevorManager.typeaheadList[elementType] = {};
    }
    
    if(!hasFilterSelect || typeof SirTrevorManager.filterList[elementType] !== 'undefined') {
        SirTrevorManager.setDiwaneeElementItemContent(blockId, nodeData, hasFilterSelect);
    } else {
        $.ajax({
            dataType: 'json',
            url: '/admin/model/typeahead/diwanee-element/items-filters',
            data: {
                elementType: elementType.replace(' ', '_')
            },
            success: function (data) {
                SirTrevorManager.filterList[elementType] = data;
                SirTrevorManager.setDiwaneeElementItemContent(blockId, nodeData, hasFilterSelect);
            }
        });
    }
}