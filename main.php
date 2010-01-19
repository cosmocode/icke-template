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
foreach (array('dashboard'        => array('txt' => 'Dashboard'),
               'fachwissen:start' => array('txt' => 'Fachwissen', 'img' => 'fachwissen', 'sep' => true),
               'allgemeines:start'=> array('txt' => 'Allgemeines', 'img' => 'allgemein'),
               'projekt:start'    => array('txt' => 'Projekte', 'img' => 'projects'),
               'produkt:start'    => array('txt' => 'Produkte', 'img' => 'products'),
               'kunde:start'      => array('txt' => 'Kunden', 'img' => 'customers')) as $id => $data) {
    if (!isset($data['img'])) {
        $data['img'] = $id;
    }
    if (!isset($data['quick'])) {
        $data['quick'] = strpos($id, ':') !== false ? str_replace('start', 'quick', $id) : $id . '_quick';
    }
    echo '<li' . (isset($data['sep']) && $data['sep'] ? ' class="separator"' : '') .
         '><a href="' . wl($id) . '"><img src="' . DOKU_TPL . 'images/icons/qn_' . $data['img'] . '.png"' .
         'alt="' . $data['txt'] . '" title="' . $data['txt'] . '" /></a>';
    icke_tplPopupPage($data['quick']);
    echo '</li>';
}
?>

<!--            <li><a href="<?php echo wl('mitarbeiter')?>"><img src="<?php echo DOKU_TPL?>images/icons/qn_personal.png" alt="Personal" /></a></li> -->

            <li class="separator"><a href=""><img src="<?php echo DOKU_TPL?>images/icons/qn_settings.png" alt="Settings" /></a>
                <div class="sec_level">
                    <ul>
                    <li><?php tpl_actionlink('history'); ?></li>
                    <li><?php tpl_actionlink('recent'); ?></li>
                    <li><?php tpl_actionlink('index'); ?></li>
                    <li><?php tpl_actionlink('backlink'); ?></li>
                    <li><?php tpl_actionlink('subscribe'); ?></li>
                    <li><?php tpl_actionlink('admin'); ?></li>
                    </ul>
                </div>

            </li>
        </ul><!-- END icke__quicknav -->
        <div class="wrap">
            <ul id="icke__sidebar">
                <li class="logon">
                    <?php
                        if($_SERVER['REMOTE_USER']){
                            echo '<a class="profile" href="'.wl('user:'.$_SERVER['REMOTE_USER']).'">'.hsc($INFO['userinfo']['name']).'<a>';
                        }
                        tpl_actionlink('login');
                    ?>
                </li>
                <li class="search">

                    <form method="post" action="" accept-charset="utf-8">
                        <select class="namespace" name="namespace">
                            <option value="">All</option>
                            <option value="projekt">Projects</option>
                            <option value="user">Personal</option>
                            <option value="kunde">Customers</option>
                        </select>
                        <div id="ns_custom" class="closed" style="display: none;">
                            <ul>
                                <li class=""><img src="<?php echo DOKU_TPL?>images/icons/qn_dashboard_small.png" alt="Dashboard" /></li>
                                <li class="projects"><img src="<?php echo DOKU_TPL?>images/icons/qn_projects_small.png" alt="Projects" /></li>
                                <li class="personal"><img src="<?php echo DOKU_TPL?>images/icons/qn_personal_small.png" alt="Personal" /></li>
                                <li class="customers"><img src="<?php echo DOKU_TPL?>images/icons/qn_customers_small.png" alt="Customers" /></li>
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
                        </span><br />
                        <?php if($INFO['user']): ?>
                            <a class="author" href="<?php echo wl('user:'.$INFO['user'])?>"><?php echo hsc($INFO['editor'])?></a>
                        <?php else: ?>
                            <span class="author"><?php echo hsc($INFO['editor'])?></span>
                        <?php endif ?>

                            <br />

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
