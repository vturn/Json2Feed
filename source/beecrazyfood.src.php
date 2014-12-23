<?php

$config = array(
'url' => 'http://www.beecrazy.hk/categories/food',
'isjson' => false,
//'rexpattern' => '/<a href=\'([^\']+)\' target=\'\' title=\'([^\']+)\'>(.+?)<\/a>/s',
'rexpattern' => '/<a href=\'([^\']+)\' target=\'\' title=\'([^\']+)\'>(.+?)src=\'(.+?)\'>(.+?)<div class=\'deal-discount-price number\'>(.+?)<\/div>(.+?)<\/a>/s',
'rexmap' => array('link', 'title', 'dummya', 'image', 'dummyb', 'price', 'dummyc'),
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

function beecrazyfood($jsons){
$outputjsons = array();
foreach($jsons as $json){
$jsonarray = (array)$json;
$outputjson = array();
$outputjson['title'] = $jsonarray['title'];
$outputjson['link'] = $jsonarray['link'];
$outputjson['content'] = '<img src="' . $jsonarray['image'] . '"><br>' . $jsonarray['price'] . '<br>' . $jsonarray['title'];
$outputjsons[] = json_decode(json_encode($outputjson));
}
return $outputjsons;

}

?>
