$(document).ready(function() {
    $.fn.addAddSubtagsEvents = function() {
        $(this).each(function(index, object) {
            $(object).change(function() {
                var selectedTagsIds = [$(object).val()];

                $.ajax({
                    type: 'GET',
                    url: '/admin/model/add-subtags',
                    data: {
                        model: $(object).data('model'),
                        model_id: $(object).data('model-id'),
                        field: $(object).data('relation'),
                        tags_id: selectedTagsIds,
                        level: $(object).data('level')
                    },
                    success: function (data) {
                        $('[id=separator-' + $(object).attr('id') + ']').after(data);
                    }
                });
            });
        });
    };

    $('.tags-relation').addAddSubtagsEvents();
});