$(document).ready(function() {
    $('#nodes-list #nodeType').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/nodes-list',
            data: {
                node_type_id: $(this).val()
            },
            success: function (data) {
                $('#nodes-list').html(data);
                TableManageButtons.init();
            }
        });
    });

    $('#node-create #nodeType').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/node-fields',
            data: {
                node_type_id: $(this).val()
            },
            success: function (data) {
                $('#node-fields').html(data);
            }
        });
    });
});