<?php

require_once('JsonRss.class.php');

//date_default_timezone_set('Asia/Hong_Kong');

class generalHandler {

    public $sourcePath;
    public $outputPath;
    public $name;
    public $config = array();

    public function __construct($config) {
        $this->sourcePath = $config['base'] . $config['source'];
        $this->outputPath = $config['base'] . $config['output'];
        $this->init();
        $this->load_config();
    }

    protected function init() {  //define name
        return true;
    }

    protected function load_config() {
        return true;
    }

    public function run() {
        $content = $this->load_source();
        $this->process($content);
    }

    protected function load_source() {
        //url
        $url = $this->config['url'];
        if (strlen($url) < 10) {
            echo 'URL error';
            return;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function process($content) {
        if (!isset($this->config['isjson'])) {
            echo 'parameter isJson missing.';
            return false;
        }
        if (!$this->config['isjson']) {
            //if not json, 1 more step
            $contentArray = $this->processWebContent($content);
            $content = json_encode($contentArray);
        }

        $jsons = $this->jsonRssCovert($content);
        $this->generateRss($jsons);

    }

    protected function processWebContent($content) {
        //custom function list
        return true;
    }

    protected function rexArray($content, $rexPattern = '', $rexmap = array()) {
        if ($rexPattern == '') {
            echo 'no regular expression pattern';
            return;
        }
        if (empty($rexmap)) {
            echo 'no regular expression to json mapping';
            return;
        }
        preg_match_all($rexPattern, $content, $matches);
        $wcontents = array();
        for ($i = 0; $i < count($matches[1]); $i++) {
            $wcontent = array();
            foreach ($rexmap as $wjkey => $wjval) {
                $wcontent[$wjval] = $matches[$wjkey + 1][$i]; //mapping according to regex
            }
            $wcontents[] = $wcontent;
        }
        return $wcontents;
    }

    protected function jsonRssCovert($json) {
        //json2rss: step 1
        if (empty($this->config['jrmap'])) {
            echo 'No json to rss mapping';
            return;
        }
        $mapping = $this->config['jrmap'];
        $jsonstrs = json_decode($json);
        //custom function
        //$custfunction = substr($file, 0, strlen($file) - 8);
        //$jsonstrs = $custfunction($jsonstrs);
        $jsonstrs = $this->processJsonContent($jsonstrs);
        return $jsonstrs;
    }

    protected function processJsonContent($jsonstrs) {
        //custom function list
        return true;
    }

    protected function generateRss($json) {
        //rss channel config
        if (empty($this->config['rssconfig'])) {
            echo 'No rss channel configuration';
            return;
        }
        $rssconfig = $this->config['rssconfig'];
        $mapping = $this->config['jrmap'];

        //conversion
        $jsonrss = new JsonRss($rssconfig, $json, $mapping);
        $content = $jsonrss->prepareRss();
        header('Content-Type: text/xml');
        $handle = fopen($this->outputPath . '/' . $this->config['output'] . '.xml', 'w');
        fwrite($handle, $content);
        fclose($handle);
    }

}

?>
