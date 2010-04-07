<?php

function icke_getFile($name) {
    return file_exists(DOKU_TPLINC . 'local/' . $name) ?
           'local/' . $name : $name;
}

function icke_tplPopupPage($id){
    $page = p_wiki_xhtml($id,'',false);
    if($page) {
        echo '<div class="sec_level">';
        echo $page;
        echo '<div class="sec_level_bottom"></div>';
        echo '</div>';
    }
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
    if (isset($_SERVER['REMOTE_USER'])) {
        $firstkey = reset(array_keys($icke_namespaces));
        $icke_namespaces = array_merge(array('dashboard' => array
                                                ('txt' => 'Dashboard',
                                                 'id' => tpl_getConf('user_ns') .
                                          $_SERVER['REMOTE_USER'] .
                                          ':dashboard')),
                                    $icke_namespaces);
        $icke_namespaces[$firstkey]['liclass'] = 'separator';
    }

    $hasactive = false;

    foreach ($icke_namespaces as $class => $data) {
        if (!isset($data['id'])) {
            $data['id'] = $class . ':';
        }
        if (!$hasactive && strpos($ID, $data['id']) === 0) {
            $data['liclass'] .= ' active';
            $hasactive = true;
        }
        if (auth_quickaclcheck($data['id']) < AUTH_READ) {
            continue;
        }

        echo '<li' . ($data['liclass'] ? ' class="'.$data['liclass'].'"' : '') .
             '><a class="' . $class . '" href="' . wl($data['id']) . '">' . $data['txt'] . '</a>';
        icke_tplPopupPage($data['id'] . (strpos($data['id'], ':') !== false ? 'quick' : '_quick'));
        echo '</li>';
    }
    ?>
        <li class="separator"><a class="einstellungen">Einstellungen</a>
            <div class="sec_level">
                <h1 class="empty"></h1>
                <ul>
<?php
                include DOKU_TPLINC . icke_getFile('tools.php');
                foreach($icke_tools as $tool) {
                    echo '<li>';
                    switch ($tool['type']) {
                    case 'action':
                        tpl_actionlink($tool['value']);
                        break;
                    case 'string':
                        echo $tool['value'];
                        break;
                    default:
                        echo call_user_func_array($tool['func'], $tool['value']);
                    }
                    echo '</li>';
                }
?>
                </ul>
                <div class="sec_level_bottom"></div>
            </div>

        </li>
    <?php
}

function icke_tplSearch() {
    include DOKU_TPLINC . icke_getFile('namespaces.php');
    $cur_val = isset($_POST['namespace']) ? $_POST['namespace'] : '';
?>        <select class="namespace" name="namespace">
            <option value="" <?php if ($cur_val === '') echo 'selected'; ?>>All</option> <?php
foreach ($icke_namespaces as $id => $ns) {
echo '<option value="' . $id . '"' . ($cur_val === $id ? ' selected' : '') . '>' . $ns['txt'] . '</option>';
}
?>
        </select>
        <div id="ns_custom" class="closed" style="display: none;">
            <ul>
                <li class=""><img src="<?php echo DOKU_TPL?>images/icons/30x30/icke.png" alt="Alles" /></li>
<?php
foreach ($icke_namespaces as $class => $ns) {
echo '<li class="' . $class . '_search"><img src="' . DOKU_TPL . 'local/images/icons/30x30/' . $class . '_aktiv.png" alt="' . $ns['txt'] . '" /></li>';
}
?>
            </ul>
        </div>
<?php
}

function icke_tplMenuCSS() {
    include DOKU_TPLINC . icke_getFile('namespaces.php');
    echo '<style type="text/css">';
    $nss = array_keys($icke_namespaces);
    $nss[] = 'dashboard';
    $nss[] = 'einstellungen';
    $str = ''; 
    foreach($nss as $ns) {
         $str .= '#icke__quicknav a.' . $ns . ",\n";
    }
    echo rtrim($str, ",\n");
    ?>
    {
        background: transparent top left no-repeat;
        display: block;
        height: 60px;
        text-indent: -9999px;
        width: 60px;
    }
    <?php
    foreach($nss as $ns) {
        echo "#icke__quicknav a.$ns {background-image: url(" . DOKU_TPL .
             icke_getFile('images/icons/60x60/' . $ns . '_inaktiv.png') . ");}
              #icke__quicknav li.active a.$ns,
              #icke__quicknav li:hover a.$ns {background-image: url(" . DOKU_TPL .
             icke_getFile('images/icons/60x60/' . $ns . '_aktiv.png') . ");}";
    }
    echo '</style>';
}
