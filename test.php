<?php
namespace PMVC\PlugIn\cache_header;

use PHPUnit_Framework_TestCase;

\PMVC\Load::plug(['controller'=>null]);
\PMVC\addPlugInFolders(['../']);
class CacheHeaderTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'cache_header'; 
    function testGetCacheHeader()
    {
        $headers = \PMVC\plug($this->_plug)->getCacheHeader(0);
        $expected = 'Cache-Control: no-store, no-cache, must-revalidate';
        $this->assertEquals($expected,$headers[0]);
    }

    function testOnBrProcessView()
    {
        $now = time();
        $view = \PMVC\plug('view',[
            _CLASS=>__NAMESPACE__.'\fakePlugIn'
        ]);
        \PMVC\plug('view_html',[
            _CLASS=>__NAMESPACE__.'\fakePlugIn'
        ]);
        $pCache = \PMVC\plug($this->_plug,[
            [10, 'public', $now]
        ]); 
        \PMVC\option('set',_VIEW_ENGINE,'html');
        $pCache->onB4ProcessView(new fakeSubject());

        $expected = [
            'Cache-Control: max-age=10, public',
            'Last-Modified: '. $pCache->getGmt($now),
            'Expires: '. $pCache->getGmt($now+10)
        ];
        $this->assertEquals($expected, $view['headers']);
    }
}

class fakePlugIn extends \PMVC\PlugIn
{
    function __construct()
    {
        $this['headers'] = [];
    }
}

class fakeSubject {
    function detach()
    {

    }
}
