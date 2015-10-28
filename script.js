jQuery(function(){

    /** hover effect for ie6 **/
    jQuery("#icke__quicknav li").hover(
        function() { jQuery(this).addClass("hover"); },
        function() { jQuery(this).removeClass("hover"); }
    );

    if(jQuery(window).width() <= 600){
        for(i=2; i<6; i++){
            jQuery('#icke__page div.level'+i).hide();
            jQuery('#icke__page h'+i).click(function(){
                jQuery(this).next('div').toggle();
            });
        }
    }

    getULwidth = function getULwidth($ul) {
        var width = 0;
        var $li = $ul.children('li');
        $li.each(function() {
            var $div = jQuery(this).children('div.li').first();
            var content = $div.html();
            $div.html('<span id="widthMeasure">' + content + '</span>');
            if (width < jQuery('#widthMeasure').width()) {
                width = jQuery('#widthMeasure').width();
            }
            $div.html(content);
        });
        return parseInt(width);
    };

    jQuery('div.sec_level').css('display','inline');
    while (jQuery('.popup_content ul').children('li.node').length) {
        var $currentNode = jQuery('.popup_content ul').children('li.node').first();
        var $newPopout = $currentNode.closest('div.sec_level').clone();
        $newPopout.html('');

        var newZIndex = $currentNode.closest('div.sec_level').first().zIndex() + 5;
        var parentWidth = getULwidth($currentNode.closest('ul'));
        var parentPaddingLeft = parseInt($currentNode.css('padding-left').replace("px", ""));
        var parentPaddingRight = parseInt($currentNode.css('padding-right').replace("px", ""));
        var newCSS = {
            'z-index': newZIndex,
            'left' : parentPaddingLeft + parentWidth + parentPaddingRight + 'px'
        };
        $newPopout.css(newCSS);
        $currentNode.children('ul').first().appendTo($newPopout);
        $newPopout.appendTo($currentNode);
        $currentNode.removeClass('node');
    }
    jQuery('div.sec_level').css('display','');

});
