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

    while (jQuery('.popup_content ul').children('li.node').length) {
        console.log('foo');
        var $currentNode = jQuery('.popup_content ul').children('li.node').first();
        var $newPopout = $currentNode.closest('div.sec_level').clone();
        $newPopout.html('');
        var newZIndex = $currentNode.closest('div.sec_level').first().zIndex() + 5;
        var newCSS = {
            'z-index': newZIndex
        };
        $newPopout.css(newCSS);
        $currentNode.children('ul').first().appendTo($newPopout);
        $newPopout.appendTo($currentNode);
        $currentNode.removeClass('node');
    }

});
