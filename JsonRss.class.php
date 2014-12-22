<?php

require_once('RssCreator/RssCreator.class.php');

class JsonRss{

    const VERSION = '1.0.0';
    private $jrmap = array();
    private $config = array();
    private $jsonstrs = '';
    private $rss_items = array();

    public function __construct($config, $jsons, $mapping){
        $this->config = $config;
        $this->jsonstrs = $jsons;
        $this->jrmap = $mapping;
        if (empty($jrmap) || empty($config) || $jsonstrs = ''){
            return false;
        }
    }

    public function prepareRss(){
        foreach ($this->jsonstrs as $jsonobj){
            $jsonarray = (array)$jsonobj;
            $rss_item = array();
            foreach ($this->jrmap as $rss => $json){
                if (is_array($json)){
                    foreach ($json as $subrss => $subjson){
                        $rss_item[$rss][$subrss] = $jsonarray[$subjson];
                    }
                } else {
                    $rss_item[$rss] = $jsonarray[$json];
                }
            }
            $this->rss_items[] = $rss_item;
        }
        return $this->createRss();
    }
 
    public function createRss(){
        $rss = new RssCreator($this->config, $this->rss_items);
        return $rss->create_feed();
    } 
} 

?>
