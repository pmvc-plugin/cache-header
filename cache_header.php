<?php
namespace PMVC\PlugIn\cache_header;
use PMVC\Event;

\PMVC\l(__DIR__.'/src/CacheHeaderHelper.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\cache_header';

class cache_header extends \PMVC\PlugIn
{
    function init()
    {
        $ocache = new \CacheHeaderHelper();
        $this->setDefaultAlias($ocache);
        \PMVC\callPlugin(
            'dispatcher',
            'attach',
            [ 
                $this,
                Event\B4_PROCESS_VIEW,
            ]
        );
    }

    function onB4ProcessView($subject)
    {
        $subject->detach($this);
        if (isset($this[0])) {
           $headers = call_user_func_array(
            [$this,'getCacheHeader'],
            $this[0]
           ); 
           $view = \PMVC\plug('view');
           $view['headers'] = array_merge(
                $view['headers'],
                $headers
           );
        }
    }
}
