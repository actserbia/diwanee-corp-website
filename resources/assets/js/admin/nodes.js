$(document).ready(function() {
    $('#nodes-list #node_type').change(function() {
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

    $('#node-create #node_type').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/node-fields',
            data: {
                node_type_id: $(this).val()
            },
            success: function (data) {
                $('#node-fields').html(data);

                $('.node-tags-relation').addAddSubtagsEvents();
                $('.remove-selected').addRemoveSubtagsEvents();

                $('.relation-multiple').addAddRelationItemSelectedEvents();
                $('.remove-selected').addRemoveSelectedEventsAndDisableSelected();

                $('.depending-field').addDependingEvents();
                $('.relation-multiple').showOrHide();

                $('.relation-item[draggable=true]').setRelationItemsDraggableAndDroppable();
            }
        });
    });
});