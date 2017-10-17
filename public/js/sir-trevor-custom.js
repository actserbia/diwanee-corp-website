"use strict";

SirTrevor.Blocks.SliderImage = SirTrevor.Block.extend({
    type: "slider image",

    droppable: true,
    uploadable: true,

    icon_name: 'iframe',        //'sliderimage',

    loadData: function(data){
        console.log(data.file.url);
        // Create our image tag
        this.editor.innerHTML = '';
        var image = document.createElement("img");
        image.setAttribute("src", data.file.url);
        var span = document.createElement("span");
        var t = document.createTextNode("Slider Image");
        span.appendChild(t);
        this.editor.appendChild(image);
        this.editor.appendChild(span);
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
            var span = document.createElement("span");
            var t = document.createTextNode("Slider Image");
            span.appendChild(t);
            this.editor.appendChild(span);

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
