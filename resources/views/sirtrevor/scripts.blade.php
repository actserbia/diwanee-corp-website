<script type="text/javascript">
$(document).ready(function() {
    SirTrevor.setDefaults({
        uploadUrl: "{{ $config['uploadUrl'] }}",
        iconUrl: "{{ $config['iconUrl'] }}"
    });
    
    SirTrevor.kaltura = {
        partner_id: "{{ $config['videos']['providers']['kaltura']['partner_id'] }}",
        uiconf_id: "{{ $config['videos']['providers']['kaltura']['uiconf_id'] }}",
        player_id: "{{ $config['videos']['providers']['kaltura']['player_id'] }}"
    }

    window.editor = new SirTrevor.Editor({
        el: document.querySelector("{{ $config['editorClass'] }}"),
        defaultType: "{{ $config['defaultType'] }}",
        blockTypes: [ {!! $blockTypes !!} ],
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
</script>
