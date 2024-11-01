<?php

require_once SA_PATH . "/core/SALogfile.php";
require_once SA_PATH . "/rule/SARule.php";
require_once SA_PATH . "/core/SAPluginConf.php";

function SAAjaxCheckRule()
{
    if($_POST['ruleName'])
    {
        
        header( "Content-Type: application/json" );
        
        // We sanitize it in order to have only A-Za-z0-9_ 
        $ruleName = sanitize_html_class($_POST['ruleName']);
        
        // This makes sure that the path we build up doesn't make any voodoo
        // when included
        $ruleFilePath = SA_PATH . "/rule/" . sanitize_file_name($ruleName . ".php");

        if(!file_exists($ruleFilePath))
        {
            echo json_encode(array("status" => FALSE,"message"=>"Unknown Rule"));
        }
        else
        {
            require_once $ruleFilePath;
            $rule = new $ruleName($_CONF);
            echo $rule->getResult();
        }
    }
    else
    {
        echo json_encode(array("status"=>FALSE,"message"=>"Internal Issue, please wait."));
    }
    exit;
}