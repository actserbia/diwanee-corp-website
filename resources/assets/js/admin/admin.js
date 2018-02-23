$(document).ready(function() {
    ModelManager.initialize();
    RelationsNodeTagsManager.initialize();
    RelationsManager.initialize();
    RelationsNodeTagsManager.setSelectedValues();
    FormManager.beautifyCheckboxes();
});