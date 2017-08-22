(function($) {

    "use strict";
    
    /* 评论提交 */
    $(document).ready(function() {
        var __cancel = $('#cancel-comment-reply-link'),
            __cancel_text = __cancel.text(),
            __list = 'comment-list';//your comment wrapprer
        $(document).on("submit", "#commentform", function() {
            $.ajax({
                url: wp_js_settings.ajax_url,
                data: $(this).serialize() + "&action=ajax_comment",
                type: $(this).attr('method'),
                beforeSend: addComment.createButterbar("submit...."),
                error: function(jqXHR, textStatus, errorThrown) {
                    var t = addComment;
                    t.createButterbar(jqXHR.responseText);
                },
                success: function(data) {
                    $('textarea').each(function() {
                        this.value = ''
                    });
                    var t = addComment,
                        cancel = t.I('cancel-comment-reply-link'),
                        temp = t.I('wp-temp-form-div'),
                        respond = t.I(t.respondId),
                        post = t.I('comment_post_ID').value,
                        parent = t.I('comment_parent').value;
                    if (parent != '0') {
                        $('#respond').before('<ol class="children">' + data + '</ol>');
                    } else if (!$('.' + __list ).length) {
                        if (wp_js_settings.comment_form == 'bottom') {
                            $('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
                        } else {
                            $('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
                        }

                    } else {
                        if (wp_js_settings.comment_order == 'asc') {
                            $('.' + __list ).append(data); // your comments wrapper
                        } else {
                            $('.' + __list ).prepend(data); // your comments wrapper
                        }
                    }
                    t.createButterbar("submit success.");
                    cancel.style.display = 'none';
                    cancel.onclick = null;
                    t.I('comment_parent').value = '0';
                    if (temp && respond) {
                        temp.parentNode.insertBefore(respond, temp);
                        temp.parentNode.removeChild(temp)
                    }
                }
            });
            return false;
        });
        var addComment = {
            moveForm: function(commId, parentId, respondId) {
                var t = this,
                    div, comm = t.I(commId),
                    respond = t.I(respondId),
                    cancel = t.I('cancel-comment-reply-link'),
                    parent = t.I('comment_parent'),
                    post = t.I('comment_post_ID');
                __cancel.text(__cancel_text);
                t.respondId = respondId;
                if (!t.I('wp-temp-form-div')) {
                    div = document.createElement('div');
                    div.id = 'wp-temp-form-div';
                    div.style.display = 'none';
                    respond.parentNode.insertBefore(div, respond)
                }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                $("body").animate({
                    scrollTop: $('#respond').offset().top - 180
                }, 400);
                parent.value = parentId;
                cancel.style.display = '';
                cancel.onclick = function() {
                    var t = addComment,
                        temp = t.I('wp-temp-form-div'),
                        respond = t.I(t.respondId);
                    t.I('comment_parent').value = '0';
                    if (temp && respond) {
                        temp.parentNode.insertBefore(respond, temp);
                        temp.parentNode.removeChild(temp);
                    }
                    this.style.display = 'none';
                    this.onclick = null;
                    return false;
                };
                try {
                    t.I('comment').focus();
                } catch (e) {}
                return false;
            },
            I: function(e) {
                return document.getElementById(e);
            },
            clearButterbar: function(e) {
                if ($(".butter-bar").length > 0) {
                    $(".butter-bar").remove();
                }
            },
            createButterbar: function(message) {
                var t = this;
                t.clearButterbar();
                $("body").append('<div class="butter-bar butter-bar-center"><p class="butter-bar-message">' + message + '</p></div>');
                //setTimeout("$('.butter-bar').remove()", 3000);
            }
        };
        
        //init hide respond-form
        $("#respond").toggleClass("hidden");
        $(".respond-switch").click(function () {
            $("#respond").toggleClass("hidden");
        });
    }); //document ready end

})(jQuery);