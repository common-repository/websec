<?php

/*
 * Model to generate client-side user data (such as an uid) and call the createUser API function
 */

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );
require_once SA_PATH.'/core/SABackendConnection.php';
require_once SA_PATH.'/core/SALogfile.php';
class SAFTUModel {
    
    private $_CONF;
    public $uid;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
        $this->generateUid();
    }
    
    /*
    public function initialDataCommit( ) {
        $f = new Farming($this->_CONF['backendServerUrl'], $this->_CONF['wpOptionsWhitelist']);
        $f->farmComments();
        $f->farmPosts();
        $f->farmOptions();
        $f->sendToBackend( );
    }*/
    
    private function generateUid() {
    
       $this->uid = md5( get_option("siteurl") . time() );
    }
    
    public function createNewUser() {
        
        
        $currentUser = wp_get_current_user(); 
        $pluginData = get_plugin_data( SA_PATH . "/index.php" );
        
        $customerData = array(
                                'email' => $currentUser->user_email,
                                'name' => $currentUser->user_firstname . " " . $currentUser->user_lastname,
                                'domain' => get_option( 'siteurl', FALSE),
                                'installedVersion' => $pluginData['Version'],
                                'language' => get_option( 'WPLANG', ""), // An empty language means en_EN and that is our default language
                                'uid' => $this->uid
                             );       

        $b = new SABackendConnection( $this->_CONF['backendServerUrl'] );
        $response = json_decode($b->sendData( $customerData, "addCustomer"), TRUE);
        
        if(!$response ||
           !is_array($response) ||
           $response['status'] == "ERROR")
        {
             SALogfile::logError(__METHOD__, "Couldn't add customer. Backend said: ". $response['response']);
             return FALSE;
        }
        
        //SALogfile::logVar(__METHOD__, $response);
        return $response['response']['id'];
                
        
    }
}