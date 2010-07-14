<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php require_once(DOKU_TPLINC.'functions.php') ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
    </title>
    <?php tpl_metaheaders(); icke_tplMenuCSS(); icke_tplFavicon(); ?>
</head>
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
        <a class="branding" href="http://www.icke-projekt.de/">ICKE - Integrated Collaboration &amp; Knowledge Environment</a>
    </div><!-- END icke__header -->
    <div id="icke__wrapper" class="dokuwiki">
        <ul id="icke__quicknav">
        <?php icke_tplSidebar(); ?>
        </ul><!-- END icke__quicknav -->
        <div class="wrap">
            <ul id="icke__sidebar">
                <li class="logon">
                    <?php
                        if($_SERVER['REMOTE_USER']){
                            echo '<a class="profile" href="'.wl(tpl_getConf('user_ns').$_SERVER['REMOTE_USER'].':') . '">'.hsc($INFO['userinfo']['name']).'</a>';
                        }
                        tpl_actionlink('login');
                    ?>
                </li>
                <li class="search">
                    <?php icke_tplSearch(); ?>
                </li>
                <li class="table_of_contents sideclip clearfix">
                    <?php tpl_toc()?>

                    <?php
                        $tags = plugin_load('action','tagging');
                        if ($tags !== null) {
                            echo '<h3>Tags</h3>';
                            $tags->tpl_tagcloud();
                            $tags->tpl_tagedit();
                        }
                    ?>

                </li>

                <?php icke_tplProjectSteps(); ?>

            </ul><!-- END icke__sidebar -->
            <div id="icke__page">
                <div id="icke__drophead" class="closed clearfix">
                    <?php

                    //Do Plugin
                    $doplugin = plugin_load('helper','do');
                    if ($doplugin !== null) {
                        $doplugin->tpl_pageTasks();
                    }

                    //Quality Control
                    $qc = plugin_load('helper','qc');
                    if ($qc !== null) {
                        $qc->tpl();
                    }

                    ?>
                </div>
                <div class="content dokuwiki clearfix">
                    <?php if ($ACT === 'show'): ?>
                        <p class="meta">
                    <?php if($INFO['exists']):?>

                        <span class="lastmod">
                        <?php
                            if($INFO['lastmod']){
                                // %e is not supported on Windows
                                echo ltrim(strftime('%d. %B %Y',$INFO['lastmod']), '0');
                            }
                        ?>
                        </span> –
                        <?php if($INFO['sum']): ?>
                            <span class="sum"><?php echo hsc($INFO['sum']); ?></span> –
                        <?php endif ?>
                        <?php if($INFO['user']): ?>
                            <a class="author" href="<?php echo wl(tpl_getConf('user_ns').$INFO['user'] . ':')?>"><?php echo editorinfo($INFO['editor'])?></a>
                        <?php else: ?>
                            <span class="author"><?php echo hsc($INFO['editor'] ? $INFO['editor'] : 'Ursprungsversion'); ?></span>
                        <?php endif ?>
                    <?php endif ?>

                            <?php
                                $starred =& plugin_load('action','starred');
                                if ($starred !== null) {
                                    $starred->tpl_starred();
                                    echo '&nbsp;&nbsp;';
                                }

                                if($INFO['subscribed']){
                                    $sub = '<img src="'.DOKU_TPL.'images/mail-yes.png" width="16" height="16" alt="" title="'.$lang['btn_subscribe'].'" />';
                                }else{
                                    $sub = '<img src="'.DOKU_TPL.'images/mail-no.png" width="16" height="16" alt="" title="'.$lang['btn_subscribe'].'" />';
                                }
                                tpl_actionlink('subscribe','','',$sub);
                            ?>

                    </p>
                    <?php endif?>

                    <div class="page">
                    <?php tpl_content(false)?>
                    </div>

                </div>
                <div id="icke__footer">
                    <div class="admin_toolbar">
                        <?php tpl_actionlink('edit'); ?>
                        <?php tpl_actionlink('backlink'); ?>
                        <?php tpl_actionlink('history'); ?>
                    </div>
                    <div class="footer_toolbar">
                        <p>
                        </p>
                        <p class="copy">&copy; Copyright 2009–2010 <a href="<?php echo wl('icke')?>">ICKE</a></p>
                    </div>
                </div><!-- END icke__footer -->
            </div><!-- END icke__page -->
        </div><!-- END wrap -->
    </div><!-- END icke__wrapper -->

<?php tpl_indexerWebBug() ?>
</body>
</html>

