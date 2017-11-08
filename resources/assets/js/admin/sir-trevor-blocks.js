"use strict";

var _template = require('lodash.template');

SirTrevor.Locales.en.blocks['diwanee image'] = {'title': 'Image'};
SirTrevor.Blocks.DiwaneeImage = SirTrevor.Blocks.Image.extend({
    type: "diwanee image",
    icon_name: 'image',

    textFields: {
        seo_name: {sel: 'seoname', required: false},
        seo_alt: {sel: 'seoalt', required: false},
        caption: {sel: 'caption', required: false},
        copyright: {sel: 'copyright', required: false}
    },

    loadData: function(data){
        // Create our image tag
        this.editor.innerHTML = '';
        var image = document.createElement("img");
        image.setAttribute("src", data.file.url);
        this.editor.appendChild(image);
        this.imageAddition(data);
    },

    onDrop: function(transferData){
        var file = transferData.files[0],
            urlAPI = (typeof URL !== "undefined") ? URL : (typeof webkitURL !== "undefined") ? webkitURL : null;

        // Handle one upload at a time
        if (/image/.test(file.type)) {
            this.loading();
            // Show this image on here
            this.inputs.style.display = 'none';
            this.editor.innerHTML = '';
            
            var image = document.createElement("img");
            image.setAttribute("src", urlAPI.createObjectURL(file));
            this.editor.appendChild(image);

            this.imageAddition();

            this.editor.style.display = '';

            this.uploader(
                file,
                function(data) {
                    this.setData(data);
                    this.ready();
                },
                function(error) {
                    this.addMessage(i18n.t('blocks:image:upload_error'));
                    this.ready();
                }
            );
        }
    },

    imageAddition: function(data){

        var that = this;
        var divAddition = document.createElement("div");
        divAddition.setAttribute("class", "image-additional");
        var a = document.createElement("a");
        a.setAttribute("data-toggle", "collapse");
        a.setAttribute("data-target", "#image-add"+that.blockID);
        a.setAttribute("class", "collapsed");
        a.innerHTML = "Image data";
        var i = document.createElement("i");
        i.setAttribute("class", "fa fa-chevron fa-fw");
        a.appendChild(i);
        divAddition.appendChild(a);

        var divCollapsable = document.createElement("div");
        divCollapsable.setAttribute("id", "image-add"+that.blockID);
        divCollapsable.setAttribute("class", "collapse");

        $.each(this.textFields, function(i, element) {
            var div = document.createElement("div");
            div.setAttribute('class', 'image_input_text');

            var field = document.createElement("input");
            field.setAttribute("type", "text");
            field.setAttribute("name", element.sel);
            field.setAttribute("class", element.sel);
            if(data !== undefined) {
                field.setAttribute("value", data[element.sel]);
            }
            if(element.required) {
                field.setAttribute("required", true);
            }
            field.setAttribute("maxlength", 90);
            var label = document.createElement("label");
            var t = document.createTextNode(i);
            label.setAttribute("for", i);
            label.appendChild(t);
            div.appendChild(label);
            div.appendChild(field);

            divCollapsable.appendChild(div);
        });
        divAddition.appendChild(divCollapsable);
        that.editor.appendChild(divAddition);

    }

});

SirTrevor.Locales.en.blocks['slider image'] = {'title': 'Slider Image'};
SirTrevor.Blocks.SliderImage = SirTrevor.Blocks.DiwaneeImage.extend({
    type: "slider image",
    icon_name: 'iframe',        //'sliderimage',

    loadData: function(data){
        // Create our image tag
        this.editor.innerHTML = '';

        var div = document.createElement("h4");
        var t = document.createTextNode("Slider Image");
        div.appendChild(t);
        this.editor.appendChild(div);

        var image = document.createElement("img");
        image.setAttribute("src", data.file.url);
        this.editor.appendChild(image);

        this.imageAddition(data);
    },

    onDrop: function(transferData){
        var file = transferData.files[0],
            urlAPI = (typeof URL !== "undefined") ? URL : (typeof webkitURL !== "undefined") ? webkitURL : null;

        // Handle one upload at a time
        if (/image/.test(file.type)) {
            this.loading();
            // Show this image on here
            this.inputs.style.display = 'none';
            this.editor.innerHTML = '';
            
            var div = document.createElement("h4");
            var t = document.createTextNode("Slider Image");
            div.appendChild(t);
            this.editor.appendChild(div);

            var image = document.createElement("img");
            image.setAttribute("src", urlAPI.createObjectURL(file));
            this.editor.appendChild(image);

            this.imageAddition();

            this.editor.style.display = '';

            this.uploader(
                file,
                function(data) {
                    this.setData(data);
                    this.ready();
                },
                function(error) {
                    this.addMessage(i18n.t('blocks:image:upload_error'));
                    this.ready();
                }
            );
        }
    }
});

