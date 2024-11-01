<?php

class SARuleset
{
    public function __construct() {
        
    }
    
    public function getList()
    {
       $path = SA_PATH . "/rule/";
       $files = scandir($path);
       
       $rules = array();
       
       foreach($files as $file)
       {
           
           if(strpos($file, "SA") !== 0)continue;
           if($file == "SARule.php")continue;
               
            array_push($rules, explode('.', $file)[0]);
       }
       
       return json_encode($rules);
    }
}

