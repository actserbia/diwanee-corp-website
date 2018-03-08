$(document).ready(function() {
    $.fn.populateStartDatePickers = function() {
        $(this).each(function(index, object) {
            $(object).datetimepicker({
                dayViewHeaderFormat: "MMMM YYYY",
                format: "YYYY-MM-DD",
                locale: $('html').attr('lang')
            });

            $(object).on("dp.change", function (e) {
                $('#' + $(object).attr('id').replace('-start-', '-end-')).data("DateTimePicker").minDate(e.date);
            });
        });
    };
    
    $.fn.populateEndDatePickers = function() {
        $(this).each(function(index, object) {
            $(object).datetimepicker({
                dayViewHeaderFormat: "MMMM YYYY",
                format: "YYYY-MM-DD",
                locale: $('html').attr('lang'),
                useCurrent: false
            });

            $(object).on("dp.change", function (e) {
                $('#' + $(object).attr('id').replace('-end-', '-start-')).data("DateTimePicker").maxDate(e.date);
            });
        });
    };

    $.fn.populateTypeheadFromList = function() {
        $(this).each(function(index, object) {
            $(object).typeahead({
                hint: true,
                highlight: true,
                minLength: 1,
                source: SearchManager.typeaheadList[$(object).attr('id')]
            });
        });
    };
    
    $.fn.populateTypehead = function() {
        var fields = [];
        $(this).each(function(index, object) {
            if(fields.inArray($(object).attr('id'), 'name') === -1 && !($(object).attr('id') in SearchManager.typeaheadList)) {
                fields.push(new SearchManager.TypeheadField($(object).attr('id'), $(object).data()));
            }
        });
        
        SearchManager.populateTypeheadListForFields(fields, this);
    };
    
    
    SearchManager = {
        initialize: function() {
            $('#search_form #model_type').change(function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/search/nodes-list',
                    data: {
                        model_type_id: $(this).val()
                    },
                    dataType: 'json',
                    context: this,
                    success: function (data) {
                        $('#filters').empty();
                        $('#search').empty();
                        $.each(data, function (index, item) {
                            $('#search').append($('<option>', {
                                value: item.value,
                                text: item.text
                            }));
                        });
                        $('#search').data('modelType', $(this).val());
                    }
                });
            });
            
            $('#search').change(function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/search/add-filter',
                    data: {
                        field: $(this).val(),
                        data: $(this).data()
                    },
                    context: this,
                    success: function (data) {
                        $('#filters').append(data);
                        $('option[value="' + $(this).val() + '"]', $(this)).attr('disabled', 'disabled');
                        $(this).val('');
                        SearchManager.attachAddInputFieldEvent($('#filters').children('.form-group').last());
                        SearchManager.attachDateFieldsEvents($('#filters').children('.form-group').last());
                    }
                });
            });
            
            SearchManager.attachAddInputFieldEvent($('#filters'));
            SearchManager.attachInputFieldsEvents($('#filters'));
            SearchManager.attachDateFieldsEvents($('#filters'));
        },
        
        attachAddInputFieldEvent: function(parent) {
            $('.search-add-input a', parent).each(function(index, object) {
                $(object).click(function() {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/search/add-input',
                        data: {
                            data: $(object).data()
                        },
                        context: object,
                        success: function (data) {
                            $(object).parent().parent().find('.search-elements').append(data);
                            SearchManager.attachInputFieldsEvents($(object).parent().parent().find('.search-elements').children('.search-field-item').last());
                        }
                    });
                });
            });
        },
        
        attachInputFieldsEvents: function(parent) {
            $('.search-remove', parent).click(function() {
                $(this).parent().remove();
                return false;
            });

            $('input.typeahead', parent).populateTypehead();
        },
        
        attachDateFieldsEvents: function(parent) {
            $('div[id^=datetimepicker-start-date-]', parent).populateStartDatePickers(); 
            $('div[id^=datetimepicker-end-date-]', parent).populateEndDatePickers();
        },
        
        TypeheadField: function(name, data) {
            this.name = name;
            this.data = data;
        },
        
        populateTypeheadListForFields: function(fields, object) {
            if(fields.length > 0) {
                var field = fields.pop();
                
                $.ajax({
                    type: 'GET',
                    url: '/admin/search/typeahead',
                    data: {
                        data: field.data,
                        param: field.name
                    },
                    success: function (data) {
                        SearchManager.typeaheadList[field.name] = data;
                        SearchManager.populateTypeheadListForFields(fields, object);
                    }
                });
            } else {
                $(object).populateTypeheadFromList();
            }
        }
    };
    SearchManager.typeaheadList = {};
      
});