<?php

require_once dirname(__FILE__) . '/../IckeNavigation.php';

/**
 * @group template_icke-template
 * @group templates
 */
class IckeTemplateNavigationTest extends DokuWikiTest {

    public function testLoadNavigation() {
        $config = 'user:%USER%:dashboard ,, allgemeines:, fachwissen:, projekt:,kunde:, ,hilfe:';

        $_SERVER['REMOTE_USER'] = '';
        plugin_disable('translation');

        $navigation = new IckeNavigation();
        $actual = $navigation->loadNavigation($config);
        $expected = array(
            new IckeNavigationSeparator(),
            new IckeNavigationItem('allgemeines:', 'allgemeines:start', false),
            new IckeNavigationItem('fachwissen:', 'fachwissen:start', false),
            new IckeNavigationItem('projekt:', 'projekt:start', false),
            new IckeNavigationItem('kunde:', 'kunde:start', false),
            new IckeNavigationSeparator(),
            new IckeNavigationItem('hilfe:', 'hilfe:start', false)
        );

        $this->assertEquals($expected, $actual);
    }

    public function testLoadNavigationUser() {
        $config = 'user:%USER%:dashboard ,, allgemeines:, fachwissen:, projekt:,kunde:, ,hilfe:';

        $_SERVER['REMOTE_USER'] = 'user';
        plugin_disable('translation');

        $navigation = new IckeNavigation();
        $actual = $navigation->loadNavigation($config);
        $expected = array(
            new IckeNavigationItem('user:%USER%:dashboard', 'user:user:dashboard', false),
            new IckeNavigationSeparator(),
            new IckeNavigationItem('allgemeines:', 'allgemeines:start', false),
            new IckeNavigationItem('fachwissen:', 'fachwissen:start', false),
            new IckeNavigationItem('projekt:', 'projekt:start', false),
            new IckeNavigationItem('kunde:', 'kunde:start', false),
            new IckeNavigationSeparator(),
            new IckeNavigationItem('hilfe:', 'hilfe:start', false)
        );

        $this->assertEquals($expected, $actual);
    }

    public function testLoadNavigationTranslation() {
        $config = 'user:%USER%:dashboard ,, allgemeines:, fachwissen:, projekt:,kunde:, ,hilfe:';

        $_SERVER['REMOTE_USER'] = '';
        plugin_enable('translation');

        $_SESSION[DOKU_COOKIE]['translationlc'] = 'en';
        $this->touchWiki('en:allgemeines:start');
        $this->touchWiki('en:fachwissen:start');
        $this->touchWiki('en:projekt:start');
        $this->touchWiki('en:kunde:start');
        $this->touchWiki('en:hilfe:start');

        $navigation = new IckeNavigation();
        $actual = $navigation->loadNavigation($config);
        $expected = array(
            new IckeNavigationSeparator(),
            new IckeNavigationItem('allgemeines:', 'en:allgemeines:start', false),
            new IckeNavigationItem('fachwissen:', 'en:fachwissen:start', false),
            new IckeNavigationItem('projekt:', 'en:projekt:start', false),
            new IckeNavigationItem('kunde:', 'en:kunde:start', false),
            new IckeNavigationSeparator(),
            new IckeNavigationItem('hilfe:', 'en:hilfe:start', false)
        );

        $this->assertEquals($expected, $actual);
    }

    private function touchWiki($id) {
        io_mkdir_p(wikiFN($id));
        touch(wikiFN($id));
    }

    public function testNavigationItemClassNS() {
        $_SESSION[DOKU_COOKIE]['translationlc'] = 'en';
        $item = new IckeNavigationItem('allgemeines:', 'allgemeines:start', false);
        $this->assertEquals('allgemeines', $item->class);
    }

    public function testNavigationItemClassPage() {
        $_SESSION[DOKU_COOKIE]['translationlc'] = 'en';
        $item = new IckeNavigationItem('allgemeines', 'allgemeines', false);
        $this->assertEquals('allgemeines', $item->class);
    }

    public function testNavigationItemClassNested() {
        $_SESSION[DOKU_COOKIE]['translationlc'] = 'en';
        $item = new IckeNavigationItem('user:%USER%:dashboard', 'user:name:dashboard', false);
        $this->assertEquals('user', $item->class);
    }


}
