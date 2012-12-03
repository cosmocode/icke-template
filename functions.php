<?php

function icke_getFile($name) {
    return file_exists(DOKU_TPLINC . 'local/' . $name) ?
           'local/' . $name : $name;
}

function icke_tplPopupPage($id){
    $page = p_wiki_xhtml($id,'',false);
    if($page) {
        icke_tplPopup($page);
    }
}

function icke_tplPopup($page) {
    ?>
    <div class="sec_level">
    <span class="a"></span>
    <span class="b"></span>
    <div class="popup_content">
    <?php echo $page; ?>
    </div>
    <span class="c"></span>
    <span class="d"></span>
    </div>
    <?php
}

function icke_tplProjectSteps(){
    global $ID;
    global $conf;

    $steps = '';
    $ns = $ID;
    do {
        $ns = getNS($ns);
        $try = $ns . ':schritt';
        if(page_exists($try)) {
            $steps = $try;
            break;
        }
        $try .= ':' . $conf['start'];
        if(page_exists($try)) {
            $steps = $try;
            break;
        }
    } while ($ns);

    if (!$steps) return;

    echo '<li class="sideclip">';
    echo p_wiki_xhtml($steps,'',false);
    echo '</li>';
}


/**
 * Return the toolbox popup
 */
function icke_toolbox(){
    $types = explode(' ','recent index media subscribe admin profile');

    $tools  = '';
    $tools .= '<h1 class="empty"></h1>';
    $tools .= '<div class="level2">';
    $tools .= '<ul>';
    foreach($types as $type){
        $tools .= tpl_actionlink($type,'<li><div class="li">','</div></li>','',$return=true);
    }
    return $tools;
    $tools .= '</ul>';
    $tools .= '</div>';
}

/**
 * Print a single navigation item and associated quick popup
 */
function icke_navi($link,$name='',$class='',$popup='',$act=false,$sep=false){
    // active/separator decorations
    $liclass = '';
    if($act) $liclass .= ' active';
    if($sep) $liclass .= ' separator';
    $liclass = trim($liclass);
    if($liclass) $liclass = ' class="'.$liclass.'"';

    if(!$name && $link)  $name  = p_get_first_heading($link);
    if(!$class && $link) $class = 'qnav_'.str_replace(':','_',getNS($link));
    if($link) $link = ' href="'.wl($link).'"';

    // output the item
    echo '<li'.$liclass.'>';
    echo '<a class="qnav_item '.$class.'" '.$link.'>'.hsc($name).'</a>';
    if($popup){
        echo '<div class="sec_level"><span class="a"></span><span class="b"></span><div class="popup_content">';
        echo $popup;
        echo '</div><span class="c"></span><span class="d"></span></div>';
    }
    echo '</li>';
}

/**
 * Populate the navigation side bar from the configured navigation links
 */
function icke_tplSidebar() {
    global $ID;
    $sep = false;

    // load dynamic namespaces
    $navi = tpl_getConf('namespaces');
    $navi = explode(',',$navi);
    foreach($navi as $id){
        // empty ones are separators
        if(!$id){
            $sep = true;
            continue;
        }

        // handle user based links
        if(strstr($id,'%USER%') !== false){
            if(!$_SERVER['REMOTE_USER']) continue; // no user available, skip it
            $id = str_replace('%USER%',$_SERVER['REMOTE_USER'],$id);
        }

        $link = $id;
        resolve_pageid('',$link,$exists); // create full links
        if (auth_quickaclcheck($link) < AUTH_READ) continue;
        $ns   = getNS($link);
        if(!$ns) $ns = $link; // treat link as outside namespace startpage

        $popup = p_wiki_xhtml($ns.':quick','',false);
        $act   = (bool) preg_match('/^'.preg_quote($ns,'/').':/',$ID);
        $class = 'qnav_'.array_shift(explode(':',$ns)); // first ns part
        icke_navi($link,'',$class,$popup,$act,$sep);
        $sep =false;
    }

    // Add toolbox
    icke_navi('','Settings','qnav_einstellungen',icke_toolbox(),false,false);
}

