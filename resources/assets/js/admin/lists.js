$(document).ready(function() {
    $('form#node-list #node_type').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/node-list-tags',
            data: {
                model_type_id: $(this).val()
            },
            success: function (data) {
                $('#node-list-tags').html(data);

                $('.tags-parenting-relation').addAddSubtagsEvents();
                $('.remove-selected').addRemoveSubtagsEvents();

                $('.relation-multiple').addAddRelationItemSelectedEvents();
                $('.remove-selected').addRemoveSelectedEventsAndDisableSelected();

                $('.relation-multiple').showOrHide();

                $('.relation-item[draggable=true]').setRelationItemsDraggableAndDroppable();
            }
        });
    });
});