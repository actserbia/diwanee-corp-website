$(document).ready(function() {
    ModelManager.initialize();
    RelationsTagsParentingManager.initialize();
    RelationsManager.initialize();
    RelationsTagsParentingManager.setSelectedValues();
    FormManager.beautifyCheckboxes();
});

function addTypeahead() {
    $('.typeahead').each(function() {
        var type_id = $(this).parent().find('select  > option:selected').val();
        var input_hidden = $(this).parent().find('input[name="id_node"]');
        $.ajax({
            type: 'GET',
            url: '/api/nodes/typeahead/'+type_id,
            dataType: 'json',
            context: this,
            success: function (data) {
                $(this).typeahead('destroy');
                $(this).typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 1,
                    source: data,
                    afterSelect: function(selected) {
                        input_hidden.val(selected.id);
                    }
                });
                $(this).removeClass('typeahead');
            }
        });
    });
}
