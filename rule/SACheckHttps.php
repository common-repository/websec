<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckHttps extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }

    public function getResult()
    {
        $url = parse_url($_SERVER['PHP_SELF']);
        
        if($url['scheme'] == 'https')
        {
            return json_encode(array("status" => TRUE, "message" => __("You have an encrypted Connection (https).","sa-plugin")));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => __("You don’t use a secure connection. No SSL-certificate (https) is in use","sa-plugin")));
        }
    }

}