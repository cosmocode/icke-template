<?php

class IckeNavigationElement {}

class IckeNavigationSeparator extends IckeNavigationElement {}

class IckeNavigationItem extends IckeNavigationElement {

    public $id;
    public $link;
    public $exists;
    public $isNamespace;
    public $class;

    function __construct($id, $link, $isNamespace) {
        $this->id = $id;
        $this->link = $link;
        $this->isNamespace = $isNamespace;
        $this->class = $this->buildClass($id);
        $this->exists = page_exists($this->link);
    }

    private function buildClass($id) {
        $id = ltrim($id, ':');
        $pos = strpos($id, ':');
        if ($pos === false) return $id;
        return substr($id, 0, $pos);
    }

    public function getNamespace() {
        if (!$this->isNamespace) return $this->id;
        return getNS($this->id);
    }
}

class IckeNavigation {

    public $navigation;

    public function __construct() {
        $navigationConfig = tpl_getConf('namespaces');
        $this->loadNavigation($navigationConfig);
    }

    public function loadNavigation($navigationConfig) {
        global $conf;

        $navigation = explode(',',$navigationConfig);
        $navigation = array_map('trim', $navigation);

        $this->navigation = array();
        foreach ($navigation as $id) {
            if ($id === '') {
                // add separator
                $this->navigation[] = new IckeNavigationSeparator();
                continue;
            }

            $link = $this->replaceUserPlaceholder($id);
            if ($link === false) continue;

            resolve_pageid('',$link,$exists); // create full links
            if (auth_quickaclcheck($link) < AUTH_READ) continue;

            $isNamespace = false;
            if (substr($link, -1) === ':') {
                $isNamespace = true;
                $link = $link . $conf['start'];
            }

            $link = $this->translateId($link);
            $link = cleanID($link);

            $this->navigation[] = new IckeNavigationItem($id, $link, $isNamespace);
        }
        return $this->navigation;
    }

    private function replaceUserPlaceholder($id) {
        if(strstr($id,'%USER%') === false) {
            return $id;
        }

        if(empty($_SERVER['REMOTE_USER'])) {
            return false;
        }

        $id = str_replace('%USER%',$_SERVER['REMOTE_USER'],$id);
        return $id;
    }

    private function translateId($id) {
        $translation = $this->getCurrentTranslation();

        $translatedId = $translation . ":$id";
        if (page_exists($translatedId)) {
            return $translatedId;
        }
        return $id;
    }

    private function getCurrentTranslation() {
        if (!isset($_SESSION[DOKU_COOKIE]['translationlc']) || empty($_SESSION[DOKU_COOKIE]['translationlc'])) {
            return '';
        }
        return $_SESSION[DOKU_COOKIE]['translationlc'];
    }

    public function drawSidebar() {
        global $ID;

        $firstItem = true;
        $separator = false;
        foreach($this->navigation as $item){
            if ($item instanceof IckeNavigationSeparator) {
                $separator = !$firstItem;
                $firstItem = false;
                continue;
            }
            $firstItem = false;
            if ($item instanceof IckeNavigationItem) {

                $namespace = $this->replaceUserPlaceholder(getNS($item->id));

                $popup  = p_wiki_xhtml($this->getLocalizedPopup($namespace.':quick'),'',false);
                $translationNs = preg_quote($this->getCurrentTranslation().':', '/');
                $active = (bool) preg_match('/^('.$translationNs.')?'.preg_quote($namespace,'/').':/',$ID);
                $class = 'qnav_'.explode(':',$item->class)[0]; // first ns part
                icke_navi($item->link, '', $class, $popup, $active, $separator);

                $separator = false;
            }
        }

        // Add toolbox
        icke_navi('','Settings','qnav_einstellungen',icke_toolbox(),false,false);
    }

    private function getLocalizedPopup($id) {
        $lang = $this->getCurrentTranslation();
        if ($lang === '') return $id;

        $local = "$lang:$id";
        if (page_exists($local)) {
            return $local;
        }
        return $id;
    }

    public function buildPageCss() {
        echo "<style type=\"text/css\">\n";

        // $navi[] = "einstellungen";

        foreach($this->navigation as $navigationItem){

            if (!($navigationItem instanceof IckeNavigationItem)) {
                continue;
            }

            /*$processed = icke_processFancySearchItem($id);
            if (!$processed) continue;
            $class = $processed['class'];
            $ns = $processed['ns'];
            $imgClass = $processed['imgClass'];*/



            $bigInactive = $this->getImage($navigationItem, 60, 'icon_off.png', '_inaktiv.png');
            $bigActive = $this->getImage($navigationItem, 60, 'icon_on.png', '_aktiv.png');
            $iconActive = $this->getImage($navigationItem, 30, 'icon_on.png', '_aktiv.png');

            echo $this->cssEntry($navigationItem->class, $bigInactive, $bigActive, $iconActive);
        }

        echo $this->cssEntry('einstellungen', DOKU_TPL . '/images/icons/60x60/einstellungen_inaktiv.png', DOKU_TPL . '/images/icons/60x60/einstellungen_aktiv.png', '');
        echo "#fancysearch__ns_custom li.fancysearch_ns_icke {";
        echo 'text-indent: -10000px; width:30px; height:30px;';
        echo 'background-image: url('.DOKU_TPL.'/images/icons/30x30/icke.png)';
        echo "}\n";

        echo "</style>\n";
    }

    private function getImage(IckeNavigationItem $navigationItem, $size, $nsPostFix, $pathPostFix) {
        if (file_exists(mediaFN($navigationItem->getNamespace() . $nsPostFix))) {
            return ml($navigationItem->getNamespace() . $nsPostFix, array('w'=>$size,'h'=>$size), true, '&');
        }

        $path = "/images/icons/{$size}x$size/".$navigationItem->class.$pathPostFix;
        if (file_exists(DOKU_TPLINC . $path)) {
            return DOKU_TPL . $path;
        }
        return DOKU_TPL . "/images/icons/{$size}x$size/fail.png";
    }

    private function cssEntry($name, $bigInactive, $bigActive, $iconActive) {
        $css = '
            #icke__quicknav a.qnav_%1$s {
                background-image: url(%2$s)
            }
            #icke__quicknav li.active a.qnav_%1$s,
            #icke__quicknav li:hover a.qnav_%1$s {
                background-image: url(%3$s)
            }
            #fancysearch__ns_custom li.fancysearch_ns_%1$s {
                text-indent: -10000px;
                width:30px;
                height:30px;
                background-image: url(%4$s)
            }';
        return sprintf($css, $name, $bigInactive, $bigActive, $iconActive);
    }

}

function icke_getNavigation() {
    static $navigation = null;
    if ($navigation === null) {
        $navigation = new IckeNavigation();
    }
    return $navigation;
}