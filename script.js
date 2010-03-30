addInitEvent(function(){
    /*
     * SEARCH-BOX DROPDOWN
     */

    // Replace HTML dropdown with the icon dropdown, but keep the current
    // value.
    jQuery("#icke__sidebar .search .namespace")
          .replaceWith(jQuery('<input class="namespace" type="hidden" ' + 
                              'name="namespace" value="' +
                              jQuery("#icke__sidebar .search .namespace").val() +
                              '" />'));
    var cur = '#icke__sidebar .search li.' +
              jQuery("#icke__sidebar .search .namespace").val() + '_search';
    jQuery(cur).parent().css('top', (jQuery(cur).prevAll().size()*-31) + 'px');

    jQuery("#icke__sidebar .search #ns_custom").show();
    jQuery("#icke__sidebar .search #ns_custom").live("click", function(){
        jQuery(this).toggleClass("closed");
    });
    jQuery("#icke__sidebar .search #ns_custom:not(.closed) li").live("click", function(){
        jQuery("#icke__sidebar .search .namespace").val(jQuery(this).attr("class").match(/(?:(\w+)_search|^()$)/)[1]);
        jQuery(this).parent().animate({'top': (jQuery(this).prevAll().size()*-31) + 'px' },"slow");
        jQuery(this).parent().parent().addClass("closed");
    });
    
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

