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

    jQuery(".popup_content li.node").mouseenter(
        function() {
            jQuery(this).children('ul').slideDown(500);
        }
    );

});
