<?php
namespace PMVC\PlugIns;

\PMVC\l(__DIR__.'/cache_header_helper.php');

${_INIT_CONFIG}[_CLASS] = 'PMVC\PlugIns\PMVC_PLUGIN_CacheHeader';

class PMVC_PLUGIN_CacheHeader extends \PMVC\PLUGIN
{
    function init(){
        $ocache = new \CacheHeaderHelper();
        $this->setDefaultAlias($ocache);
    }
}
