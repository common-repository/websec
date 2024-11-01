<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWpUpdates extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }

    public function getResult()
    {

        if(WP_AUTO_UPDATE_CORE)
        {
            return json_encode(array("status" => TRUE, "message" =>  __("WordPress auto updater is activated.","sa-plugin")));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => __("Automatic WordPress updates are deactivated.","sa-plugin")));
        }
    }

}