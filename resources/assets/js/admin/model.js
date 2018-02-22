$(document).ready(function() {
    $.fn.addAddCheckboxEvents = function() {
        $(this).each(function(index, object) {
            $(object).click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-checkbox',
                    data: {
                        data: $(object).data()
                    },
                    success: function (data) {
                        $(object).parent().before(data);
                        $('.remove-checkbox').addRemoveCheckboxEvents();
                        $(object).setAddCheckboxVisibility();
                    }
                });
            });
        });
    };
    
    $.fn.addRemoveCheckboxEvents = function() {
        $(this).each(function(index, object) {
            $(object).click(function() {
                $(object).parent().remove();
                $('.add-checkbox').setAddCheckboxVisibility();
                return false;
            });
        });
    };
    
    $.fn.setAddCheckboxVisibility = function() {
        $(this).each(function(index, object) {
            if($('input.checkbox-item[data-model-id=' + $(object).data('model-id') + ']').length >= maximumTagsLevelsCount) {
                $(object).attr('style', 'display:none;');
            } else {
                $(object).attr('style', 'display:block;');
            }
        });
    };
    
    ModelManager = {
        initialize: function() {
            $('.add-checkbox').addAddCheckboxEvents();
            $('.remove-checkbox').addRemoveCheckboxEvents();
            
            $('.add-checkbox').setAddCheckboxVisibility();
        }
    };
});