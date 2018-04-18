<?php

require_once dirname(__FILE__) . '/IckeNavigation.php';

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
    $tools  = '';
    $tools .= '<h1 class="empty"></h1>';
    $tools .= '<div class="level2">';
    $tools .= '<ul>';
    $tools .= (new \dokuwiki\Menu\SiteMenu())->getListItems('dw-menu-item ');
    $tools .= (new \dokuwiki\Menu\UserMenu())->getListItems('dw-menu-item ');
    $tools .= '</ul></div>';
    return $tools;
}

function icke_toolbox_renderer($action, $text, $pluginName, $pluginType) {
    if (plugin_load($pluginType, $pluginName) === null) {
        return '';
    }
    global $ID;
    $url = wl($ID, array('do' => $action));
    $link =  '<li><div class="li">';
    $link .= "<a href=\"$url\" class=\"action admin\" rel=\"nofollow\" title=\"$text\">$text</a>";
    $link .= '</div></li>';
    return $link;
}

/**
 * Print a single navigation item and associated quick popup
 */
function icke_navi($link,$name='',$class='',$popup='',$active=false,$sep=false){
    // active/separator decorations
    $liclass = '';
    if($active) $liclass .= ' active';
    if($sep) $liclass .= ' separator';
    if($name == 'Settings') $liclass .= ' nomobile';
    $liclass = trim($liclass);
    if($liclass) $liclass = ' class="'.$liclass.'"';

    if(!$name && $link)  $name  = p_get_first_heading($link);
    if(!$class && $link) $class = 'qnav_'.str_replace(':','_',getNS($link));
    if($link) $link = ' href="'.wl($link).'"';

    // output the item
    $liHTML  = '<li'.$liclass.'>';
    $liHTML .= '<a class="qnav_item '.$class.'" '.$link.'>'.hsc($name).'</a>';
    if($popup){
        $liHTML .= '<div class="sec_level">';
        $liHTML .= '<div class="popup_content">';
        $liHTML .= $popup;
        $liHTML .= '</div>';
        $liHTML .= '</div>';
    }
    $liHTML .= '</li>';
    echo $liHTML;
}

/**
 * Populate the navigation side bar from the configured navigation links
 */
function icke_tplSidebar() {
    $navigation = icke_getNavigation();
    $navigation->drawSidebar();
}

function icke_translatedID($id, $mustExist = true) {
    $translation =& plugin_load('action', 'translation');
    if ($translation === null) {
        return $id;
    }

    if ($translation->locale === null) {
        return $id;
    }
    $translatedId = $translation->locale . ":$id";
    if (page_exists($translatedId) || !$mustExist ) {
        return $translatedId;
    }
    return $id;
}

/**
 * Include icons CSS for the navigation and fancy search
 *
 * Looks in the media namespace (ns:icon_on.png, ns:icon_off.png) first,
 * then in the template, then uses a fail image.
 */
function icke_tplCSS() {
    $navigation = icke_getNavigation();
    $navigation->buildPageCss();
}

function icke_processFancySearchItem($id) {
    if(!$id) return false;
    $link = $id;
    resolve_pageid('',$link,$exists);
    if (auth_quickaclcheck($link) < AUTH_READ) return false;
    $ns   = getNS($link);
    if(!$ns) $ns = $link;

    // try to use translated namespaces for translation plug-in
    $class = array_shift(explode(':',$ns));
    $imgClass = $class;
    if (page_exists($link)) {
        $class = icke_translatedID($class, false);
        $class = str_replace(':', '_', $class);
    }
    return array('ns' => $ns, 'class' => $class, 'imgClass' => $imgClass);
}


function icke_tplSearch() {

    $fancysearch = plugin_load('action', 'fancysearch');
    if (is_null($fancysearch)) {
        tpl_searchform(true, false);
        return;
    }

    $navigation = icke_getNavigation();
    $navi = array();
    $navi[''] = 'icke';
    foreach($navigation->navigation as $item){
        if (!($item instanceof IckeNavigationItem)) continue;
        if(strstr($item->id,'%USER%') !== false) continue;
        //$processed = icke_processFancySearchItem($id);
        //if (!$processed) continue;
        $ns = rtrim($item->getNamespace(), ':');
        $class = $item->class;

        $navi[$ns] = $class;
    }

    $fancysearch->tpl_searchform($navi);
}


