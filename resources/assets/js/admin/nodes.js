$(document).ready(function() {
    $('#nodes-list #model_type').change(function() {
        window.location = '/admin/nodes?model_type_id=' + $(this).val();
    });

    $('#node-create #model_type').change(function() {
        if($(this).val() !== '') {
            window.location = '/admin/nodes/create?model_type_id=' + $(this).val();
        }
    });
});