<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/rule/SARule.php';

class SACheckWpSecurityKeys extends SARule
{
    private $_CONF;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
    }
    
    public function getResult() {
        $securityKeys = array(AUTH_KEY, SECURE_AUTH_KEY, LOGGED_IN_KEY, NONCE_KEY, AUTH_SALT, SECURE_AUTH_SALT, LOGGED_IN_SALT, NONCE_SALT);
        
        //check if one key is empty
        foreach ($securityKeys as $key => $value) {
            $value = trim($value);
            if (empty($value))
                $securityKeysEmpty = true;
            else
                $securityKeysEmpty = false;
        }
        
        if(!$securityKeysEmpty and !in_array("put your unique phrase here", $securityKeys)) //put your unique phrase here = WP default
        {
            return json_encode(array("status" => TRUE, "message" => __("Your WordPress instance uses secure and unique authentication keys.","sa-plugin")));
        }
        else
        {
            return json_encode(array("status" => FALSE, "message" => __("Your WordPress instance doesn't use secure and unique authentication keys.","sa-plugin")));
        }
    }

}