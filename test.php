<?php
namespace PMVC\PlugIn\cache_header;

use PHPUnit_Framework_TestCase;

\PMVC\Load::plug(['controller'=>null]);
\PMVC\addPlugInFolders(['../']);
class CacheHeaderTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'cache_header'; 

    function teardown()
    {
        \PMVC\unplug($this->_plug);
    }

    function testGetNoCacheHeader()
    {
        $headers = \PMVC\plug($this->_plug)->getCacheHeader(0);
        $expected = [
            'Cache-Control: no-store, no-cache, must-revalidate',
            'Expires: Thu, 01 Jan 1970 00:00:00 GMT'
        ];
        $this->assertEquals($expected,$headers);
    }

    function testGetCacheHeader()
    {
        $plug = \PMVC\plug($this->_plug);
        $now = time();
        $headers = $plug->getCacheHeader(1, null, true, $now);
        $modify = $plug->getGmt($now - 86400);
        $expire = $plug->getGmt($now + 1);
        $expected = [
            'Cache-Control: max-age=1, public',
            'Last-Modified: '.$modify,
            'Expires: '. $expire
        ];
        $this->assertEquals($expected,$headers);
    }

    function testSetHeader()
    {
        $now = time();
        \PMVC\option('set', _ROUTER, 'fake_router');
        $router = \PMVC\plug('fake_router',[
            _CLASS=>__NAMESPACE__.'\fakePlugIn'
        ]);
        $pCache = \PMVC\plug($this->_plug,[
            [10, null, $now]
        ]); 
        $pCache->onWillProcessAction();

        $expected = [
            'Cache-Control: max-age=10, public',
            'Last-Modified: '. $pCache->getGmt($now),
            'Expires: '. $pCache->getGmt($now+10)
        ];
        $this->assertEquals($expected, $router['headers']);
    }
}

class fakePlugIn extends \PMVC\PlugIn
{
    function __construct()
    {
        $this['headers'] = [];
    }

    function processHeader($h)
    {
        $this['headers'] = $h;
    }
}

