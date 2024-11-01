<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWpDebug extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }

    public function getResult()
    {

        if(WP_DEBUG)
        {
            return json_encode(array("status" => FALSE, "message" => __("The developer modus is activated.","sa-plugin")));
        }
        else
        {
            return json_encode(array("status" => TRUE, "message" => __("The developer modus is deactivated.","sa-plugin")));
        }
    }

}