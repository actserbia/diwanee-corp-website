$(document).ready(function() {
    ModelManager.initialize();
    RelationsTagsParentingManager.initialize();
    RelationsManager.initialize();
    RelationsTagsParentingManager.setSelectedValues();
    SearchManager.initialize();
    FormManager.beautifyInputFields();
});