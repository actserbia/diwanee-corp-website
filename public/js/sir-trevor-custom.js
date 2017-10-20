"use strict";

SirTrevor.Blocks.SliderImage = SirTrevor.Block.extend({
    type: "slider image",

    droppable: true,
    uploadable: true,

    icon_name: 'iframe',        //'sliderimage',
    textFields: {
        seo_name: {sel: 'seoname', required: false},
        seo_alt: {sel: 'seoalt', required: false},
        caption: {sel: 'caption', required: false},
        copyright: {sel: 'copyright', required: false}
    },

    loadData: function(data){
        console.log(data.file.url);
        // Create our image tag
        this.editor.innerHTML = '';
        
        var div = document.createElement("div");
        var t = document.createTextNode("Slider Image");
        div.appendChild(t);
        this.editor.appendChild(div);
        
        var div = document.createElement("div");
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
            
            var div = document.createElement("div");
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
    },

    imageAddition: function(data){
        console.log(data);
        var that = this;
        
        var divAddition = document.createElement("div");
        divAddition.setAttribute("class", "image-additional");
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

            divAddition.appendChild(div);
        });
        that.editor.appendChild(divAddition);

    }

});


SirTrevor.Blocks.DiwaneeImage = SirTrevor.Blocks.Image.extend({
    type: "diwanee image",
    title: function() {
        return "image";
    },

    droppable: true,
    uploadable: true,

    icon_name: 'image',
    textFields: {
        seo_name: {sel: 'seoname', required: false},
        seo_alt: {sel: 'seoalt', required: false},
        caption: {sel: 'caption', required: false},
        copyright: {sel: 'copyright', required: false}
    },

    loadData: function(data){
        console.log(data.file.url);
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
        console.log(data);
        var that = this;
        
        var divAddition = document.createElement("div");
        divAddition.setAttribute("class", "image-additional");
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

            divAddition.appendChild(div);
        });
        that.editor.appendChild(divAddition);

    }


});


    SirTrevor.Blocks.Heading = SirTrevor.Blocks.Heading.extend({
        toolbarEnabled: true
    });
    
    SirTrevor.Blocks.Quote = SirTrevor.Blocks.Quote.extend({
        toolbarEnabled: true
    });

