<?php
defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';
require_once SA_PATH . '/core/SAVersionChecker.php';
require_once SA_PATH . '/core/SALogfile.php';

class SACheckPluginVersion extends SARule
{
    public $_CONF;
    
    public function __construct($_CONF)
    {
        $this->_CONF = $_CONF;
    }
    
    public function getResult()
    {
        $s = new SAVersionChecker($this->_CONF['backendServerUrl']);
        
        $plugins = $s->checkIfUpToDate();
        $messageTemplate = __("The following plugins can be updated: ","sa-plugin");
        
        $outdatedPlugins = "";
         
        foreach($plugins as $plugin)
        {
            if(!$plugin['upToDate'])
            {
                $outdatedPlugins .= $plugin['name'] . " (" . $plugin['latestVersion'] . "),";
            }
        }
        
        if($outdatedPlugins == "")
        {
            return json_encode(array("status" => TRUE, "message" => __("All plugins are updated.","sa-plugin")));
        }
        
        // Remove trailing ,
        $message = $messageTemplate . substr($outdatedPlugins,0,-1);
        
        return json_encode(array("status" => FALSE, "message" => $message));
    }
}