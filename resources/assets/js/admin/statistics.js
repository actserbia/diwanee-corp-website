$(document).ready(function() {
    $('#nodes-statistics #node_type').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/statistics/nodes-list',
            data: {
                node_type_id: $(this).val()
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