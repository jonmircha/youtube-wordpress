/*jslint browser: true, white: true */
/*global console,jQuery,megamenu,window,navigator*/

/**
 * Max Mega Menu jQuery Plugin
 */
(function($) {

    "use strict";

    $.maxmegamenu = function(menu, options) {

        var plugin = this;
        var $menu = $(menu);

        var defaults = {
            event: $menu.attr('data-event'),
            effect: $menu.attr('data-effect'),
            panel_width: $menu.attr('data-panel-width'),
            second_click: $menu.attr('data-second-click'),
            vertical_behaviour: $menu.attr('data-vertical-behaviour'),
            document_click: $menu.attr('data-document-click'),
            breakpoint: $menu.attr('data-breakpoint')
        };

        plugin.settings = {};

        var isTouchDevice = function() {
            if (typeof Modernizr == 'object') {
                return Modernizr.touch;
            }

            return (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
        };


        plugin.hidePanel = function(anchor, immediate) {
            if (immediate) {
                anchor.siblings('.mega-sub-menu').removeClass('mega-toggle-on').css('display', '');
                anchor.parent().removeClass('mega-toggle-on').triggerHandler("close_panel");
                return;
            }

            if ( megamenu.effect[plugin.settings.effect] ) {
                var effect = megamenu.effect[plugin.settings.effect]['out'];
                var speed = megamenu.effect[plugin.settings.effect]['speed'] ? megamenu.effect[plugin.settings.effect]['speed'] : "fast";

                if (effect.css) {
                    anchor.siblings('.mega-sub-menu').css(effect.css);
                }

                if (effect.animate) {
                    anchor.siblings('.mega-sub-menu').animate(effect.animate, speed, function() {
                        anchor.parent().removeClass('mega-toggle-on').triggerHandler("close_panel");
                    });
                } else {
                    anchor.parent().removeClass('mega-toggle-on').triggerHandler("close_panel");
                }
            } else {
                anchor.parent().removeClass('mega-toggle-on').triggerHandler("close_panel");
            }

        };


        plugin.hideAllPanels = function() {
            $('.mega-toggle-on > a', $menu).each(function() {
                plugin.hidePanel($(this), true);
            });
        };


        plugin.hideSiblingPanels = function(anchor, immediate) {
            // all open children of open siblings
            anchor.parent().siblings().find('.mega-toggle-on').andSelf().children('a').each(function() {
                if ( $(this).parent().hasClass('mega-toggle-on') ) {
                    plugin.hidePanel($(this), immediate);
                }
            });
        }


        plugin.isDesktopView = function() {
            return $(window).width() > plugin.settings.breakpoint;
        }


        plugin.hideOpenSiblings = function() {
            // desktops, horizontal
            if ( plugin.isDesktopView() && $menu.hasClass('mega-menu-horizontal') ) {
                return 'immediately';
            }

            if ( plugin.settings.vertical_behaviour == 'accordion' ) {
                return 'animated';
            }

        }


        plugin.showPanel = function(anchor) {

            switch( plugin.hideOpenSiblings() ) {
                case 'immediately':
                    plugin.hideSiblingPanels(anchor, true);
                    break;
                case 'animated':
                    plugin.hideSiblingPanels(anchor, false);
                    break;
            }

            // apply dynamic width and sub menu position
            if ( anchor.parent().hasClass('mega-menu-megamenu') && $(plugin.settings.panel_width).length ) {
                var submenu_offset = $menu.offset();
                var target_offset = $(plugin.settings.panel_width).offset();

                anchor.siblings('.mega-sub-menu').css({
                    width: $(plugin.settings.panel_width).outerWidth(),
                    left: (target_offset.left - submenu_offset.left) + "px"
                });
            }

            if ( megamenu.effect[plugin.settings.effect] ) {
                var effect = megamenu.effect[plugin.settings.effect]['in'];
                var speed = megamenu.effect[plugin.settings.effect]['speed'] ? megamenu.effect[plugin.settings.effect]['speed'] : "fast";

                if (effect.css) {
                    anchor.siblings('.mega-sub-menu').css(effect.css);
                }

                if (effect.animate) {
                    anchor.siblings('.mega-sub-menu').animate(effect.animate, speed, 'swing', function() {
                        $(this).css('visiblity', 'visible');
                    });
                }
            }

            anchor.parent().addClass('mega-toggle-on').triggerHandler("open_panel");
        };


        var openOnClick = function() {
            // hide menu when clicked away from
            $(document).on('click', function(event) {
                if ( ( plugin.settings.document_click == 'collapse' || ! plugin.isDesktopView() ) && ! $(event.target).closest(".mega-menu li").length ) {
                    plugin.hideAllPanels();
                }
            });

            $('li.mega-menu-megamenu.mega-menu-item-has-children > a, li.mega-menu-flyout.mega-menu-item-has-children > a, li.mega-menu-flyout li.mega-menu-item-has-children > a', menu).on({
                click: function(e) {
                    // check for second click
                    if ( plugin.settings.second_click == 'go' || $(this).parent().hasClass("mega-click-click-go") ) {
                        if ( ! $(this).parent().hasClass("mega-toggle-on") ) {
                            e.preventDefault();
                            plugin.showPanel($(this));
                        }
                    } else {
                        e.preventDefault();

                        if ( $(this).parent().hasClass("mega-toggle-on") ) {
                            plugin.hidePanel($(this), false);
                        } else {
                            plugin.showPanel($(this));
                        }
                    }
                }
            });
        };


        var openOnHover = function() {
            $('li.mega-menu-megamenu.mega-menu-item-has-children, li.mega-menu-flyout.mega-menu-item-has-children, li.mega-menu-flyout li.mega-menu-item', menu).hoverIntent({
                over: function () {
                    plugin.showPanel($(this).children('a'));
                },
                out: function () {
                    if ($(this).hasClass("mega-toggle-on")) {
                        plugin.hidePanel($(this).children('a'), false);
                    }
                },
                timeout: megamenu.timeout,
                interval: megamenu.interval
            });
        };


        plugin.init = function() {
            plugin.settings = $.extend({}, defaults, options);

            $menu.removeClass('mega-no-js');

            $menu.siblings('.mega-menu-toggle').on('click', function() {
                $(this).toggleClass('mega-menu-open');
            });

            if (isTouchDevice() || plugin.settings.event === 'click') {
                openOnClick();
            } else {
                openOnHover();
            }
        };

        plugin.init();

    };


    $.fn.maxmegamenu = function(options) {
        return this.each(function() {
            if (undefined === $(this).data('maxmegamenu')) {
                var plugin = new $.maxmegamenu(this, options);
                $(this).data('maxmegamenu', plugin);
            }
        });
    };


    $(function() {
        $(".mega-menu").maxmegamenu();
    });


})(jQuery);