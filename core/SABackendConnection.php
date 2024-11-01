<?php
/*
 * Call the API of our backend through the {@link BackendServer} Class.
 * 
 * Every API Call has its own action. We post it to our backend and get 
 * plain text back.
 */

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once ABSPATH . "/wp-includes/http.php";
require_once SA_PATH . "/model/SAEulaModel.php";
require_once SA_PATH . '/core/SALogfile.php';
class SABackendConnection{
    
    private $backendUrl;
    
    public function __construct($backendUrl) {
        $this->backendUrl = $backendUrl;
    }
    
    private function request($action, $data = FALSE){
        
        //SALogfile::logDebug(__FILE__, "Request:$action");
        
        // Don't send any data if user hasn't agreed the EULA!
        $eula = new SAEulaModel();
        if(!$eula->getState()) {
            SALogfile::logWarning(__METHOD__, "$action can't be executed because user didn't agree the EULA.");

            return json_encode(array("status" => "ERROR", "response" => "Please agree EULA before requesting tips!"));
        }
        
        $package = "";
        
        $bodyArray = array( 'action' => $action );
        
        $customerId = get_option( SA_PREFIX . "customerId", FALSE);
        $uid = get_option( SA_PREFIX . "uid" , FALSE);
        if($customerId !== FALSE) {
            $bodyArray['customerId'] = $customerId;
            $bodyArray['uid'] = $uid;
        }
        
        if($data !== FALSE)
        {
            $package = json_encode($data);
            $bodyArray['data'] = $package;
        }
        //SALogfile::logDebug(__FILE__, __METHOD__ . "request");
        SALogfile::logVar(__FILE__, $bodyArray);
        $response = wp_remote_post($this->backendUrl, 
                                    array(
                                            'method' => 'POST',
                                            'body' => $bodyArray,
                                            'timeout' => 15
                                    ));
        
        if(is_wp_error($response))
        {
            return json_encode(array("status" => "ERROR", "response" => "Couldn't communicate with backend."));
        }
        
        if(!$this->isJson($response['body']))
        {
            SALogfile::logError(__FILE__, "Json Error: ". json_last_error_msg() . " with '" .$response['body']."'");
            return json_encode(array("status" => "ERROR", "response" => json_last_error_msg()));
        }
        
        return $response['body'];
    }
    
    public function sendData($data, $action = 'sendData'){
        return $this->request( $action, $data);
    }
    
    public function callUrl($action){
        return $this->request($action);
    }
    
    public function isJson($json)
    {
        json_decode($json);
        return json_last_error() == JSON_ERROR_NONE;
    }
}