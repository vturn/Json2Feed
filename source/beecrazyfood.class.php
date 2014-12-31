<?php

class beecrazyfood extends generalHandler {

    public $config = array(
'url' => 'http://www.beecrazy.hk/categories/food',
'isjson' => false,
'outerpattern' => '/<div class=\'row category-section ([^\']+?) hide\' data-category-slug=\'food\' data-section=\'[^\']+?\' data-total-deals=\'([^\']+?)\'>(.+?)<button class=\'btn btn-warning/s',
'outermap' => array('category', 'numofitems', 'htmlstr'),
'rexpattern' => '/<a href=\'([^\']+)\' target=\'\' title=\'([^\']+)\'>.+?src=\'(.+?)\'>.+?<div class=\'deal-original-price number\'>.*?<span class=\"currency\"><span class=\"currency-symbol\">\$<\/span>(.+?)<\/span>.*?<div class=\'deal-discount-price number\'>.*?<span class=\"currency\"><span class=\"currency-symbol\">\$<\/span>(.+?)<\/span>.*?<div class=\'deal-bought\'>.*?<span class=\'deal-num-of-bought number\'>(.+?)<\/span>.*?<\/a>/s',
'rexmap' => array('link', 'title', 'image', 'oprice', 'dprice', 'boughtnum'),
'jrmap' => array(
    //rss -- json
    'title' => 'title',
    'link' => 'link',
    'description' => 'content',
),
'rssconfig' => array(
    'channel' => array(
        'title' => 'Bee Crazy HK',
        'link' => 'http://www.beecrazy.hk',
        'description' => 'Bee Crazy HK',
    ),
),
'output' => 'beecrazyfood',
);

   
    protected function init(){
        $this->name = 'beecrazyfood';
    }

    protected function processWebContent($content){
$result = array();
$items = $this->rexArray($content, $this->config['outerpattern'], $this->config['outermap']);
foreach($items as $item){
$category = $item['category'];
$inneritems = $this->rexArray($item['htmlstr'], $this->config['rexpattern'], $this->config['rexmap']);
foreach ($inneritems as $inneritem){
    $inneritem = array_map('trim', $inneritem);
    $inneritem['category'] = array($category);
    $insert = true;

    //reverse lookup
    foreach ($result as $rkey => $r){
        if ($inneritem['link'] == $r['link']){
            if (!in_array($inneritem['category'][0], $result[$rkey]['category'])){
                $result[$rkey]['category'][] = $category;
            }
            $insert = false;
        }
    }

    if ($insert){
        $result[] = $inneritem;
}
}
}
     // $result = $this->rexArray($content, $this->config['rexpattern'], $this->config['rexmap']);
        return $result;
    }

    protected function processJsonContent($jsons){
        $outputjsons = array();
        foreach($jsons as $json){
            $jsonarray = (array)$json;
            $outputjson = array();
            $outputjson['title'] = $jsonarray['title'];
            $outputjson['link'] = $jsonarray['link'];
            $hash = array('source' => 'beecrazy',
                'category'=> 'food', 
                'subcategory' => $jsonarray['category'], 
                'oprice' => $jsonarray['oprice'], 
                'dprice' => $jsonarray['dprice'], 
                'boughtnum' => $jsonarray['boughtnum'],
                'url' => $jsonarray['link'],);
            $outputjson['content'] = '<img src="' . $jsonarray['image'] . '"><br>--goodbuya--' . base64_encode(serialize($hash)) . '--goodbuya--';
            $outputjsons[] = json_decode(json_encode($outputjson));
        }
        return $outputjsons;
    }
}
?>
