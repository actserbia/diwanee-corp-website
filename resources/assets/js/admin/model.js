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
                        $(object).parent().parent().find('div[id=checkbox-list]').append(data);
                        $('.remove-checkbox').addRemoveCheckboxEvents();
                        $(object).setAddCheckboxVisibility();
                        $('input.checkbox-item').beautifyInputField();
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
            if($('input.checkbox-item[data-type-id=' + $(object).data('type-id') + ']').length >= $(object).data('maximum-count')) {
                $(object).fadeOut('slow');
            } else {
                $(object).fadeIn('slow');
            }
        });
    };
    
    $.fn.setHasLevelsCheckboxEvents = function() {
        $(this).each(function(index, object) {
            $(object).on('ifChanged', function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-checkbox',
                    data: {
                        data: $(object).data(),
                        removeCheckbox: $(object).is(":checked") ? 1 : 0
                    },
                    success: function (data) {
                        $(object).parent().parent().find('div[id=checkbox-list]').html(data);
                        $('input.checkbox-item').beautifyInputField();
                        if($(object).is(":checked")) {
                            $('div.checkbox-add').fadeIn('fast');
                        } else {
                            $('div.checkbox-add').fadeOut('fast');
                        }
                    }
                });
            });
        });
    };
    
    
    
    $.fn.populateDatePickers = function() {
        $(this).each(function(index, object) {
            $(object).datetimepicker({
                dayViewHeaderFormat: "MMMM YYYY",
                format: "YYYY-MM-DD",
                locale: $('html').attr('lang')
            });
        });
    };
    
    ModelManager = {
        initialize: function() {
            $('input.has-levels[type=checkbox]').setHasLevelsCheckboxEvents();
            
            $('.add-checkbox').addAddCheckboxEvents();
            $('.remove-checkbox').addRemoveCheckboxEvents();
            
            $('.add-checkbox').setAddCheckboxVisibility();
            
            $('.date').populateDatePickers(); 
        }
    };
});