/**
 * Include icons CSS for the navigation and fancy search
 *
 * Looks in the media namespace (ns:icon_on.png, ns:icon_off.png) first,
 * then in the template, then uses a fail image.
 */
function icke_tplCSS() {
    $navi = tpl_getConf('namespaces');
    $navi = explode(',',$navi);

    echo "<style type=\"text/css\">\n";

    $navi[] = "einstellungen";

    foreach($navi as $id){
        if(!$id) continue;
        $link = $id;
        resolve_pageid('',$link,$exists);
        $ns   = getNS($link);
        if(!$ns) $ns = $link;

        $class = array_shift(explode(':',$ns));

        echo "#icke__quicknav a.qnav_$class {";
            if(file_exists(mediaFN("$ns:icon_off.png"))){
                echo "background-image: url(".ml("$ns:icon_off.png",array('w'=>60,'h'=>60),true,'&').")";
            }elseif(file_exists(DOKU_TPLINC.'/images/icons/60x60/'.$class.'_inaktiv.png')){
                echo "background-image: url(".DOKU_TPL.'/images/icons/60x60/'.$class."_inaktiv.png)";
            }else{
                echo "background-image: url(".DOKU_TPL."/images/icons/60x60/fail.png)";
            }
        echo "}\n";

        echo "#icke__quicknav li.active a.qnav_$class, #icke__quicknav li:hover a.qnav_$class {";
            if(file_exists(mediaFN("$ns:icon_on.png"))){
                echo "background-image: url(".ml("$ns:icon_on.png",array('w'=>60,'h'=>60),true,'&').")";
            }elseif(file_exists(DOKU_TPLINC.'/images/icons/60x60/'.$class.'_aktiv.png')){
                echo "background-image: url(".DOKU_TPL.'/images/icons/60x60/'.$class."_aktiv.png)";
            }else{
                echo "background-image: url(".DOKU_TPL."/images/icons/60x60/fail.png)";
            }
        echo "}\n";

        echo "#fancysearch__ns_custom li.fancysearch_ns_$class {";
            echo 'text-indent: -10000px; width:30px; height:30px;';
            if(file_exists(mediaFN("$ns:icon_on.png"))){
                echo "background-image: url(".ml("$ns:icon_on.png",array('w'=>30,'h'=>30),true,'&').")";
            }elseif(file_exists(DOKU_TPLINC.'/images/icons/30x30/'.$class.'_aktiv.png')){
                echo "background-image: url(".DOKU_TPL.'/images/icons/30x30/'.$class."_aktiv.png)";
            }else{
                echo "background-image: url(".DOKU_TPL."/images/icons/30x30/fail.png)";
            }
        echo "}\n";
    }
    echo "#fancysearch__ns_custom li.fancysearch_ns_icke {";
    echo 'text-indent: -10000px; width:30px; height:30px;';
    echo 'background-image: url('.DOKU_TPL.'/images/icons/30x30/icke.png)';
    echo "}\n";

    echo "</style>\n";
}


function icke_tplSearch() {
    $navi = array('' => 'icke');
    $ns = tpl_getConf('namespaces');
    $ns = explode(',',$ns);
    foreach($ns as $id){
        if(!$id) continue;
        if(strstr($id,'%USER%') !== false) continue;

        $link = $id;
        resolve_pageid('',$link,$exists); // create full links
        if (auth_quickaclcheck($link) < AUTH_READ) continue;
        $ns   = getNS($link);
        if(!$ns) $ns = $link;
        $navi[$ns] = array_shift(explode(':',$ns));
    }

    $fancysearch = plugin_load('action', 'fancysearch');
    if (!is_null($fancysearch)) {
        $fancysearch->tpl_searchform($navi);
    }else{
        tpl_searchform(true, false);
    }
}



