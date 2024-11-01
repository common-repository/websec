<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWpTablePrefix extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }

    public function getResult()
    {        
        global $wpdb;
            
        if($wpdb->prefix != "wp_")
        {
            return json_encode(array("status" => TRUE, "message" => __("The table prefix for the database is not equal to its default value : ","sa-plugin"). $wpdb->prefix));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => __("The table prefix for the database is equal to its default value : ","sa-plugin"). $wpdb->prefix));
        }
    }

}