$(document).ready(function() {
    $('#nodes-list #model_type').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/nodes-list',
            data: {
                model_type_id: $(this).val()
            },
            success: function (data) {
                $('#nodes-list-content').html(data);
                TableManageButtons.init();
            }
        });
    });

    $('#node-create #model_type').change(function() {
        if($(this).val() !== '') {
            window.location = '/admin/nodes/create?model_type_id=' + $(this).val();
        }
    });
});