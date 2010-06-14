addInitEvent(function(){
    /** hover effect for ie6 **/
    jQuery(document).ready(function(){
        jQuery("#icke__quicknav li").hover(
            function() { jQuery(this).addClass("hover"); },
            function() { jQuery(this).removeClass("hover"); }
        );
    });
});

