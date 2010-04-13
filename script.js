addInitEvent(function(){
    /** hover effect for ie6 **/
    if (jQuery.browser.msie) {
        jQuery(document).ready(function(){
            jQuery("#icke__quicknav li").hover(
                function() { jQuery(this).addClass("hover"); },
                function() { jQuery(this).removeClass("hover"); }
            );
        });
    }
});

