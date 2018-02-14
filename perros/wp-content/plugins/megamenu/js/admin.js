/*global console,ajaxurl,$,jQuery*/

/**
 * Mega Menu jQuery Plugin
 */
(function ($) {
    "use strict";

    $.fn.megaMenu = function (options) {

        var panel = $("<div />");

        panel.settings = options;

        panel.log = function (message) {
            if (window.console && console.log) {
                console.log(message.data);
            }

            if (message.success !== true) {
                alert(message.data);
            }
        };


        panel.init = function () {

            panel.log({success: true, data: megamenu.debug_launched + " " + panel.settings.menu_item_id});

            $.colorbox({
                html: "",
                initialWidth: '991',
                scrolling: true,
                fixed: true,
                top: '10%',
                initialHeight: '500'
            });

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "mm_get_lightbox_html",
                    _wpnonce: megamenu.nonce,
                    menu_item_id: panel.settings.menu_item_id,
                    menu_item_depth: panel.settings.menu_item_depth,
                    menu_id: panel.settings.menu_id
                },
                cache: false,
                beforeSend: function() {
                    $('#cboxLoadedContent').empty();
                    $('#cboxClose').empty();
                    $('#cboxLoadingGraphic').show();
                },
                complete: function() {
                    $('#cboxLoadingGraphic').hide();
                    $('#cboxLoadingOverlay').remove();
                },
                success: function(response) {
                    var json = $.parseJSON(response.data);

                    var header_container = $("<div />").addClass("mm_header_container");

                    var title = $("<div />").addClass("mm_title").html(panel.settings.menu_item_title);

                    var saving = $("<div class='mm_saving '>" + megamenu.saving + "</div>");

                    header_container.append(title).append(saving);

                    var tabs_container = $("<div class='mm_tab_container' />");

                    var content_container = $("<div class='mm_content_container' />");

                    $.each(json, function(idx, obj) {

                        var content = $("<div />").addClass('mm_content').addClass(idx).html(this.content).hide();

                        // bind save button action
                        content.find('form').on("submit", function (e) {

                            start_saving();

                            e.preventDefault();

                            var data = $(this).serialize();

                            $.post(ajaxurl, data, function (submit_response) {

                                end_saving();

                                panel.log(submit_response);
                            });

                        });

                        if (idx == 'menu_icon') {

                            var form = content.find('form');

                            // bind save button action
                            form.on("change", function (e) {

                                start_saving();

                                e.preventDefault();

                                $("input", form).not(e.target).removeAttr('checked');

                                var data = $(this).serialize();

                                $.post(ajaxurl, data, function (submit_response) {

                                    end_saving();

                                    panel.log(submit_response);
                                });

                            });
                        }

                        if (idx == 'general_settings') {

                        }

                        if (idx == 'mega_menu') {

                            var widget_selector = content.find('#mm_widget_selector');

                            widget_selector.on('change', function() {

                                var selector = $(this);

                                if (selector.val() != 'disabled') {

                                    start_saving();

                                    var postdata = {
                                        action: "mm_add_widget",
                                        id_base: selector.val(),
                                        menu_item_id: panel.settings.menu_item_id,
                                        title: selector.find('option:selected').text(),
                                        _wpnonce: megamenu.nonce
                                    };

                                    $.post(ajaxurl, postdata, function (response) {

                                        end_saving();

                                        $(".no_widgets").hide();

                                        var widget = $(response.data);

                                        add_events_to_widget(widget);

                                        $("#widgets").append(widget);

                                        // reset the dropdown
                                        selector.val('disabled');

                                    });

                                }


                            });

                            var submenu_type = content.find('#mm_enable_mega_menu');

                            submenu_type.on('change', function() {

                                if ( $(this).val() == 'megamenu' ) {
                                    $("#widgets").removeClass('disabled');
                                } else {
                                    $("#widgets").addClass('disabled');
                                }

                                start_saving();

                                var postdata = {
                                    action: "mm_save_menu_item_settings",
                                    settings: { type: $(this).val() },
                                    menu_item_id: panel.settings.menu_item_id,
                                    _wpnonce: megamenu.nonce
                                };



                                $.post(ajaxurl, postdata, function (select_response) {

                                    end_saving();

                                    panel.log(select_response);
                                });

                            });

                            var number_of_columns = content.find('#mm_number_of_columns');

                            number_of_columns.on('change', function() {

                                content.find("#widgets").attr('data-columns', $(this).val());

                                start_saving();

                                var postdata = {
                                    action: "mm_save_menu_item_settings",
                                    settings: { panel_columns: $(this).val() },
                                    menu_item_id: panel.settings.menu_item_id,
                                    _wpnonce: megamenu.nonce
                                };

                                $.post(ajaxurl, postdata, function (select_response) {

                                    end_saving();

                                    panel.log(select_response);
                                });

                            });

                            var widget_area = content.find('#widgets');

                            widget_area.sortable({
                                forcePlaceholderSize: true,
                                items : '.widget:not(.sub_menu)',
                                placeholder: "drop-area",
                                start: function (event, ui) {
                                    $(".widget").removeClass("open");
                                    ui.item.data('start_pos', ui.item.index());
                                },
                                stop: function (event, ui) {
                                    // clean up
                                    ui.item.removeAttr('style');

                                    var start_pos = ui.item.data('start_pos');

                                    if (start_pos !== ui.item.index()) {
                                        ui.item.trigger("on_drop");
                                    }
                                }
                            });


                            $('.widget', widget_area).each(function() {
                                add_events_to_widget($(this));
                            });

                        }

                        var tab = $("<div />").addClass('mm_tab').addClass(idx).html(this.title).css('cursor', 'pointer').on('click', function() {
                            $(".mm_content").hide();
                            $(".mm_tab").removeClass('active');
                            $(this).addClass('active');
                            content.show();
                        });

                        if ( ( panel.settings.menu_item_depth == 0 && idx == 'mega_menu' ) ||
                             ( panel.settings.menu_item_depth > 0 && idx == 'general_settings' ) ) {
                            content.show();
                            tab.addClass('active');
                        }

                        tabs_container.append(tab);
                        content_container.append(content);
                    });

                    $('#cboxLoadedContent').addClass('depth-' + panel.settings.menu_item_depth).append(header_container).append(tabs_container).append(content_container);
                    $('#cboxLoadedContent').css({'width': '100%', 'height': '100%', 'display':'block'});
                    $('#cboxLoadedContent').trigger('megamenu_content_loaded');

                }
            });

        };

        var start_saving = function() {
            $('.mm_saving').show();
        }

        var end_saving = function() {
            $('.mm_saving').fadeOut('fast');
        }

        var add_events_to_widget = function (widget) {

            var widget_title = widget.find(".widget-title h4");
            var expand = widget.find(".widget-expand");
            var contract = widget.find(".widget-contract");
            var edit = widget.find(".widget-action");
            var widget_inner = widget.find(".widget-inner");
            var widget_id = widget.attr("data-widget-id");
            var menu_item_id = widget.attr("data-menu-item-id");
            var type = widget.is('[data-widget-id]') ? 'widget' : 'menu-item';

            widget.bind("on_drop", function () {

                start_saving();

                var position = $(".widget").not(".sub_menu").index(widget);

                $.post(ajaxurl, {
                    action: "mm_move_widget",
                    widget_id: widget_id,
                    position: position,
                    menu_item_id: panel.settings.menu_item_id,
                    _wpnonce: megamenu.nonce
                }, function (move_response) {
                    end_saving();
                    panel.log(move_response);
                });
            });

            expand.on("click", function () {

                var cols = parseInt(widget.attr("data-columns"), 10);
                var maxcols = parseInt($("#mm_number_of_columns").val(), 10);

                if (cols < maxcols) {
                    cols = cols + 1;

                    widget.attr("data-columns", cols);

                    start_saving();

                    if (type == 'widget') {

                        $.post(ajaxurl, {
                            action: "mm_update_widget_columns",
                            widget_id: widget_id,
                            columns: cols,
                            _wpnonce: megamenu.nonce
                        }, function (expand_response) {
                            end_saving();
                            panel.log(expand_response);
                        });

                    }

                    if (type == 'menu-item' ) {

                        $.post(ajaxurl, {
                            action: "mm_save_menu_item_settings",
                            menu_item_id: menu_item_id,
                            settings: { mega_menu_columns: cols },
                            _wpnonce: megamenu.nonce
                        }, function (contract_response) {
                            end_saving();
                            panel.log(contract_response);
                        });

                    }

                }

            });

            contract.on("click", function () {

                var cols = parseInt(widget.attr("data-columns"), 10);

                // account for widgets that have say 8 columns but the panel is only 6 wide
                var maxcols = parseInt($("#mm_number_of_columns").val(), 10);

                if (cols > maxcols) {
                    cols = maxcols;
                }

                if (cols > 1) {
                    cols = cols - 1;
                    widget.attr("data-columns", cols);
                } else {
                    return;
                }

                start_saving();

                if (type == 'widget') {

                    $.post(ajaxurl, {
                        action: "mm_update_widget_columns",
                        widget_id: widget_id,
                        columns: cols,
                        _wpnonce: megamenu.nonce
                    }, function (contract_response) {
                        end_saving();
                        panel.log(contract_response);
                    });

                }

                if (type == 'menu-item') {

                    $.post(ajaxurl, {
                        action: "mm_save_menu_item_settings",
                        menu_item_id: menu_item_id,
                        settings: { mega_menu_columns: cols },
                        _wpnonce: megamenu.nonce
                    }, function (contract_response) {
                        end_saving();
                        panel.log(contract_response);
                    });

                }

            });


            edit.on("click", function () {

                if (! widget.hasClass("open") && ! widget.data("loaded")) {

                    widget_title.addClass('loading');

                    // retrieve the widget settings form
                    $.post(ajaxurl, {
                        action: "mm_edit_widget",
                        widget_id: widget_id,
                        _wpnonce: megamenu.nonce
                    }, function (response) {

                        var $response = $(response);
                        var $form = $response.find('form');

                        // bind delete button action
                        $(".delete", $form).on("click", function (e) {
                            e.preventDefault();

                            var data = {
                                action: "mm_delete_widget",
                                widget_id: widget_id,
                                _wpnonce: megamenu.nonce
                            };

                            $.post(ajaxurl, data, function (delete_response) {
                                widget.remove();
                                panel.log(delete_response);
                            });

                        });

                        // bind close button action
                        $(".close", $form).on("click", function (e) {
                            e.preventDefault();

                            widget.toggleClass("open");
                        });

                        // bind save button action
                        $form.on("submit", function (e) {
                            e.preventDefault();

                            var data = $(this).serialize();

                            start_saving();

                            $.post(ajaxurl, data, function (submit_response) {
                                end_saving();
                                panel.log(submit_response);
                            });

                        });

                        widget_inner.html($response);

                        widget.data("loaded", true).toggleClass("open");

                        widget_title.removeClass('loading');

                        // Init Black Studio TinyMCE
                        if ( widget.is( '[id*=black-studio-tinymce]' ) ) {
                            bstw( widget ).deactivate().activate();
                        }

                    });

                } else {
                    widget.toggleClass("open");
                }

                // close all other widgets
                $(".widget").not(widget).removeClass("open");

            });

            return widget;
        };

        panel.init();

    };

}(jQuery));

