<?php

$config = array(
'url' => 'http://alldeals.groupon.hk/zh/_shopping?view=listing',
'isjson' => true,
'rexpattern' => '',
'rexmap' => array(),
'jrmap' => array(
    //rss -- json
    'title' => 'title',
    'link' => 'url',
    'description' => 'description',
),
'rssconfig' => array(
    'channel' => array(
        'title' => 'Feed Title',
        'link' => 'http://www.google.com',
        'description' => 'Feed Description',
    ),
),
'output' => 'grouponshop',
);

function grouponshop($jsons){
$outputjsons = array();
foreach($jsons as $json){
$jsonarray = (array)$json;
$outputjson = array();
$outputjson['title'] = $jsonarray['title'];
$outputjson['url'] = $jsonarray['url'];
$outputjson['description'] = '<img src="' . $jsonarray['image_large_url'] . '"><br>' . $jsonarray['title'];
$outputjsons[] = json_decode(json_encode($outputjson));
}
return $outputjsons;
}


?>
