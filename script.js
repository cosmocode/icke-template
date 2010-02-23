addInitEvent(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */
    jQuery("#icke__sidebar .search .namespace").replaceWith(jQuery('<input class="namespace" type="hidden" name="namespace" value="" />'));
    jQuery("#icke__sidebar .search #ns_custom").show();
    jQuery(document).live("click", function(){
        jQuery("#icke__sidebar .search #ns_custom:not(.closed)").addClass("closed");
    });
    jQuery("#icke__sidebar .search #ns_custom.closed").live("click", function(){
        jQuery(this).removeClass("closed");
    });
    jQuery("#icke__sidebar .search #ns_custom:not(.closed) li").live("click", function(){
        jQuery("#icke__sidebar .search .namespace").val(jQuery(this).attr("class"));
        jQuery(this).parent().animate({'top': (jQuery(this).prevAll().size()*-30) + 'px' },"slow");
        jQuery(this).parent().parent().addClass("closed");
    });

    
    /** hover effect for ie6 **/
    if (jQuery.browser === 'msie' && jQuery.browser.version === '6.0') {
        jQuery(document).ready(function(){
            jQuery("#icke__quicknav li").hover(
                function() { jQuery(this).addClass("hover"); },
                function() { jQuery(this).removeClass("hover"); }
            ); 
        });
    }
});

