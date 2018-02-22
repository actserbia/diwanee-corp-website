$(document).ready(function() {
    $.fn.setTagsDraggableAndDroppable = function() {
        $(this).each(function(index, object) {
            $(object).on('dragstart', function(e) {
                var startPos = 30 * $('div[id=' + e.target.id + ']').data('level');
                if(e.originalEvent.layerX < startPos || e.originalEvent.layerX > startPos + 30) {
                    e.preventDefault();
                }
                if($('div[id=' + e.target.id + ']').data('moving-disabled') == '0') {
                    e.originalEvent.dataTransfer.setData('id', e.target.id);
                }
            });

            $(object).on('dragover', function(e) {
                e.preventDefault();
            });

            $(object).on('drop', function(e) {
                e.preventDefault();
                var dragId = e.originalEvent.dataTransfer.getData('id');
                
                var element = e.target;
                
                var dropId = '';
                while(dropId === '') {
                    $.each(element.classList, function( index, className ) {
                        if(className === 'tag-item') {
                            dropId = element.id;
                        }
                    });
                    
                    if(dropId === '') {
                        element = element.parentNode;
                    }
                }

                var parents = $('div[id=' + dropId + ']').parents();
                var inParents = false;
                $.each(parents, function( index, parent ) {
                    if(dragId === parent.id) {
                        inParents = true;
                    };
                });
                
                if(inParents === false && dragId !== dropId) {
                    var startPos = 30 * $('div[id=' + dropId + ']').data('level');
                    if(e.originalEvent.layerX > startPos + 30) {
                        $('div[id=' + dropId + '] div[class=tags-list]').first().append($('div[id=' + dragId + ']'));
                        $('div[id=' + dragId + ']').data('level', $('div[id=' + dropId + '] div[class=tags-list]').first().data('level') + 1);
                    } else {
                        if(e.originalEvent.layerY < 20) {
                            $('div[id=' + dropId + ']').before($('div[id=' + dragId + ']'));
                        } else {
                            $('div[id=' + dropId + ']').after($('div[id=' + dragId + ']'));
                        }
                        $('div[id=' + dragId + ']').data('level', $('div[id=' + dropId + ']').data('level'));
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
            if($(objectChild).hasClass('tags-list')) {
                child = objectChild;
            }
        });

        if(child !== null) {
            tags.children = $(child).getTagsOrder();
        }

        return tags;
    };

    
    TagsManager = {
        initialize: function() {
            $('#tag_type').change(function() {
                $.ajax({
                    type: 'GET',
                    url: '/admin/tags-list',
                    data: {
                        tag_type_id: $(this).val()
                    },
                    success: function (data) {
                        $('#tags-list').html(data);
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
                    url: '/admin/tags-reorder-tags',
                    data: {
                        tags: $('#tags-list').getTagsOrder()
                    },
                    success: function (data) {
                        $('.alert').remove();
                        $('.right_col').prepend(data);
                    }
                });
            });
        }
    }
    
    TagsManager.initialize();
});