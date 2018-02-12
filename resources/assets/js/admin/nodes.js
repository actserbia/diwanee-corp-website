$(document).ready(function() {
    $('#nodeType').change(function() {
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
});