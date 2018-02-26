$(document).ready(function() {
    var typeaheadList = {};

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

    addEvents();

    function addEvents() {
        $('.search-add-input a').each(function(index, object) {
            if(typeof($(object).data('events')) === 'undefined') {
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
                            addEvents();
                        }
                    });
                });
                $(object).data('events', 'set');
            }
        });


        $('.search-remove').click(function() {
            $(this).parent().remove();
        });
        
        $('.typeahead').each(function() {
            if($(this).attr('id') in typeaheadList) {
                $(this).addTypehead();
            } else {
                $.ajax({
                    type: 'GET',
                    url: '/admin/search/typeahead',
                    data: {
                        data: $(this).data(),
                        param: $(this).attr('id')
                    },
                    context: this,
                    success: function (data) {
                        typeaheadList[$(this).attr('id')] = data;
                        $(this).addTypehead();
                    }
                });
            }
        });
        
        $('div[id^=datetimepicker-start-date-]').populateStartDatePickers(); 
        $('div[id^=datetimepicker-end-date-]').populateEndDatePickers();
    }
    
    $.fn.addTypehead = function() {
        $(this).typeahead({
            hint: true,
            highlight: true,
            minLength: 1,
            source: typeaheadList[$(this).attr('id')]
        });
        $(this).removeClass('typeahead');
    };
    
    
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
                $('#search').html('');
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
                addEvents();
            }
        });
    });
});