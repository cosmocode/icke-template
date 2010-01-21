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

function icke_tplProjectSteps(){
    global $ID;

    $steps = '';
    $ns = $ID;
    do {
        $ns = getNS($ns);
        if(page_exists("$ns:steps:")){
            $steps = "$ns:steps:";
            break;
        }
    }while($ns);

    if(!$steps) return;


    echo '<li class="sideclip">';
    echo p_wiki_xhtml($steps,'',false);
    echo '</li>';
}
