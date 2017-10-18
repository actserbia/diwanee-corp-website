$(document).ready(function() {
    SirTrevor.setDefaults({ uploadUrl: "/sir-trevor/images",  iconUrl: "/asset/sirtrevorjs/sir-trevor-icons.svg" });

    window.editor = new SirTrevor.Editor({
        el: document.querySelector('.sir-trevor'),
        defaultType: 'Text',
        blockTypes: ['Text', 'List', 'Quote', 'Image', 'Video', 'Heading']
    });
});