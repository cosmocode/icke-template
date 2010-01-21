<?php

function icke_tplPopupPage($id){
    $page = p_wiki_xhtml($id,'',false);
    if($page) {
        echo '<div class="sec_level">';
        echo $page;
        echo '<div class="sec_level_bottom" />';
        echo '</div>';
    }
}
