/*global console,ajaxurl,$,jQuery*/

/**
 *
 */
jQuery(function ($) {
    "use strict";

    if ($('#codemirror').length) {
        var codeMirror = CodeMirror.fromTextArea(document.getElementById('codemirror'), {
            tabMode: 'indent',
            lineNumbers: true,
            lineWrapping: true,
            onChange: function(cm) {
                cm.save();
            }
        });
    }

    $(".mm_colorpicker").spectrum({
        preferredFormat: "rgb",
        showInput: true,
        showAlpha: true,
        clickoutFiresChange: true,
        change: function(color) {
            if (color.getAlpha() === 0) {
                $(this).siblings('div.chosen-color').html('transparent');
            } else {
                $(this).siblings('div.chosen-color').html(color.toRgbString());
            }
        }
    });

    $(".confirm").on("click", function() {
        return confirm(megamenu_settings.confirm);
    });

    $('#theme_selector').bind('change', function () {
        var url = $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });

    $('.mega-location-header').on("click", function(e) {
        if (e.target.nodeName.toLowerCase() != 'a') {
            $(this).parent().toggleClass('mega-closed').toggleClass('mega-open');
            $(this).siblings('.mega-inner').slideToggle();
        }
    });

    $('.icon_dropdown').on("change", function() {
        var icon = $("option:selected", $(this)).attr('data-class');
        // clear and add selected dashicon class
        $(this).next('.selected_icon').removeClass().addClass(icon).addClass('selected_icon');
    });

    $('select#mega_css').on("change", function() {
        var select = $(this);
        var selected = $(this).val();
        select.next().children().hide();
        select.next().children('.' + selected).show();
    });


});