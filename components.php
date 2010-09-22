<?php
function icke_header() {
    global $conf;
?>
<body>
    <?php html_msgarea() ?>
    <?php icke_startupCheck() ?>
    <div id="icke__header" class="clearfix">
        <a class="logo" href="<?php echo wl()?>">
          <?php if (tpl_getConf('logo') && file_exists(mediaFN(tpl_getConf('logo')))) {?>
            <img src="<?php echo ml(tpl_getConf('logo'))?>" alt="" />
          <?php } ?>
        </a>
        <h5>
            <?php echo $conf['title']?><br />
            <span><?php echo tpl_getConf('tagline')?></span>
        </h5>
        <a class="branding" href="http://www.ickewiki.de">ICKE - Integrated Collaboration &amp; Knowledge Environment</a>
    </div><!-- END icke__header -->
<?php
}

function icke_sidebar() {
    global $USERINFO;
?>
    <div id="icke__wrapper" class="dokuwiki">
        <ul id="icke__quicknav">
        <?php icke_tplSidebar(); ?>
        </ul><!-- END icke__quicknav -->
        <div class="wrap">
            <ul id="icke__sidebar">
                <li class="logon">
                    <?php
                        if($_SERVER['REMOTE_USER']){
                            echo '<a class="profile" href="'.wl(tpl_getConf('user_ns').$_SERVER['REMOTE_USER'].':') . '">'.hsc($USERINFO['name']).'</a>';
                        }
                        tpl_actionlink('login');

                        $doplugin = plugin_load('helper','do');
                        if ($doplugin !== null && isset($_SERVER['REMOTE_USER'])) {
                            $tasks = $doplugin->loadTasks(array('status' => 'undone',
                                                                'user'   => $_SERVER['REMOTE_USER']));
                            if (count($tasks) > 0) {
                                $ignoreme = array();
                                echo '<div class="icke__opentasks">';
                                printf(tpl_getLang(count($tasks) === 1 ? 'opentask' : 'opentasks'),
                                       wl(tpl_getConf('user_ns').$_SERVER['REMOTE_USER'].':dashboard') .
                                        '#' . sectionID(tpl_getConf('dashboard_task_section'), $ignoreme),
                                       count($tasks));
                                echo '</div>';
                            }
                        }
                    ?>
                    <div class="clearfix"></div>
                </li>
                <li class="search">
                    <?php icke_tplSearch(); ?>
                </li>
                <?php
                $toc = tpl_toc(true);
                global $ACT;
                if ($toc || $ACT == 'show') {
                ?>
                <li class="table_of_contents sideclip clearfix">
                    <?php
                    echo $toc;
                    if ($ACT == 'show') {
                        $tags = plugin_load('helper','tagging');
                        if ($tags !== null) {
                            echo '<h3>Tags zu dieser Seite</h3>';
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
