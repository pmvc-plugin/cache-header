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
     function getCacheHeader($timestamp,$type='private',$modificationTimestamp=1){
        $headers = array();
        if($timestamp){
            $headers[]='Cache-Control: max-age='.$timestamp.', '.$type;
            if(!is_null($modificationTimestamp)){
                $modificationGmt =self::getGmt($modificationTimestamp);
                $headers[]='Last-Modified: '.$modificationGmt;
            }
        }else{
            $headers[]='Cache-Control: no-store, no-cache, must-revalidate';
        }
        $cacheGmt = self::getGmt(time()+$timestamp);
        $headers[]='Expires: '.$cacheGmt;
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
