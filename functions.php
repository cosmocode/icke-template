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

function icke_tplSidebar() {
    global $ID;
    include DOKU_TPLINC . icke_getFile('namespaces.php');
    if (!isset($icke_ns)) {
        $icke_ns = $icke_namespaces + array('bottom_sep' => array('_special' => 'separator'));
    }
    if (isset($_SERVER['REMOTE_USER'])) {
        $icke_ns = array('dashboard' => array('txt' => 'Dashboard',
                                              'id'  => tpl_getConf('user_ns') .
                                                       $_SERVER['REMOTE_USER'] .
                                                       ':dashboard'),
                         'dashb_sep' => array('_special' => 'separator')) +
                   $icke_ns;
    }

    $hasactive = false;
    $addsep = false;

    foreach ($icke_ns as $class => $data) {
        if (isset($data['_special']) && $data['_special'] === 'separator') {
            $addsep = true;
            continue;
        }
        if (!isset($data['id'])) {
            $data['id'] = $class . ':';
        }
        if (auth_quickaclcheck($data['id']) < AUTH_READ) {
            continue;
        }
        if (!isset($data['liclass'])) {
            $data['liclass'] = '';
        }
        if (!$hasactive && strpos($ID, $data['id']) === 0) {
            $data['liclass'] .= ' active';
            $hasactive = true;
        }
        if ($addsep) {
            $data['liclass'] .= ' separator';
            $addsep = false;
        }

        echo '<li class="'.$data['liclass'].'"><a class="qnav_item ' . $class . '" ' .
             'href="' . wl($data['id']) . '">' . $data['txt'] . '</a>';
        icke_tplPopupPage($data['id'] . (strpos($data['id'], ':') !== false ? 'quick' : '_quick'));
        echo '</li>';
    }

    echo '<li ' . ($addsep ? 'class="separator"' : '') . '><a class="qnav_item einstellungen">Einstellungen</a>';

    $text = '<h1 class="empty"></h1><div class="level2"><ul>';
    include DOKU_TPLINC . icke_getFile('tools.php');
    foreach($icke_tools as $tool) {
        switch ($tool['type']) {
        case 'action':
            $str = tpl_actionlink($tool['value'], '', '', '', true);
            break;
        case 'string':
            $str = $tool['value'];
            break;
        default:
            $str = call_user_func_array($tool['func'], $tool['value']);
        }
        if ($str != '') {
            $text .= '<li>' . $str . '</li>';
        }
    }
    $text .= '</ul></div>';
    icke_tplPopup($text);

    echo '</li>';
}

function icke_tplSearch() {
    include DOKU_TPLINC . icke_getFile('namespaces.php');
    if (!isset($icke_ns)) {
        $icke_ns = $icke_namespaces;
    }
    $search_items = array();
    foreach ($icke_ns as $id => $ns) {
        if (isset($ns['_special'])) continue;
        $ns['img'] = DOKU_TPL . icke_getFile('images/icons/30x30/' . $id . '_aktiv.png');
        $search_items[$id] = $ns;
    }
    $fancysearch = plugin_load('action', 'fancysearch');
    if (!is_null($fancysearch)) {
        $fancysearch->tpl_searchform($search_items, DOKU_TPL . icke_getFile('images/icons/30x30/icke.png'));
    }
}

function icke_tplMenuCSS() {
    include DOKU_TPLINC . icke_getFile('namespaces.php');
    if (!isset($icke_ns)) {
        $icke_ns = $icke_namespaces;
    }

    $nss = array_keys($icke_ns);
    $nss[] = 'dashboard';
    $nss[] = 'einstellungen';
    echo '<style type="text/css">';
    foreach($nss as $ns) {
        if (isset($icke_ns[$ns]['_special'])) continue;
        echo "#icke__quicknav a.$ns {background-image: url(" . DOKU_TPL .
             icke_getFile('images/icons/60x60/' . $ns . '_inaktiv.png') . ");}
              #icke__quicknav li.active a.$ns,
              #icke__quicknav li:hover a.$ns {background-image: url(" . DOKU_TPL .
             icke_getFile('images/icons/60x60/' . $ns . '_aktiv.png') . ");}";
    }
    echo '</style>';
}

function icke_tplFavicon() {
    echo '  <link rel="shortcut icon" href="' . DOKU_TPL . icke_getFile('images/favicon.png') . '" />';
}

function icke_startupCheck() {
    $plugins = plugin_list();
    $requiredPlugins = array('jquery', 'fancysearch');
    foreach ($requiredPlugins as $req) {
        if (in_array($req, $plugins)) continue;
        msg('ICKE-template requires the '. hsc($req).'-plugin.');
    }
}
