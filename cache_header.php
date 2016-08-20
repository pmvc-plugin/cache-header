<?php
namespace PMVC\PlugIn\cache_header;

\PMVC\l(__DIR__.'/src/CacheHeaderHelper.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\cache_header';

class cache_header extends \PMVC\PlugIn
{
    function init()
    {
        $this->setDefaultAlias(new CacheHeaderHelper());
        if (!empty($this[0]) || strlen($this[0])) {
            \PMVC\callPlugin(
                \PMVC\getOption(_ROUTER),
                'processHeader',
                [
                    call_user_func_array(
                        [$this, 'getCacheHeader'],
                        $this[0]
                    )
                ]
            );
        }
    }

}
