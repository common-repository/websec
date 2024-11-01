<?php

require_once SA_PATH . '/core/SABackendConnection.php';
require_once ABSPATH . "/wp-admin/includes/plugin.php";

class SAVersionChecker
{
    private $backend;
    public function __construct($backendUrl) {
        $this->backend = new SABackendConnection($backendUrl);
    }
    
    public function checkIfUpToDate()
    {
        
        $plugins = get_plugins();
        
        $package = array();
        $i = 0;
        foreach($plugins as $plugin)
        {
            // Getting the local plugin information
            $path = array_keys($plugins)[$i++];
            $data = array();
            $data['name'] = $plugin['Name'];
            $data['currentVersion'] = $plugin['Version'];
            $data['path'] = $path;
            $data['active'] = (in_array( $path, (array) get_option( 'active_plugins', array() ) ))?1:0;
            $data['slug'] = sanitize_title($plugin['Name']);
        
            // Request latest Version from WordPress.
            // This can be acomplished by the WordPress API
            $wpPluginInfo = $this->request($data['slug']);
            
            // Getting information requested from wordpress
            $data['latestVersion'] = $wpPluginInfo->version;
            $data['upToDate'] = (version_compare($data['currentVersion'], $data['latestVersion'], '>='));
            
            // Put it all together, so we can return a summary with all plugins and their update status
            // Much summary, so customerfriendly, very informative
            array_push($package, $data);

        }
        
        // To-Do: Send summary to our backend in order to give the customer
        // the right hints and to check if no dangerous version is in the 
        // current wp instance.
        $this->backend->sendData($package,"sendVersions");
        
        return $package;
    }
    
    public function request($plugin_slug)
    {
        // 
        $args = (object) array( 'slug' => $plugin_slug );
        $request = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args) );
        $url = 'http://api.wordpress.org/plugins/info/1.0/';
        $response = wp_remote_post( $url, array( 'body' => $request ) );
        $plugin_info = unserialize( $response['body'] );
        
        return $plugin_info;
    }
    
    public function updatePlugin($plugin)
    {
        // NOT IMPLEMENTED YET
    }
    
    public function deactivatePlugin($plugin)
    {
        // NOT IMPLEMENTED YET
    }
}