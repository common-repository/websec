<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/core/SABackendConnection.php';
require_once SA_PATH . '/core/SALogfile.php';
require_once ABSPATH . "/wp-admin/includes/plugin.php";

class SAFarming
{
    private $backend;
    
    function __construct($backendUrl) {
        $this->backend = new SABackendConnection($backendUrl);
    }
    
    function farmVersions()
    {
        $plugins = get_plugins();
        
        $hash = md5(json_encode($plugins). json_encode(get_option("active_plugins", array())));
        if(!$this->checkIfUpToDate("Versions", $hash))
        {
            $package = array();
            $i = 0;
            foreach($plugins as $plugin)
            {
                $path = array_keys($plugins)[$i++];
                $data = array();
                $data['name'] = $plugin['Name'];
                $data['version'] = $plugin['Version'];
                $data['path'] = $path;
                $data['pluginURI'] = $plugin['PluginURI'];
                $data['title'] = $plugin['title'];
                $data['authorURI'] = $plugin['AuthorURI'];
                $data['textDomain'] = $plugin['TextDomain'];
                $data['domainPath'] = $plugin['DomainPath'];
                $data['network'] = $plugin['Network'];
                $data['author'] = $plugin['author'];
                $data['active'] = (in_array( $path, (array) get_option( 'active_plugins', array() ) ))?1:0;
                array_push($package, $data);
                
            }
            
            array_push($package, array("name" => "Wordpress", 
                                       "version" => get_bloginfo('version'),
                                       "path" => "/",
                                       "active" => 1));
            
            SALogfile::logDebug(__METHOD__, "Versions to send" . print_r($package,TRUE));
          
            $this->backend->sendData($package,"sendVersions");
        }
    }
    
    function checkIfUpToDate($transmitionName, $hash)
    { 
        $transmitions = json_decode(get_option(SA_PREFIX . "transmitions", json_encode(FALSE)), TRUE);
       

        if(!$transmitions ||                                    // WP_Option doesn't exist
           !is_array($transmitions) ||                          // json_decode failed
           !array_key_exists($transmitionName, $transmitions))  // Transmition was never made before
        {
            $t = array();
            $t[$transmitionName] = $hash;
            SALogfile::logDebug(__METHOD__, "Not Up to date! $hash");
            update_option(SA_PREFIX . "transmitions", json_encode($t));
            return FALSE;
        }
        $lastTransmitionHash = $transmitions[$transmitionName];
        
        if($lastTransmitionHash === $hash)
        {
            SALogfile::logDebug(__METHOD__, "Up to date!");
            return TRUE;
        }
        else
        {
            $transmitions[$transmitionName] = $hash;
            SALogfile::logDebug(__METHOD__, "Not Up to date! Current hash: $hash, Stored Hash: $lastTransmitionHash");
            update_option(SA_PREFIX . "transmitions", json_encode($transmitions));
            return FALSE;
        }       
    }
}