SirTrevor.Locales.en.blocks['diwanee video'] = {'title': 'Video'};
SirTrevor.Blocks.DiwaneeVideo = SirTrevor.Blocks.Video.extend({
    type: 'diwanee video',
    icon_name: 'video',

    providers: {
        vimeo: {
            regex: /(?:http[s]?:\/\/)?(?:www.)?vimeo\.co(?:.+(?:\/)([^\/].*)+$)/,
            html: "<iframe src=\"<%= protocol %>//player.vimeo.com/video/<%= remote_id %>?title=0&byline=0\" width=\"580\" height=\"320\" frameborder=\"0\"></iframe>"
        },
        youtube: {
            //regex: /^.*(?:(?:youtu\.be\/)|(?:youtube\.com)\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*)/,
            regex: /(?:http[s]?:\/\/)?(?:www.)?youtu\.be|youtube\.com\/v\/|u\/\w\/|embed\/|watch\?v=([^\W]*)/,
            html: "<iframe src=\"<%= protocol %>//www.youtube.com/embed/<%= remote_id %>\" width=\"580\" height=\"320\" frameborder=\"0\" allowfullscreen></iframe>"
        },
        vine: {
            regex: /(?:http[s]?:\/\/)?(?:www.)?vine.co\/v\/([^\W]*)/,
            html: "<iframe class=\"vine-embed\" src=\"<%= protocol %>//vine.co/v/<%= remote_id %>/embed/simple\" width=\"<%= width %>\" height=\"<%= width %>\" frameborder=\"0\"></iframe><script async src=\"http://platform.vine.co/static/scripts/embed.js\" charset=\"utf-8\"></script>",
            square: true
        },
        dailymotion: {
            regex: /(?:http[s]?:\/\/)?(?:www.)?dai(?:.ly|lymotion.com\/video)\/([^\W_]*)/,
            html: "<iframe src=\"<%= protocol %>//www.dailymotion.com/embed/video/<%= remote_id %>\" width=\"580\" height=\"320\" frameborder=\"0\"></iframe>"
        },
        kaltura: {
            regex: /([^\W]*)/,
            html: "<iframe src=\"http://www.kaltura.com/p/<%= settings.partner_id %>/sp/<%= settings.partner_id %>00/embedIframeJs/uiconf_id/<%= settings.uiconf_id %>/partner_id/<%= settings.partner_id %>?iframeembed=true&playerId=<%= settings.player_id %>&entry_id=<%= remote_id %>\" width=\"580\" height=\"320\"></iframe>",
        }
    },
    
    loadData: function loadData(data) {
    
	if (!this.providers.hasOwnProperty(data.source)) {
	    return;
	}

	var source = this.providers[data.source];

	var protocol = window.location.protocol === "file:" ? "http:" : window.location.protocol;

	var aspectRatioClass = source.square ? 'with-square-media' : 'with-sixteen-by-nine-media';

	this.editor.classList.add('st-block__editor--' + aspectRatioClass);
	
        this.editor.innerHTML = _template(source.html, {
	    protocol: protocol,
	    remote_id: data.remote_id,
	    width: this.editor.style.width, // for videos like vine
            settings: SirTrevor.providers[data.source]
	});
    },

    handleDropPaste: function(url){
        for(var key in this.providers) {
            if (!this.providers.hasOwnProperty(key)) { continue; }

            var videoData = this.matchVideoProvider(this.providers[key], key, url);
            if (_.isUndefined(videoData.remote_id)) {
                continue;
            } else {
                this.setAndLoadData(videoData);
                break;
            }
        }
     }
});




    SirTrevor.Blocks.Heading = SirTrevor.Blocks.Heading.extend({
        toolbarEnabled: true
    });
    
    SirTrevor.Blocks.Quote = SirTrevor.Blocks.Quote.extend({
        toolbarEnabled: true
    });

