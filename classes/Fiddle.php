<?php

class Fiddle{

    protected $project = "afrid1";
    protected $root    = "";

    public function create($project = ""){
        if(!empty($project)){
            $this->project = $project;
        }
        $this->setproject($this->project);
        $directory = $this->root;
        if(!is_dir($directory)){
            mkdir($directory);
            touch($directory . "/index.php");
        }
    }

    public function setproject($project){
        $this->root = __DIR__ . "/../fiddles/" . $project;
    }

    public function getProject(){
        return $this->project;
    }

    public function isProject($project){
        $directory = __DIR__ . "/../fiddles/" . $project;
        if(is_dir($directory)){
            return true;
        }
        return false;
    }
    public function getFile($project, $path = "/index.php"){
        if(empty($this->root)){
            $this->setproject($project);
        }
        $file = $this->root . str_replace("//", "/", $path);
        return file_get_contents($file);
    }
    public function getContents($project, $path = "/"){
        if(empty($this->root)){
            $this->setproject($project);
        }
        $list  = [
            "files"   => [],
            "folders" => []
        ];
        $files = glob($this->root . str_replace("//", "/", $path . "/*"));
        foreach($files as $file){
            $find = "/../fiddles/$this->project";
            $pos  = strpos($file, $find);
            $incl = substr($file, $pos + strlen($find));
            if(is_dir($file)){
                $list["folders"][basename($incl)] = $incl;
            }else{
                $list["files"][basename($incl)] = $incl;
            }
        }
        return $list;
    }

}

?>
