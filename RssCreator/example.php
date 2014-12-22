<?php
require_once('RssCreator.class.php');
date_default_timezone_set('Asia/Hong_Kong');
//General settings
$title = 'Feed Title';
$link = 'http://www.google.com';
$description = 'Feed Description';
$config = array(
    'channel' => array(
        'title' => $title,
        'link' => $link,
        'description' => $description,
    ),
);
$rss_items = array(
    array(
        'title' => 'Post Title',
        'description' => 'Post Descriptipn',
        'link' => 'http://www.github.com',
        'published' => date("D, d M Y H:i:s O"),
    ),
    array(
        'title' => 'Post Title2',
        'description' => 'Post Descriptipn2',
        'link' => 'http://www.google.com',
        'published' => date("D, d M Y H:i:s O"),
    ),
);
$rss = new RssCreator($config, $rss_items);
$content = $rss->create_feed();
header('Content-Type: text/xml');
$handle = fopen('feeds/example.xml', 'w');
fwrite($handle, $content);
fclose($handle);
?>
