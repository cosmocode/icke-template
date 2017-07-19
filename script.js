jQuery(function intializeTemplateJS() {
    'use strict';

    var getULwidth = function getULwidth($ul) {
        var widestLiWidth = 0;
        var $li = $ul.children('li');
        $li.each(function getInlineLiWidth() {
            var $div = jQuery(this).children('div.li').first();
            var content = $div.html();
            var currentLiWidth;
            $div.html('<span id="widthMeasure">' + content + '</span>');
            currentLiWidth = jQuery('#widthMeasure').width();
            $div.html(content);
            widestLiWidth = Math.max(widestLiWidth, currentLiWidth);
        });
        return parseInt(widestLiWidth, 10);
    };

    (function toggleSubsectionsOnMobile() {
        var MOBILE_WIDTH = 600;
        var SHALLOWST_SECTION_TO_HIDE = 2;
        var DEEPEST_SECTION_TO_HIDE = 6;
        var i;
        var $page;
        if (jQuery(window).width() <= MOBILE_WIDTH) {
            $page = jQuery('#icke__page');
            for (i = SHALLOWST_SECTION_TO_HIDE; i < DEEPEST_SECTION_TO_HIDE; i += 1) {
                $page.find('div.level' + i).hide();
                $page.find('h' + i).click(function toggleSection() {
                    jQuery(this).next('div').toggle();
                });
            }
        }
    }());

    jQuery('div.sec_level').css('display', 'inline');
    while (jQuery('.popup_content ul').children('li.node').length) {
        var $currentNode = jQuery('.popup_content ul').children('li.node').first();
        var $newPopout = $currentNode.closest('div.sec_level').clone();
        $newPopout.html('');

        var newZIndex = $currentNode.closest('div.sec_level').first().zIndex() + 5;
        var parentWidth = getULwidth($currentNode.closest('ul'));
        var parentPaddingLeft = parseInt($currentNode.css('padding-left').replace('px', ''));
        var parentPaddingRight = parseInt($currentNode.css('padding-right').replace('px', ''));
        var newCSS = {
            'z-index': newZIndex,
            left: parentPaddingLeft + parentWidth + parentPaddingRight + 'px',
        };
        $newPopout.css(newCSS);
        $currentNode.children('ul').first().appendTo($newPopout);
        $newPopout.appendTo($currentNode);
        $currentNode.removeClass('node');
        $currentNode.addClass('navNode');
    }
    jQuery('div.sec_level').css('display', '');
});
