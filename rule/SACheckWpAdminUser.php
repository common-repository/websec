<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWpAdminUser extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }
    
    public function getResult() {
        
        if(!username_exists("admin")) 
        {
            return json_encode(array("status" => TRUE, "message" => __("You don't use 'admin' as a username for your administrator account.","sa-plugin")));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => __("Don't use 'admin' as a username for your administrator account.","sa-plugin")));
        }
    }

}