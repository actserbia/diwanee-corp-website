$(document).ready(function() {
    ModelManager.initialize();
    RelationsNodeTagsManager.initialize();
    RelationsManager.initialize();
    RelationsNodeTagsManager.setSelectedValues();
    FormManager.beautifyCheckboxes();
});

function addTypeahead(type_id) {
    $('.typeahead').each(function() {
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
                    source: data
                });
                $(this).removeClass('typeahead');
            }
        });
    });

}
