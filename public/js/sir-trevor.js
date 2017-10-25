$(document).ready(function() {
    SirTrevor.setDefaults({ uploadUrl: "/sirtrevor/upload-image",  iconUrl: "/asset/sirtrevorjs/sir-trevor-icons.svg" });

    window.editor = new SirTrevor.Editor({
        el:document.querySelector('.sir-trevor'),
        defaultType: 'Text',
        blockTypes: [ 'Text', 'Heading', 'List', 'DiwaneeImage', 'Video', 'SliderImage', 'Quote' ],
        formatBar: {
	      commands: [{
	        name: "Bold",
	        title: "bold",
	        iconName: "fmt-bold",
	        cmd: "bold",
	        keyCode: 66,
	        text: "B"
	      }, {
	        name: "Italic",
	        title: "italic",
	        iconName: "fmt-italic",
	        cmd: "italic",
	        keyCode: 73,
	        text: "i"
	      }, {
	        name: "Link",
	        title: "link",
	        iconName: "fmt-link",
	        cmd: "linkPrompt",
	        text: "link"
	      }, {
	        name: "Unlink",
	        title: "unlink",
	        iconName: "fmt-unlink",
	        cmd: "unlink",
	        text: "link"
	      }/*, {
	        name: "Heading",
	        title: "heading",
	        iconName: "fmt-heading",
	        cmd: "heading",
	        text: "heading"
	      }, {
	        name: "Quote",
	        title: "quote",
	        iconName: "fmt-quote",
	        cmd: "quote",
	        text: "quote"
	      }*/]
	    }
    });

});