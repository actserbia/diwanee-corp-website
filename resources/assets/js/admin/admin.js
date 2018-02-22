$(document).ready(function() {
    $('form').submit(function () {
        var form = $(this);

        form.find('input[type="checkbox"]').each( function () {
            var checkbox = $(this);
            
            if(checkbox.is(":checked")) {
                checkbox.attr('value', '1');
            } else {
                checkbox.prop('checked', true);
                checkbox.attr('value', '0');
            }
        });
    });
    
    ModelManager.initialize();
    RelationsNodeTagsManager.initialize();
    RelationsManager.initialize();
    RelationsNodeTagsManager.setSelectedValues();
});