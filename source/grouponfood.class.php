<?php

class grouponfood extends generalHandler {

    public $config = array(
        'url' => 'http://alldeals.groupon.hk/zh/_food-and-beverage?view=listing',
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
        'output' => 'grouponfood',
    );

    protected function init() {
        $this->name = 'grouponfood';
    }

    protected function processWebContent($content) {
        return $content;
    }

    protected function processJsonContent($jsons) {
        $outputjsons = array();
        foreach ($jsons as $json) {
            $jsonarray = (array) $json;
            $outputjson = array();
            $outputjson['title'] = $jsonarray['title'];
            $outputjson['url'] = $jsonarray['url'];
            $subcategory = array();
            $scategory = $jsonarray['categories'];
            foreach ($scategory[0] as $sckey => $sc){
                if ($sckey > 0){
                    $subcategory[] = $sc;
                } 
            } 
            $hash = array(
                'source' => 'groupon',
                'category' => 'food',
                //'subcategory' => $subcategory,
                'oprice' => $jsonarray['original_price'],
                'dprice' => $jsonarray['price'],
                'boughtnum' => $jsonarray['sold_count'],
                'url' => $jsonarray['url'],
            );
            $outputjson['description'] = '<img src="' . $jsonarray['image_large_url'] . '"><br>--goodbuya--' . base64_encode(serialize($hash)) . '--goodbuya--';
            $outputjsons[] = json_decode(json_encode($outputjson));
        }
        return $outputjsons;
    }

}

?>
