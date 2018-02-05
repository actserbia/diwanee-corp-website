$(document).ready(function() {
    $.fn.setTagsDraggableAndDroppable = function() {
        $(this).each(function(index, object) {
            $(object).on('dragstart', function(e) {
                var startPos = 30 * $('div[id=' + e.target.id + ']').data('level');
                if(e.originalEvent.layerX < startPos || e.originalEvent.layerX > startPos + 30) {
                    e.preventDefault();
                }
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

                var parents = $('div[id=' + e.target.parentNode.id + ']').parents();
                var inParents = false;
                $.each(parents, function( index, parent ) {
                    if(dragId === parent.id) {
                        inParents = true;
                    };
                });
                if(inParents === false) {
                    var startPos = 30 * $('div[id=' + e.target.parentNode.id + ']').data('level');
                    if(e.originalEvent.layerX > startPos + 30) {
                        $('div[id=' + e.target.parentNode.id + '] div[class=tag-list]').first().append($('div[id=' + dragId + ']'));
                        $('div[id=' + dragId + ']').data('level', $('div[id=' + e.target.parentNode.id + '] div[class=tag-list]').first().data('level') + 1);
                    } else {
                        if(e.originalEvent.layerY < 20) {
                            $('div[id=' + e.target.parentNode.id + ']').before($('div[id=' + dragId + ']'));
                        } else {
                            $('div[id=' + e.target.parentNode.id + ']').after($('div[id=' + dragId + ']'));
                        }
                        $('div[id=' + dragId + ']').data('level', $('div[id=' + e.target.parentNode.id + ']').data('level'));
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
                tag_type_id: $(this).val()
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