/**
 *
 */
jQuery(function ($) {
    "use strict";

    $(".megamenu_launch").live("click", function (e) {
        e.preventDefault();

        $(this).megaMenu();
    });

    $('#megamenu_accordion').accordion({
        heightStyle: "content",
        collapsible: true,
        active: false,
        animate: 200
    });

    var apply_megamenu_enabled_class = function() {
        if ( $('input.megamenu_enabled:checked') && $('input.megamenu_enabled:checked').length ) {
            $('body').addClass('megamenu_enabled');
        } else {
            $('body').removeClass('megamenu_enabled');
        }
    }

    $('input.megamenu_enabled').on('change', function() {
        apply_megamenu_enabled_class();
    });

    apply_megamenu_enabled_class();

    $('#menu-to-edit li.menu-item').each(function() {

        var menu_item = $(this);
        var menu_id = $('input#menu').val();
        var title = menu_item.find('.menu-item-title').text();
        var id = parseInt(menu_item.attr('id').match(/[0-9]+/)[0], 10);

        var button = $("<span>").addClass("mm_launch")
                                .html(megamenu.launch_lightbox)
                                .on('click', function(e) {
                                    e.preventDefault();

                                    if ( ! $('body').hasClass('megamenu_enabled') ) {
                                        alert(megamenu.is_disabled_error);
                                        return;
                                    }

                                    var depth = menu_item.attr('class').match(/\menu-item-depth-(\d+)\b/)[1];

                                    $(this).megaMenu({
                                        menu_item_id: id,
                                        menu_item_title: title,
                                        menu_item_depth: depth,
                                        menu_id: menu_id
                                    });
                                });

        $('.item-title', menu_item).append(button);
    });

    $(".mm_tabs li").live('click', function() {
        var tab = $(this);
        var tab_id = $(this).attr('rel');

        tab.addClass('active');
        tab.siblings().removeClass('active');
        tab.parent().siblings().hide();
        tab.parent().siblings("." + tab_id).show();
    });
});