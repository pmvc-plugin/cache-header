<?php

namespace PMVC\PlugIn\cache_header;

class CacheHeaderHelper {

    /** 
     * return gmt string
     */
    static public function getGmt($timestamp=0){
        $gmt = gmdate( 'D, d M Y H:i:s', $timestamp ).' GMT';
        return $gmt;
    }   

    /**
     * get cache header
     */
     public function getCacheHeader($sec, $type=null, $modificationTimestamp=true, $now=null){
        $headers = [];
        if (is_null($type)) {
            $type = 'public';
        }
        if (is_null($now)) {
            $now = time();
        }
        if($sec){
            $headers[]='Cache-Control: max-age='. $sec. ', '. $type;
            if ($modificationTimestamp) {
                if (!is_numeric($modificationTimestamp)) {
                    $modificationTimestamp = $now - 86400;
                }
                $modificationGmt =self::getGmt($modificationTimestamp);
                $headers[]='Last-Modified: '.$modificationGmt;
            }
            $expireGmt = self::getGmt($now+ $sec);
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
    public function setCache(){
        $args = func_get_args();
        $headers = call_user_func_array(array($this,'getCacheHeader'),$args);
        foreach($headers as $h){
            header($h);
        }
    }

    /**
     * public cache
     */
     public function publicCache($sec, $modificationTimestamp=null){
        $this->setCache($sec, 'public', $modificationTimestamp);
     }

    /**
     * private cache
     */
     public function privateCache($sec, $modificationTimestamp=null){
        $this->setCache($sec, 'private', $modificationTimestamp);
     }

    /**
     * set no cache
     */
    public function noCache(){
        $this->setCache(0);
    }
}
