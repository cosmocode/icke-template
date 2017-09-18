<?php

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

require_once DOKU_TPLINC.'functions.php';
require_once DOKU_TPLINC.'components.php';

?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
     <?php echo hsc(tpl_img_getTag('IPTC.Headline',$IMG))?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders(); icke_tplCSS(); echo tpl_favicon(array('favicon', 'mobile')); ?>

</head>
<?php
icke_header();
icke_sidebar();
?>
  <div id="icke__page">
                <div id="icke__drophead" class="closed">
                </div>

  <div id="detail__page" class="dokuwiki content page">
    <?php if($ERROR){ print $ERROR; }else{ ?>

    <h1><?php echo hsc(tpl_img_getTag('IPTC.Headline',$IMG))?></h1>

    <p class="back">&larr; <?php echo $lang['img_backto']?> <?php tpl_pagelink($ID)?></p>

    <div class="img_big">
      <?php tpl_img(900,700) ?>
    </div>

    <div class="img_detail">
      <dl class="img_tags">
        <?php
          $t = tpl_img_getTag('Date.EarliestTime');
          if($t) print '<dt>'.$lang['img_date'].':</dt><dd>'.dformat($t).'</dd>';

          $t = tpl_img_getTag('File.Name');
          if($t) print '<dt>'.$lang['img_fname'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag(array('Iptc.Byline','Exif.TIFFArtist','Exif.Artist','Iptc.Credit'));
          if($t) print '<dt>'.$lang['img_artist'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag(array('Iptc.CopyrightNotice','Exif.TIFFCopyright','Exif.Copyright'));
          if($t) print '<dt>'.$lang['img_copyr'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag('File.Format');
          if($t) print '<dt>'.$lang['img_format'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag('File.NiceSize');
          if($t) print '<dt>'.$lang['img_fsize'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag('Simple.Camera');
          if($t) print '<dt>'.$lang['img_camera'].':</dt><dd>'.hsc($t).'</dd>';

          $t = tpl_img_getTag(array('IPTC.Keywords','IPTC.Category','xmp.dc:subject'));
          if($t) print '<dt>'.$lang['img_keywords'].':</dt><dd>'.hsc($t).'</dd>';

        ?>
      </dl>
      <?php //Comment in for Debug// dbg(tpl_img_getTag('Simple.Raw'));?>
    </div>

  <?php } ?>
  </div>
                <div id="icke__footer">
                    <div class="footer_toolbar">
                        <p>
                        </p>
                        <p class="copy"><a href="<?php echo wl('icke')?>">ICKE</a></p>
                    </div>
                </div><!-- END icke__footer -->

</div>
</div>
</body>
</html>

