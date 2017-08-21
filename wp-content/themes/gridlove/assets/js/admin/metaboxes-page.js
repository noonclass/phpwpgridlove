(function($) {
    $(document).ready(function() {

        /* Image opts selection */
        $('body').on('click', 'img.gridlove-img-select', function(e){
            e.preventDefault();
            $(this).closest('ul').find('img.gridlove-img-select').removeClass('selected');
            $(this).addClass('selected');
            $(this).closest('ul').find('input').removeAttr('checked');
            $(this).closest('li').find('input').attr('checked','checked');

            if($(this).attr('data-step') !== undefined ){
                
                var step = parseInt($(this).attr('data-step'));
                var min = parseInt($(this).attr('data-min'));
                var current = parseInt($(this).attr('data-default'));
                
                $(this).closest('.gridlove-tab').find('.gridlove-input-slider').attr('step', step ).attr('min', min).attr('value', current ).next().text(current);

            }
        });

        /* Dynamicaly apply select value */
        $('body').on('change', '.gridlove-opt-select', function(e) {
            //e.preventDefault();
            var sel = $(this).val();
            $(this).find('option').removeAttr('selected');
            $(this).find('option[value=' + sel + ']').attr('selected', 'selected');
        });

         /* Dynamicaly change span text based on slider input value  */
        $('body').on("input", '.gridlove-input-slider', function(e) {
            $(this).next().text($(this).val());
        });

        

        /* Module form tabs */
        $('body').on('click', '.gridlove-opt-tabs a', function(e) {
            e.preventDefault();
            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
            $(this).closest('.gridlove-module-form').find('.gridlove-tab').hide();
            $(this).closest('.gridlove-module-form').find('.gridlove-tab').eq($(this).index()).show();

        });

        /* Module layout types switch */

         $('body').on('click', '.gridlove-module-layout-switch', function(e) {

             var container = $(this).closest('.gridlove-opt-content').find('.gridlove-module-layouts');
             container.find('.gridlove-module-layout').removeClass('active');
             container.find('.gridlove-module-layout.'+ $(this).val() ).addClass('active').find('img.gridlove-img-select.selected').click();

         });


        /* Show/hide module more button link option */
        $('body').on('change', '.gridlove-more-button-switch', function(e){
            $(this).closest('.gridlove-opt').find('.gridlove-more-button-opt').toggleClass('gridlove-hidden');
        });
        

        /* Make modules sortable */
        $(".gridlove-modules").sortable({
            revert: false,
            cursor: "move",
            placeholder: "gridlove-module-drop"
        });


        var gridlove_current_module;
        var gridlove_module_type;


        /* Add new module */
        $('body').on('click', '.gridlove-add-module', function(e) {
            e.preventDefault();
            gridlove_module_type = $(this).attr('data-type');
            var $modal = $($.parseHTML('<div class="gridlove-module-form">' + $('#gridlove-module-clone .' + gridlove_module_type + ' .gridlove-module-form').html() + '</div>'));
            gridlove_dialog($modal, 'Add New Module', 'gridlove-save-module');

            /* Make some options sortable */
            $(".gridlove-opt-content.sortable").sortable({
                revert: false,
                cursor: "move"
            });
        });

        /* Edit module */
        $('body').on('click', '.gridlove-edit-module', function(e) {
            e.preventDefault();
            gridlove_current_module = parseInt($(this).closest('.gridlove-module').attr('data-module'));
            var $modal = $(this).closest('.gridlove-module').find('.gridlove-module-form').clone();
            gridlove_dialog($modal, 'Edit Module', 'gridlove-save-module');

            /* Make some options sortable */
            $(".gridlove-opt-content.sortable").sortable({
                revert: false,
                cursor: "move"
            });
        });

        /* Remove module */
        $('body').on('click', '.gridlove-remove-module', function(e) {
            e.preventDefault();
            remove = gridlove_confirm();
            if (remove) {
                $(this).closest('.gridlove-module').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });

        /* Save module */

        $('body').on('click', 'button.gridlove-save-module', function(e) {

            e.preventDefault();

            var $gridlove_form = $(this).closest('.wp-dialog').find('.gridlove-module-form').clone();

            /* Nah, jQuery clone bug, clone text area manually */
            var txt_content = $(this).closest('.wp-dialog').find('.gridlove-module-form').find("textarea").first().val();
            if (txt_content !== undefined) {
                $gridlove_form.find("textarea").first().val(txt_content);
            }

            if ($gridlove_form.hasClass('edit')) {
                $gridlove_form = gridlove_fill_form_fields($gridlove_form);
                var $module = $('.gridlove-module-' + gridlove_current_module);
                $module.find('.gridlove-module-form').html($gridlove_form.html());
                $module.find('.gridlove-module-title').text($gridlove_form.find('.mod-title').val());
                $module.find('.gridlove-module-columns').text($gridlove_form.find('.mod-columns:checked').closest('li').find('span').text());
            } else {
                var count = $('.gridlove-modules-count').attr('data-count');
                $gridlove_form = gridlove_fill_form_fields($gridlove_form, 'gridlove[modules][' + count + ']');
                $('.gridlove-modules').append($('#gridlove-module-clone .' + gridlove_module_type).html());
                var $new_module = $('.gridlove-modules .gridlove-module').last();
                $new_module.addClass('gridlove-module-' + parseInt(count)).attr('data-module', parseInt(count)).find('.gridlove-module-form').addClass('edit').html($gridlove_form.html());
                $new_module.find('.gridlove-module-title').text($gridlove_form.find('.mod-title').val());
                $new_module.find('.gridlove-module-columns').text($gridlove_form.find('.mod-columns:checked').closest('li').find('span').text());
                $('.gridlove-modules-count').attr('data-count', parseInt(count) + 1);
                $('.gridlove-empty-modules').hide();
            }

        });

        /* Open our dialog modal */
        function gridlove_dialog(obj, title, action) {

            obj.dialog({
                'dialogClass': 'wp-dialog',
                'appendTo': false,
                'modal': true,
                'autoOpen': false,
                'closeOnEscape': true,
                'draggable': false,
                'resizable': false,
                'width': 800,
                'height': $(window).height() - 60,
                'title': title,
                'close': function(event, ui) {
                    $('body').removeClass('modal-open');
                },
                'buttons': [{
                    'text': "Save",
                    'class': 'button-primary ' + action,
                    'click': function() {
                        $(this).dialog('close');
                    }
                }]
            });

            obj.dialog('open');

            $('body').addClass('modal-open');
        }


        /* Fill form fields dynamically */
        function gridlove_fill_form_fields($obj, name) {

            $obj.find('.gridlove-count-me').each(function(index) {

                if (name !== undefined && !$(this).is('option')) {
                    $(this).attr('name', name + $(this).attr('name'));
                }

                if ($(this).is('textarea')) {
                    $(this).html($(this).val());
                }


                if (!$(this).is('select')) {
                    $(this).attr('value', $(this).val());
                }



                if ($(this).is(":checked")) {
                    $(this).attr('checked', 'checked');
                } else {
                    $(this).removeAttr('checked');
                }

            });

            return $obj;
        }

        function gridlove_confirm() {
            var ret_val = confirm("Are you sure?");
            return ret_val;
        }

        /* Metabox switch - do not show every metabox for every template */

        gridlove_template_metaboxes(false);

        $('#page_template').change(function(e) {
            gridlove_template_metaboxes(true);
        });

        function gridlove_template_metaboxes(scroll) {


            var template = $('select#page_template').val();

            if (template == 'template-modules.php') {
                $('#gridlove_page_sidebar').fadeOut(300);
                $('#gridlove_page_layout').fadeOut(300);
                $('#gridlove_modules').fadeIn(300);
                $('#gridlove_pagination').fadeIn(300);
                $('#gridlove_cover').fadeIn(300);
                if (scroll) {
                    var target = $('#gridlove_cover').attr('id');
                    $('html, body').stop().animate({
                        'scrollTop': $('#' + target).offset().top
                    }, 900, 'swing', function() {
                        window.location.hash = target;
                    });
                }
            } else if (template == 'template-full-width.php') {
                $('#gridlove_page_sidebar').fadeOut(300);
                $('#gridlove_page_layout').fadeOut(300);
                $('#gridlove_modules').fadeOut(300);
                $('#gridlove_pagination').fadeOut(300);
                $('#gridlove_cover').fadeOut(300);
            } else {
                $('#gridlove_page_sidebar').fadeIn(300);
                $('#gridlove_page_layout').fadeIn(300);
                $('#gridlove_modules').fadeOut(300);
                $('#gridlove_pagination').fadeOut(300);
                $('#gridlove_cover').fadeOut(300);
            }

        }



    });



})(jQuery);