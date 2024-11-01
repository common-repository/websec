<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWPVersion extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }

    public function getResult() {
        $currentVersion = get_bloginfo('version');
        
        if(version_compare($currentVersion, "4.6", '>='))
        {
            return json_encode(array("status" => TRUE, "message" => __("You have the latest WordPress Version","sa-plugin")." (". $currentVersion . ")"));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => sprintf(__("Please install the latest WordPress Version (at least %s).","sa-plugin"),"4.6")));
        }
    }

}