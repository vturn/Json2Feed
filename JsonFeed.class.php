<?php

require_once('generalHandler.class.php');

class JsonFeed {

    private $source = '';
    private $tasks = array();
    private $config = array();

    public function __construct($config){
        $this->source = $config['base'] . $config['source'];
        $this->config = $config;
    }

    public function run(){
        $this->load_tasks();
        $this->run_tasks();
    }

    private function load_tasks(){
        $files = scandir($this->source);
        foreach ($files as $classfile) {
            if (strpos(substr($classfile, -9, 9), "class.php") !== FALSE) {
                require_once($this->source . '/' . $classfile);
                $classname = explode('.', $classfile);
                if (class_exists($classname[0])) {
                    try {
                        $newtask = new $classname[0]($this->config);
                        $this->tasks[$newtask->name] = $newtask;
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }
    }

    private function run_tasks(){
        foreach ($this->tasks as $task){
            $task->run();
        }
    }

}


?>
