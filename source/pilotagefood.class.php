<?php

class pilotagefood extends generalHandler {

    public $urlprefix = "http://www.pilotage.hk/groupbuy/";

    public $config = array(
'url' => 'http://www.pilotage.hk/groupbuy/?mod=catalog&code=1',
'isjson' => false,
'rexpattern' => '/<div class=\"t_area_out item m_item\" >.+?<div class="at_jrat at_jrat_title"><a href=\"(.+?)\">(.+?)<\/a>.+?class=\"mosaic-backdrop\">.+?<img src=\"(.+?)\".+?已售出<b>(.+?)<\/b>.+?<b class="prime_cost ">\$(.+?)<\/b>.+?現價：<\/span><b>\$(.+?)<\/b>/ss',
'rexmap' => array('link', 'title', 'image', 'boughtnum', 'oprice', 'dprice'),
'jrmap' => array(
    //rss -- json
    'title' => 'title',
    'link' => 'link',
    'description' => 'content',
),
'rssconfig' => array(
    'channel' => array(
        'title' => 'Pilotage HK',
        'link' => 'http://www.pilotage.hk',
        'description' => 'Pilotage HK',
    ),
),
'output' => 'pilotagefood',
);

   
    protected function init(){
        $this->name = 'pilotagefood';
    }

    protected function processWebContent($content){
      $result = $this->rexArray($content, $this->config['rexpattern'], $this->config['rexmap']);
        return $result;
    }

    protected function processJsonContent($jsons){
        $outputjsons = array();
        foreach($jsons as $json){
            $jsonarray = (array)$json;
            $outputjson = array();
            $outputjson['title'] = $jsonarray['title'];
            $outputjson['link'] = $jsonarray['link'];
            $hash = array('source' => 'pilotage',
                'category'=> 'food', 
                //'subcategory' => $jsonarray['category'], 
                'oprice' => $jsonarray['oprice'], 
                'dprice' => $jsonarray['dprice'], 
                'boughtnum' => $jsonarray['boughtnum'],
                'url' => $this->urlprefix . $jsonarray['link'],);
            $outputjson['content'] = '<img src="' . $jsonarray['image'] . '"><br>--goodbuya--' . base64_encode(serialize($hash)) . '--goodbuya--';
            $outputjsons[] = json_decode(json_encode($outputjson));
        }
        return $outputjsons;
    }
}
?>
