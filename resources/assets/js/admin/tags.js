$(document).ready(function() {
    $.fn.setTagsDraggableAndDroppable = function() {
        $(this).each(function(index, object) {
            $(object).on('dragstart', function(e) {
                if($('div[id=' + e.target.id + ']').data('parents-count') < 2) {
                    e.originalEvent.dataTransfer.setData('id', e.target.id);
                }
            });

            $(object).on('dragover', function(e) {
                e.preventDefault();
            });

            $(object).on('drop', function(e) {
                e.preventDefault();
                var dragId = e.originalEvent.dataTransfer.getData('id');

                console.log(e.originalEvent.layerX);
                console.log(e.originalEvent.layerY);
                console.log(e.target.parentNode.id);
                console.log(e.target.parentNode);
                console.log(e.target.parentNode.parentNode.id);
                console.log(dragId);

                if(dragId !== e.target.parentNode.id) {
                    if(e.originalEvent.layerX > 100) {
                        $('div[id=' + e.target.parentNode.id + '] div[class=tag-list]').first().append($('div[id=' + dragId + ']'));
                    } else {
                        if(e.originalEvent.layerY < 20) {
                            $('div[id=' + e.target.parentNode.id + ']').before($('div[id=' + dragId + ']'));
                        } else {
                            $('div[id=' + e.target.parentNode.id + ']').after($('div[id=' + dragId + ']'));
                        }
                    }
                }
            });
        });
    };


    $.fn.getTagsOrder = function() {
        var tags = [];
        $.each($(this).children(), function(index, object){
            tags.push($(object).getTagsOrderFromLI(tags));
        });
        return tags;
    };

    $.fn.getTagsOrderFromLI = function() {
        var tags = {};

        tags.id = $(this).data('tag-id');

        var child = null;
        $.each($(this).children(), function(index, objectChild) {
            if($(objectChild).hasClass('tag-list')) {
                child = objectChild;
            }
        });

        if(child !== null) {
            tags.children = $(child).getTagsOrder();
        }

        return tags;
    };

    $('#tagType').change(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/tags-reorder-list',
            data: {
                type: $(this).val()
            },
            success: function (data) {
                $('#tag-list').html(data);
                if($.trim(data) === '') {
                    $('#tags-reoder').attr('style', 'display:none;');
                } else {
                    $('#tags-reoder').attr('style', 'display:block;');
                }
                $('.tag-item[draggable=true]').setTagsDraggableAndDroppable();
            }
        });
    });

    $('#tags-reoder').click(function() {
        $.ajax({
            type: 'GET',
            url: '/admin/tags-reorder',
            data: {
                tags: $('#tag-list').getTagsOrder()
            },
            success: function (data) {
                $('.right_col').prepend(data);
            }
        });
    });
});