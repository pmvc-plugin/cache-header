<?php
namespace PMVC\PlugIn\cache_header;
class CacheHeaderHelper {

    /** 
     * return gmt string
     */
    function getGmt($timestamp=0){
        $gmt = gmdate( 'D, d M Y H:i:s', $timestamp ).' GMT';
        return $gmt;
    }   

    /**
     * get cache header
     */
     function getCacheHeader($timestamp, $type=null, $modificationTimestamp=true, $now=null){
        $headers = [];
        if (is_null($type)) {
            $type = 'public';
        }
        if (is_null($now)) {
            $now = time();
        }
        if($timestamp){
            $headers[]='Cache-Control: max-age='.$timestamp.', '.$type;
            if ($modificationTimestamp) {
                if (!is_numeric($modificationTimestamp)) {
                    $modificationTimestamp = $now - 86400;
                }
                $modificationGmt =self::getGmt($modificationTimestamp);
                $headers[]='Last-Modified: '.$modificationGmt;
            }
            $expireGmt = self::getGmt($now+$timestamp);
        }else{
            $headers[]='Cache-Control: no-store, no-cache, must-revalidate';
            $expireGmt = self::getGmt(0);
        }
        $headers[]='Expires: '.$expireGmt;
        return $headers;
     }

    /** 
     * set cache header
     * @see getCacheHeader
     */
    function setCache(){
        $args = func_get_args();
        $headers = call_user_func_array(array($this,'getCacheHeader'),$args);
        foreach($headers as $h){
            header($h);
        }
    }

    /**
     * public cache
     */
     function publicCache($timestamp,$modificationTimestamp=null){
        $this->setCache($timestamp,'public',$modificationTimestamp);
     }

    /**
     * private cache
     */
     function privateCache($timestamp,$modificationTimestamp=null){
        $this->setCache($timestamp,'private',$modificationTimestamp);
     }

    /**
     * set no cache
     */
    function noCache(){
        $this->setCache(0);
    }
}
