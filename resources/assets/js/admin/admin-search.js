$(document).ready(function() {

    //$('.typeahead').typeahead('destroy');
    
    var typeaheadList = {};

    $('.search-add-input a').click(function() {
        $.ajax({
            type: 'GET',
            url: '/ajax/search/add-input',
            data: { field: $(this).data('field'), model: $(this).data('model') },
            context: this,
            success: function (data) {
                $(this).parent().parent().find('.search-elements').append(data);
                addEventsAndTypeahead();
            }
        });
    });

    //$('.search-date').datepicker({
    //    format: 'yyyy-mm-dd',
    //    language: $('html').attr('lang')
    //});
    
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
    
    $('div[id^=datetimepicker-start-date-]').populateStartDatePickers(); 
    $('div[id^=datetimepicker-end-date-]').populateEndDatePickers();

    $('input').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    addEventsAndTypeahead();

    function addEventsAndTypeahead() {
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
                    data: { model: $(this).data('model'), param: $(this).attr('id') },
                    context: this,
                    success: function (data) {
                        typeaheadList[$(this).attr('id')] = data;
                        $(this).addTypehead();
                    }
                });
            }
        });
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
    
    $.fn.populateExpandedBlocksFormData = function() {
        $(this).each(function(index, object) {
            $('#search_form').append('<input type="hidden" value="' + $(object).attr('id') + '" name="expandedBlocks[]" />');
        });
    };
    
    $('#search_form .btn-success').click(function() {
        $('div[aria-expanded="true"]').populateExpandedBlocksFormData();
    });
});