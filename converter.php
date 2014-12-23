<?php
require_once('JsonRss.class.php');
date_default_timezone_set('Asia/Hong_Kong');

//init
$url = '';
$isJson = false;
$configs = array();
$source_path = 'source';

//load sources
$files = scandir($source_path);
foreach ($files as $file) {
    if (strpos(substr($file, -7, 7), "src.php") !== FALSE) {
        $config = array();
        require_once($source_path . '/' . $file);

//url
$url = $config['url'];
if (strlen($url) < 10){
    echo 'URL error';
    return;
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);

if (isset($config['isjson']) && $config['isjson']){
    $isJson = true;
}

//if not json, 1 more step
if (!$isJson){
    if (!isset($config['rexpattern'])){
        echo 'no regular expression pattern';
        return;
    }
    if (!isset($config['rexmap'])){
        echo 'no regular expression to json mapping';
        return;
    }
    $pattern = $config['rexpattern']; //pattern
    $wjmap = $config['rexmap'];
    preg_match_all($pattern, $result, $matches);
    $wcontents = array();
    for ($i = 0; $i < count($matches[1]); $i++){
        $wcontent = array();
        foreach ($wjmap as $wjkey => $wjval){
            $wcontent[$wjval] = $matches[$wjkey+1][$i]; //mapping according to regex
        }
        $wcontents[] = $wcontent;
    }
    $result = json_encode($wcontents);
}

//json2rss: step 1
if (empty($config['jrmap'])){
    echo 'No json to rss mapping';
    return;
}
$mapping = $config['jrmap'];
$jsonstrs = json_decode($result);

//custom function
$custfunction = substr($file, 0, strlen($file)-8);
$jsonstrs = $custfunction($jsonstrs);

//rss channel config
if (empty($config['rssconfig'])){
    echo 'No rss channel configuration';
    return;
}
$rssconfig = $config['rssconfig'];

//conversion
$jsonrss = new JsonRss($rssconfig, $jsonstrs, $mapping);
$content = $jsonrss->prepareRss();
header('Content-Type: text/xml');
$handle = fopen('feeds/' . $config['output'] . '.xml', 'w');
fwrite($handle, $content);
fclose($handle);
}
}
?>
