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

    function testGetCacheHeader()
    {
        $headers = \PMVC\plug($this->_plug)->getCacheHeader(0);
        $expected = 'Cache-Control: no-store, no-cache, must-revalidate';
        $this->assertEquals($expected,$headers[0]);
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
        $pCache->onMapRequest();

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

