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
        $newPopout.find('div.popup_content').html('');
        $newPopout.css("left","30px").css("top","-20px");
        var newZIndex = $currentNode.closest('div.sec_level').find('span.b').first().zIndex() + 1;
        $newPopout.css("z-index", newZIndex);
        $currentNode.children('ul').first().appendTo($newPopout.find('div.popup_content'));
        $newPopout.appendTo($currentNode);
        $currentNode.removeClass('node');
    }

});
