<?php
defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckPHPVersion extends SARule
{
    public $_CONF;
    
    public function __construct($_CONF)
    {
        $this->_CONF = $_CONF;
    }
    
    public function getResult()
    {
        $currentVersion = phpversion();
        if(version_compare($currentVersion, "5.6", '>='))
        {
            return json_encode(array("status" => TRUE, "message" => __("You have the latest PHP Version ","sa-plugin") . "($currentVersion)"));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => sprintf(__("Please install the latest PHP Version (at least %s)","sa-plugin"),"5.6")));
        }
        return "test";
    }
}