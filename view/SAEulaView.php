<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/view/SAView.php';

class SAEulaView extends SAView{

   public static $_CONF;

   public function __construct($_CONF) {
       SAEulaView::$_CONF = $_CONF;
   }

   public function add(){

       add_menu_page( 'WebSec', 
                      'WebSec',                                          
                      'administrator', 
                      'securityAdvisor_overview', 
                      array(
                          'SAEulaView',
                          'securityAdvisor_display_eula'
                          ),
                      'dashicons-businessman',
					  '2.000123451'
                    );
   }

   public function securityAdvisor_display_eula()
   {
       // You can put the eula frontend stuff after this point.
       // If the user agrees just request .?eula=yes.
       // The eula flag will be set to true and we can power on our magic
       ?>
       
       <div class="SAcontent">
           <div class="SAlogo">
           <img src="<?php echo plugins_url( 'img/securityadvisor-logo.png', __FILE__ ); ?>"/>
           </div>
           
           <div class="SAintro">
                   <h1><?php echo __("Welcome","sa-plugin");?></h1>
               <h2><?php echo __("All we need to get started is your permission to access your WordPress data. This will allow us to provide you with an analysis of your security status as well as relevant tips to remove vulnerabilities ","sa-plugin");?></h2><br/>
           
           <a href="?page=securityAdvisor_overview&eula=yes"><div class="SAbtn SAbtn-default SAbtn-hover"><?php echo __("I want tips concerning my security","sa-plugin");?></div></a><br/><br/>
           <small> <?php echo __("and agree to the: ","sa-plugin");?> <a href="http://yourguy.marketing/privacy-policy/" target="_blank"><?php echo __("Privacy Policy","sa-plugin");?></a> & <a href="http://yourguy.marketing/t-c/" target="_blank"><?php echo __("Terms of Service","sa-plugin");?></a></small>
           </div>
        
       </div>          
       
       <?php
   }
}