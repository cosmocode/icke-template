<?php
function icke_header() {
    global $conf;
?>
<body>
    <div id="icke__header">
        <a class="logo" href="<?php echo wl()?>">
          <?php if (tpl_getConf('logo') && file_exists(mediaFN(tpl_getConf('logo')))) {?>
            <img src="<?php echo ml(tpl_getConf('logo'),array('w'=>95,'h'=>95))?>" alt="" />
          <?php } ?>
        </a>
        <h5>
            <?php echo $conf['title']?><br />
            <span><?php echo $conf['tagline']?></span>
        </h5>
        <a class="branding" href="http://www.ickewiki.de">ICKE - Integrated Collaboration &amp; Knowledge Environment</a>
    </div><!-- END icke__header -->

    <div id="icke__toolbar">
        <?php echo (new \dokuwiki\Menu\MobileMenu())->getDropdown(); ?>
        <?php /* tpl_searchform()  FIXME this breaks the other search*/ ?>
    </div>
<?php
}

function icke_sidebar() {
    global $USERINFO;
    global $ACT;
?>
    <div id="icke__wrapper" class="dokuwiki act_<?php echo act_clean($ACT) ?>">
        <ul id="icke__quicknav">
        <?php icke_tplSidebar(); ?>
        </ul><!-- END icke__quicknav -->
        <div class="wrap">
            <ul id="icke__sidebar">
                <li class="logon">
                    <?php
                        if (!empty($_SERVER['REMOTE_USER'])) {
                            echo '<a class="profile" href="'.wl(tpl_getConf('user_ns').$_SERVER['REMOTE_USER'].':') . '">'.hsc($USERINFO['name']).'</a>';
                        }
                        echo (new dokuwiki\Menu\Item\Login())->asHtmlLink(null, false);

                        /** @var helper_plugin_do $doplugin */
                        $doplugin = plugin_load('helper','do');
                        if ($doplugin !== null && isset($_SERVER['REMOTE_USER'])) {
                            $tasks = $doplugin->loadTasks(array('status' => array('undone'),
                                                                'user'   => $_SERVER['REMOTE_USER']));
                            switch (count($tasks)) {
                                case 0:  $class = 'noopentasks'; break;
                                case 1:  $class = 'opentask'; break;
                                default: $class = 'opentasks'; break;
                            }
                            $ignoreme = array();
                            $linktarget = tpl_getConf('tasks_page');
                            if (substr($linktarget, 0, 1) !== ':') {
                                $linktarget = tpl_getConf('user_ns'). $_SERVER['REMOTE_USER'] .
                                              ':' . $linktarget;
                            }
                            echo '<a class="icke__' . $class . '"' .
                                   ' href="' . wl($linktarget) . '">' .
                                 sprintf(tpl_getLang($class), count($tasks)) . '</a>';
                        }
                    ?>
                </li>
                <li class="search">
                    <?php icke_tplSearch(); ?>
                </li>
                <?php
                $toc = tpl_toc(true);
                global $ACT;
                if ($toc || $ACT == 'show') {
                ?>
                <li class="table_of_contents sideclip">
                    <?php
                    echo $toc;
                    if ($ACT == 'show') {
                        /** @var helper_plugin_labeled $labeled */
                        $labeled = plugin_load('helper','labeled');
                        if ($labeled !== null) {
                            $labels = $labeled->tpl_labels();
                            if ($labels) {
                                echo '<h3 class="labeled">'.tpl_getLang('labeled').'</h3>';
                                echo $labels;
                            }

                        }

                        /** @var helper_plugin_tagging $tags */
                        $tags = plugin_load('helper','tagging');
                        if ($tags !== null) {
                            echo '<h3>'.tpl_getLang('tagging').'</h3>';
                            $tags->tpl_tags();
                        }
                    }
                    ?>
                </li>
                <?php } ?>

                <?php icke_tplProjectSteps(); ?>

            </ul><!-- END icke__sidebar -->
<?php
}
