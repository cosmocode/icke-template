<!DOCTYPE html>
<?php
if (!defined('DOKU_INC')) die(); /* must be run from within DokuWiki */
require_once DOKU_TPLINC.'functions.php';
require_once DOKU_TPLINC.'components.php';
global $conf;
?>
<html lang="<?php echo $conf['lang'] ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php tpl_metaheaders(); icke_tplCSS(); echo tpl_favicon(array('favicon', 'mobile')) ?>
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
                <div id="icke__drophead" class="closed">
                    <?php

                    /** @var helper_plugin_translation $translation */
                    $translation = plugin_load('helper','translation');
                    if ($translation !== null) {
                        echo $translation->showTranslations();
                    }

                    /** @var helper_plugin_do $doplugin */
                    $doplugin = plugin_load('helper','do');
                    if ($doplugin !== null) {
                        $doplugin->tpl_pageTasks();
                    }

                    /** @var helper_plugin_qc $qc */
                    $qc = plugin_load('helper','qc');
                    if ($qc !== null) {
                        $qc->tpl();
                    }

                    ?>
                </div>
                <?php html_msgarea() ?>
                <div class="content">
                    <?php if ($ACT === 'show'): ?>
                        <div class="bc"><?php tpl_youarehere(); ?></div>
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
                                    <a class="author" href="<?php echo wl(tpl_getConf('user_ns').$INFO['user'] . ':')?>"><?php echo editorinfo($INFO['editor'], true)?></a>
                                <?php else: ?>
                                    <span class="author"><?php echo hsc($INFO['editor'] ? $INFO['editor'] : tpl_getLang('current version')); ?></span>
                                <?php endif ?>
                            <?php endif ?>

                            <?php


                                /** @var action_plugin_starred $starred */
                                $starred = plugin_load('action','starred');
                                if ($starred !== null) {
                                    $starred->tpl_starred();
                                    echo '&nbsp;&nbsp;';
                                }

                                /** @var helper_plugin_quicksubscribe $quicksubscribe */
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
                    <div class="page_toolbar">
                        <ul class="page-menu">
                            <?php echo (new \dokuwiki\Menu\PageMenu())->getListItems('dw-menu-item page-menu__item '); ?>
                        </ul>
                    </div>
                    <div class="footer_toolbar">
                        <p>
                        </p>
                        <p class="copy"><a href="http://www.ickewiki.de">ICKE</a></p>
                    </div>
                </div><!-- END icke__footer -->
            </div><!-- END icke__page -->
        </div><!-- END wrap -->
    </div><!-- END icke__wrapper -->

<?php tpl_indexerWebBug() ?>
</body>
</html>

