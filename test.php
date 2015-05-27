<?php
include('vendor/autoload.php');
PMVC\Load::plug();
PMVC\setPlugInFolder('../');
class CacheHeaderTest extends PHPUnit_Framework_TestCase
{
    function testHello()
    {
        $headers = PMVC\plug('cache_header')->getCacheHeader(0);
        $expected = 'Cache-Control: no-store, no-cache, must-revalidate';
        $this->assertEquals($expected,$headers[0]);
    }
}
