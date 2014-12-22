<?php
require_once('JsonRss.class.php');
date_default_timezone_set('Asia/Hong_Kong');

//JSON URL
$url = 'http://alldeals.groupon.hk/zh/_shopping?view=listing';
//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

$jsonstrs = json_decode($result);
$mapping = array(
//rss -- json
'title' => 'title',
'link' => 'url',
'description' => 'title',
'image' => array(
'url' => 'image_large_url',
'title' => 'meta_title',
'link' => 'url',
)
);

$rssconfig = array(
    'channel' => array(
        'title' => 'Feed Title',
        'link' => 'http://www.google.com',
        'description' => 'Feed Description',
    ),
);

$jsonrss = new JsonRss($rssconfig, $jsonstrs, $mapping);
$content = $jsonrss->prepareRss();
header('Content-Type: text/xml');
$handle = fopen('feeds/example.xml', 'w');
fwrite($handle, $content);
fclose($handle);
?>
