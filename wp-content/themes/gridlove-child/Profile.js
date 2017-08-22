(function($) {

    "use strict";
    
    /* 瀑布流无限加载 */
    $(document).ready(function () {
        /************************************/
        var ias;
        if (wp_js_settings.current != null){
            if(wp_js_settings.current == 'index'){
                console.log("ias init....");
                var ias = $.ias({
                    container: '.gridlove-posts',
                    item: '.gridlove-entry',
                    pagination: ".gridlove-pagination",
                    next: ".gridlove-pagination a",
                });
            }
            if(wp_js_settings.current == 'single'){
                var ias = $.ias({
                    container: '.comment-list',
                    item: '.comment',
                    pagination: ".navigation",
                    next: ".nav-previous a",
                });
            }
            
            ias.extension(new IASTriggerExtension({offset: 2, html: '<nav class="gridlove-pagination gridlove-load-more"><a href="javascript:void(0);">{text}</a><div class="gridlove-loader" style="display:none;"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>'}));
            ias.extension(new IASSpinnerExtension({html: '<nav class="gridlove-pagination gridlove-load-more gridlove-loader-active"><a href="javascript:void(0);">{text}</a><div class="gridlove-loader"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>'}));
            ias.extension(new IASNoneLeftExtension({html: '<nav class="gridlove-pagination gridlove-load-more gridlove-separator"><span>{text}</span><div class="gridlove-loader" style="display:none;"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>'}));
            
            //绑定的是根窗口，滚动触发
            ias.on('scroll', function(scrollOffset, scrollThreshold) {
                console.log('We scroll:' + scrollOffset);
            });
        }
        
        $(".entry-content").mCustomScrollbar({setHeight: 650, theme: "minimal-dark"});
        $(".comment-list").mCustomScrollbar({
            setHeight: 650, 
            theme: "rounded-dots-dark",
            scrollButtons:{ enable: true },
            callbacks: {onTotalScroll:function(){
                console.log("Scrolled to end of content.");
          
                //屏幕向下移动1像素，触发ias的滚动事件，自动加载trigger按钮
                $(document).scrollTop($(document).scrollTop()+1);

                //修正：滚动CustomScrollbar显示上面的trigger按钮
                setTimeout(function(){
                    $(".comment-list").mCustomScrollbar("scrollTo","bottom",{
                        scrollInertia:500
                    });
                    }, 500);
                }
            }
        });
        
        
        
        /* 相册图片增加鼠标悬停特效 */
        var pexetoSite;
        pexetoSite = {
            disableRightClick:false,
            initSite : function() {
                // sets the lightbox
                $('a.wplightbox[title="image"]').each(function(){
                    $(this).toggleClass( "lightbox-image" );
                    //$(this).attr("rel", "lightbox[group]");//for responsive-lightbox plugin
                });
                
                $('a.lightbox-image').each(function(){
                    $(this).append('<div class="portfolio-more"><div class="portfolio-icon"></div></div>');
                });
                
                //set the hover animation to the gallery images
                $('a.lightbox-image').on('mouseenter', this.doOnImageMouseenter);
                $('a.lightbox-image').on('mouseleave', this.doOnImageMouseleave);
                
                if(this.disableRightClick){
                    this.disableRightClicking();
                }
            },
            
            disableRightClicking:function(){
                $(document).bind('contextmenu', function(e) {
                    if(pexetoSite.rightClickMessage){
                        alert(pexetoSite.rightClickMessage);
                    }
                    return false;
                });
            },
            
            doOnImageMouseenter:function(){
                pexetoSite.elemFadeIn($(this).find('.portfolio-more'));
                pexetoSite.elemFadeOut($(this).find('img'), 0.8);
            },
            
            doOnImageMouseleave:function(){
                pexetoSite.elemFadeOut($(this).find('.portfolio-more'), 0);
                pexetoSite.elemFadeIn($(this).find('img'));
            },
            
            /**
             * Fades an element in.
             * @param $elem the element to be faded
             */
            elemFadeIn:function($elem){
                $elem.stop().animate({opacity:1}, function(){
                    $elem.animate({opacity:1}, 0);	
                });
            },
            
            /**
             * Fades an elemen out to a selected opacity.
             * @param $elem the element to be faded
             * @param opacity the opacity to be faded to
             */
            elemFadeOut:function($elem, opacity){
                $elem.stop().animate({opacity:opacity}, function(){
                    $elem.animate({opacity:opacity}, 0);	
                });
            }
        };
        
        pexetoSite.initSite();
    });//document ready end
    
})(jQuery);