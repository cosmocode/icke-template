<?php

function icke_tplPopupPage($id){
    $page = p_wiki_xhtml($id,'',false);
    echo '<div class="sec_level">';
    echo $page;
    echo '</div>';
}
