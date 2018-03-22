$(document).ready(function() {
    $('#node-list #node_type').change(function() {
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

                $('select.relation-multiple').addAddRelationItemSelectedEvents();
                $('.remove-selected').addRemoveSelectedEventsAndDisableSelected();

                $('select.relation-multiple').showOrHide();
                $('input.relation-multiple').riShowOrHide();

                $('.relation-item[draggable=true]').setRelationItemsDraggableAndDroppable();

                $('input.relation-multiple', $('#node-list-tags')).relationPopulateTypehead();
            }
        });
    });
});