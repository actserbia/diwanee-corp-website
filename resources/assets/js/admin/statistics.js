$(document).ready(function() {
    $('#statistics_form #model_type').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/statistics/items-list',
            data: {
                model_type_id: $(this).val(),
                data: $(this).data()
            },
            dataType: 'json',
            context: this,
            success: function (data) {
                $('#statistic').html('');
                $.each(data, function (index, item) {
                    $('#statistic').append($('<option>', {
                        value: item.value,
                        text: item.text
                    }));
                });
                $('#statistic').data('modelType', $(this).val());
            }
        });
    });
});