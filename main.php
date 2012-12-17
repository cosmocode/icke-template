<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once DOKU_TPLINC.'functions.php';
require_once DOKU_TPLINC.'components.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
    </title>
    <?php tpl_metaheaders(); icke_tplCSS(); icke_tplFavicon(); ?>
</head>
<?php
// render the content into buffer for later use (see doku>devel:templates#tpl_toc
ob_start();
tpl_content(false);
$buffer = ob_get_clean();

icke_header();
icke_sidebar();
?>
            <div id="icke__page">
                <div id="icke__drophead" class="closed clearfix">
                    <?php

                    $translation =& plugin_load('helper','translation');
                    if ($translation !== null) {
                        echo $translation->showTranslations();
                    }

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
                <div class="content clearfix">
                    <?php if ($ACT === 'show'): ?>
                        <div><?php tpl_youarehere(); ?></div>
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

                                $quicksubscribe = plugin_load('helper', 'quicksubscribe');
                                if ($quicksubscribe !== null) {
                                    $quicksubscribe->tpl_subscribe();
                                }
                            ?>
                        </p>

                    <?php endif?>

                    <div class="page">
                        <?php echo $buffer ?>
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
                        <p class="copy">&copy; Copyright 2009–2011 <a href="http://www.ickewiki.de">ICKE</a></p>
                    </div>
                </div><!-- END icke__footer -->
            </div><!-- END icke__page -->
        </div><!-- END wrap -->
    </div><!-- END icke__wrapper -->

<?php tpl_indexerWebBug() ?>
</body>
</html>

