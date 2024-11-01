<?php
/* 
 * 
 * @wordpress-plugin
 * Plugin Name: WebSec
 * Description: WebSec for WordPress will automatically check your website for potential security vulnerabilities. You are able to see instantly where you could improve your security by just adjusting some basic settings.
 * Plugin URI:  https://yourguy.marketing/
 * Version: 1.0
 * Author: YourMarketingGuy
 * Author URI: https://yourguy.marketing/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Domain Path: /language
 * Text-Domain: sa-plugin
 * 
 */
$version = "1.0";

// Script Kiddie protection
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Set up environment
setupSecurityAdvisorEnvironment();

require_once SA_PATH . '/core/SAPluginConf.php';
require_once SA_PATH . '/core/SALogfile.php';
require_once SA_PATH . '/controller/SAAdminMenuController.php';
require_once SA_PATH . '/view/ajax/SAAjaxCheckRule.php';
require_once SA_PATH . '/core/SAVersionChecker.php';

// Set up Logger -> If smth goes wrong
SALogfile::$logfile = $_CONF['logFile'];
SALogfile::$logpath = $_CONF['logPath'];
SALogfile::$logext = $_CONF['logExtension'];

add_action('plugins_loaded', 'securityAdvisor_loadLanguage');

// Set up hooks -> Install/Uninstall
register_activation_hook( __FILE__ , installSecurityAdvisor );
add_action( 'admin_init', securityAdvisorPostInstallRedirect );

register_uninstall_hook( __FILE__ , uninstallSecurityAdvisor );

// Add visual elements
$adminController = new SAAdminMenuController( $_CONF );
$adminController->add();

// Add Ajax calls
add_action( 'wp_ajax_SAAjaxCheckRule', 'SAAjaxCheckRule');

// Add Farmer
//$v = new SAVersionChecker($_CONF['backendServerUrl']);
//SALogfile::logVar(__FILE__,$v->checkIfUpToDate());

// FUTURE SHIT
// Add our Widget to the dashboard
//$dashboardWidget = new DashboardWidget( $_CONF );
//$dashboardWidget->add();


function setupSecurityAdvisorEnvironment() {

    defined( "SA_PATH" ) or define( "SA_PATH", dirname( __FILE__ ), TRUE);    
    defined( "SA_PREFIX" ) or define( "SA_PREFIX", "SA_" );
}

function installSecurityAdvisor() {
    
    add_option( SA_PREFIX . "installed", TRUE);
    add_option( SA_PREFIX . "postInstallRedirect", TRUE );
   if( get_option(SA_PREFIX . "FTUSeen",FALSE) === FALSE)
   {
       add_option(SA_PREFIX . "FTUSeen",0);
   }
}

function securityAdvisorPostInstallRedirect() {
    if( get_option( SA_PREFIX . "postInstallRedirect", FALSE)) {
        delete_option( SA_PREFIX . "postInstallRedirect" );
        
        wp_redirect("admin.php?page=securityAdvisor_overview");
    }
}

function securityAdvisor_loadLanguage() {
 $plugin_dir = basename(dirname(__FILE__)) . "/language/";
 load_plugin_textdomain( 'sa-plugin', false, $plugin_dir );
}

function uninstallSecurityAdvisor()
{
   
    /*
     *  ONLY WP FUNCTIONS BEYOND THIS POINT!
     * 
     *  Because the delete_option destroys the ymg plugin context
     */
    
    delete_option(SA_PREFIX . "installed" );
    delete_option(SA_PREFIX . "customerId" );
    delete_option(SA_PREFIX . "uid" );
    delete_option(SA_PREFIX . "FTUSeen" ); 
    delete_option(SA_PREFIX . "eula" ); 
    delete_option(SA_PREFIX . "transmitions");

}
?>
