<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php require_once(DOKU_TPLINC.'functions.php') ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
    </title>

    <?php tpl_metaheaders(); icke_tplMenuCSS(); ?>
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

                    <form method="post" action="" accept-charset="utf-8">
                        <?php icke_tplSearch(); ?>
                        <input type="hidden" name="do" value="search" />
                        <input class="query" id="qsearch__in" type="text" name="id" autocomplete="off" value="<?echo hsc(preg_replace('/ ?@\S+/','',$QUERY))?>" accesskey="f" />
                        <input class="submit" type="submit" name="submit" value="Search" />
                    </form>

                    <div id="qsearch__out" class="ajax_qsearch JSpopup"></div>
                </li>
                <li class="table_of_contents sideclip clearfix">
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
                    <?php if ($ACT === 'show'): ?>
                        <p class="meta">
                    <?php if($INFO['exists']):?>

                        <span class="lastmod">
                        <?php
                            if($INFO['lastmod']){
                                echo strftime('%e. %B %Y',$INFO['lastmod']);
                            }
                        ?>
                        </span> – 
                        <?php if($INFO['user']): ?>
                            <a class="author" href="<?php echo wl(tpl_getConf('user_ns').$INFO['user'] . ':')?>"><?php echo hsc($INFO['editor'])?></a>
                        <?php else: ?>
                            <span class="author"><?php echo hsc($INFO['editor'])?></span>
                        <?php endif ?>
                    <?php endif ?>

                            <?php
                                $starred =& plugin_load('action','starred');
                                $starred->tpl_starred();

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
