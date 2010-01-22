<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php require_once(DOKU_TPLINC.'functions.php') ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
    </title>

    <?php tpl_metaheaders() ?>

</head>
<body>
    <?php html_msgarea() ?>
    <div id="icke__header" class="clearfix">
        <a class="logo" href="<?php echo wl()?>"><img src="<?php echo ml(tpl_getConf('logo'))?>" alt="" /></a>
        <h5>
            <?php echo $conf['title']?><br />
            <span><?php echo tpl_getConf('tagline')?></span>
        </h5>
        <a class="branding" href="http://www.icke-projekt.de/">ICKE - Integrated Collaboration &amp; Knowledge Environment</a>
    </div><!-- END icke__header -->
    <div id="icke__wrapper" class="dokuwiki">
        <ul id="icke__quicknav">
<?php
foreach (array('dashboard'    => array('txt' => 'Dashboard'),
               'fachwissen:'  => array('txt' => 'Fachwissen', 'class' => 'fachwissen', 'liclass' => 'separator'),
               'allgemeines:' => array('txt' => 'Allgemeines', 'class' => 'allgemeines'),
               'projekt:'     => array('txt' => 'Projekte', 'class' => 'projekte'),
               'produkt:'     => array('txt' => 'Produkte', 'class' => 'produkte'),
               'kunde:'       => array('txt' => 'Kunden', 'class' => 'kunden')) as $id => $data) {
    if (!isset($data['class'])) {
        $data['class'] = $id;
    }
    if (!isset($data['quick'])) {
        $data['quick'] = strpos($id, ':') !== false ? str_replace('start', 'quick', $id) : $id . '_quick';
    }
    if(strpos($ID,$id) === 0) $data['liclass'] .= ' active';

    echo '<li' . ($data['liclass'] ? ' class="'.$data['liclass'].'"' : '') .
         '><a class="' . $data['class'] . '" href="' . wl($id) . '">' . $data['txt'] . '</a>';
    icke_tplPopupPage($data['quick']);
    echo '</li>';
}
?>

            <li class="separator"><a class="einstellungen" href="">Einstellungen</a>
                <div class="sec_level">
                    <h1></h1>
                    <ul>
                    <li><?php tpl_actionlink('history'); ?></li>
                    <li><?php tpl_actionlink('recent'); ?></li>
                    <li><?php tpl_actionlink('index'); ?></li>
                    <li><?php tpl_actionlink('backlink'); ?></li>
                    <li><?php tpl_actionlink('subscribe'); ?></li>
                    <li><?php tpl_actionlink('admin'); ?></li>
                    </ul>
                    <div class="sec_level_bottom"></div>
                </div>

            </li>
        </ul><!-- END icke__quicknav -->
        <div class="wrap">
            <ul id="icke__sidebar">
                <li class="logon">
                    <?php
                        if($_SERVER['REMOTE_USER']){
                            echo '<a class="profile" href="'.wl('user:'.$_SERVER['REMOTE_USER']).'">'.hsc($INFO['userinfo']['name']).'</a>';
                        }
                        tpl_actionlink('login');
                    ?>
                </li>
                <li class="search">

                    <form method="post" action="" accept-charset="utf-8">
                        <select class="namespace" name="namespace">
                            <option value="">All</option>
                            <option value="Fachwissen">Fachwissen</option>
                            <option value="Allgemeines">Allgemeines</option>
                            <option value="Projekte">Projekte</option>
                            <option value="Produkte">Produkte</option>
                            <option value="Kunden">Kunden</option>
                        </select>
                        <div id="ns_custom" class="closed" style="display: none;">
                            <ul>
                                <li class=""><img src="<?php echo DOKU_TPL?>images/icons/30x30/icke.png" alt="Alles" /></li>
                                <li class="Fachwissen"><img src="<?php echo DOKU_TPL?>images/icons/30x30/fachwissen_aktiv.png" alt="Fachwissen" /></li>
                                <li class="Allgemeines"><img src="<?php echo DOKU_TPL?>images/icons/30x30/allgemein_aktiv.png" alt="Allgemeines" /></li>
                                <li class="Projekte"><img src="<?php echo DOKU_TPL?>images/icons/30x30/projekte_aktiv.png" alt="Projekte" /></li>
                                <li class="Produkte"><img src="<?php echo DOKU_TPL?>images/icons/30x30/produkte_aktiv.png" alt="Produkte" /></li>
                                <li class="Kunden"><img src="<?php echo DOKU_TPL?>images/icons/30x30/kunden_aktiv.png" alt="Kunden" /></li>
                            </ul>
                        </div>
                        <input type="hidden" name="do" value="search" />
                        <input class="query" id="qsearch__in" type="text" name="id" value="<?echo hsc(preg_replace('/ ?@\S+/','',$QUERY))?>" accesskey="f" />
                        <input class="submit" type="submit" name="submit" value="Search" />
                    </form>

                    <div id="qsearch__out" class="ajax_qsearch JSpopup"></div>
                </li>
                <li class="table_of_contents sideclip">
                    <?php tpl_toc()?>

                    <h3>Tags</h3>

                    <?php
                        $tags = plugin_load('action','tagging');
                        $tags->tpl_tagcloud();
                        $tags->tpl_tagedit();
                    ?>

                </li>

                <?php icke_tplProjectSteps(); ?>

            </ul><!-- END icke__sidebar -->
            <div id="icke__page">
                <div id="icke__drophead" class="closed clearfix">
                    <?php

                    //Quality Control
                    $qc = plugin_load('helper','qc');
                    $qc->tpl();

                    ?>
                </div>
                <div class="content dokuwiki clearfix">
                    <?php if($INFO['exists'] && $ACT == 'show'):?>
                    <p class="meta">
                        <span class="lastmod">
                        <?php
                            if($INFO['lastmod']){
                                echo strftime('%e. %B %Y',$INFO['lastmod']);
                            }
                        ?>
                        </span>
                        <?php if($INFO['user']): ?>
                            <a class="author" href="<?php echo wl('user:'.$INFO['user'])?>"><?php echo hsc($INFO['editor'])?></a>
                        <?php else: ?>
                            <span class="author"><?php echo hsc($INFO['editor'])?></span>
                        <?php endif ?>

                            <?php
                                if($ACT == 'show'){
                                    $starred =& plugin_load('action','starred');
                                    $starred->tpl_starred();
                                }

                                echo '&nbsp;&nbsp;';

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
                        <?php tpl_actionlink('edit')?>
                    </div>
                    <div class="footer_toolbar">
                        <p>
                        </p>
                        <p class="copy">&copy; Copyright 2009 <a href="<?php echo wl('icke')?>">ICKE</a></p>
                    </div>
                </div><!-- END icke__footer -->
            </div><!-- END icke__page -->
        </div><!-- END wrap -->
    </div><!-- END icke__wrapper -->

<?php tpl_indexerWebBug() ?>
</body>
</html>
