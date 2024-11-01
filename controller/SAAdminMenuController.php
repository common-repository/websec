<?php

/*
 * Controller to add the our view(s) to the wordpress admin panel
 * 
 */

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/view/SAView.php';
require_once SA_PATH . '/view/SAOverview.php';
require_once SA_PATH . '/view/SAEulaView.php';
require_once SA_PATH . '/model/SAEulaModel.php';

class SAAdminMenuController
    {
        public static $_CONF;
        public function __construct($_CONF) {
            SAAdminMenuController::$_CONF =$_CONF;
        }

        public function add()
        {            
            
            add_action( 'admin_enqueue_scripts', array( $this, 'securityAdvisorLoadCss' ));
            add_action( 'admin_enqueue_scripts', array( $this, 'securityAdvisorLoadScripts' ));
            add_action(
                           'admin_menu', 
                           array(
                               'SAAdminMenuController',
                               'securityAdvisorAdminMenuAction'
                               )
                           );
        }
            
        public function securityAdvisorLoadCss()
        {
            wp_register_style( 'securityAdvisor_css', plugin_dir_url( __FILE__ ) . '../view/css/style.css');   
            wp_enqueue_style( 'securityAdvisor_css' );           
        }
        public function securityAdvisorLoadScripts()
        {
        wp_register_script( 'jquery_particleground_js', plugin_dir_url( __FILE__ ) . '../view/js/jquery.particleground.min.js', array( 'jquery')); 
        wp_enqueue_script( 'jquery_particleground_js' );    
        }
		
        public function securityAdvisorAdminMenuAction()
        {
            // Initiate EulaModel and check if user agreed the EULA (via GET)
            $eulaModel = new SAEulaModel();
            if(!$eulaModel->getState())
            {
                $eulaModel->checkIfAccepted();
            }

            // Check again if it is agreed now.
            // If not, print the eula again.
            if(!$eulaModel->getState())
            {
                    $entry1 = new SAEulaView(SAAdminMenuController::$_CONF);
                    $entry1->add();                        
                    return;              
            }
            // USER HAS TO ACCEPT THE EULA TO GO BEHIND THIS POINT
            $overview = new SAOverview(SAAdminMenuController::$_CONF);
            $overview->add(); 
        }
    }
