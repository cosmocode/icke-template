<?php

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

    $steps = '';
    $ns = $ID;
    do {
        $ns = getNS($ns);
        if(page_exists("$ns:schritt")) {
            $steps = "$ns:schritt";
            break;
        }
        if(page_exists("$ns:schritt:start")) {
            $steps = "$ns:schritt:start";
            break;
        }
    }while($ns);

    if(!$steps) return;


    echo '<li class="sideclip">';
    echo p_wiki_xhtml($steps,'',false);
    echo '</li>';
}

function icke_tplSidebar() {
    global $ID;
    include DOKU_TPLINC . 'local/namespaces.php';
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
                <h5></h5>
                <ul>
                    <li><?php tpl_actionlink('recent'); ?></li>
                    <li><?php tpl_actionlink('index'); ?></li>
                    <li><?php tpl_actionlink('subscribe'); ?></li>
                    <li><?php tpl_actionlink('admin'); ?></li>
                </ul>
                <div class="sec_level_bottom"></div>
            </div>

        </li>
    <?php
}

function icke_tplSearch() {
    include DOKU_TPLINC . 'local/namespaces.php';
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
    include DOKU_TPLINC . 'local/namespaces.php';
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
        $img = 'local/images/icons/60x60/' . $ns . '_inaktiv.png';
        $img2 = 'local/images/icons/60x60/' . $ns . '_aktiv.png';
        if (!file_exists(dirname(__FILE__) . '/' . $img)) {
            $img = 'images/icons/60x60/' . $ns . '_inaktiv.png';
            $img2 = 'images/icons/60x60/' . $ns . '_aktiv.png';
        }
        echo "#icke__quicknav a.$ns {background-image: url(" . DOKU_TPL . "$img);}
              #icke__quicknav li.active a.$ns,
              #icke__quicknav li:hover a.$ns {background-image: url(" . DOKU_TPL . "$img2);}";
    }
    echo '</style>';
}
