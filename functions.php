<?php

function icke_tplPopupPage($id){
    $page = p_wiki_xhtml($id,'',false);
    echo '<div class="sec_level">';
    echo $page;
    echo '</div>';
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
