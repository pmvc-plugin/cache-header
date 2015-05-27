<?php
namespace PMVC\PlugIn\cache_header;

\PMVC\l(__DIR__.'/CacheHeaderHelper.php');

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIn\cache_header\cache_header';

class cache_header extends \PMVC\PlugIn
{
    function init(){
        $ocache = new \CacheHeaderHelper();
        $this->setDefaultAlias($ocache);
    }
}
