<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

require_once SA_PATH . '/view/SAView.php';
require_once SA_PATH . '/core/SARuleset.php';
require_once SA_PATH . '/model/SAFTUModel.php';


class SAOverview extends SAView{

   public static $_CONF;

   public function __construct($_CONF) {
       
       SAOverview::$_CONF = $_CONF;
   }

   public function add(){

       add_menu_page( 'WebSec', 
                      'WebSec',                                          
                      'administrator', 
                      'securityAdvisor_overview', 
                      array(
                          'SAOverview',
                          'securityAdvisor_display_overview'
                          ),
                      'dashicons-lock',
					  '2.000123451'
                    );     
   }
   
   public function securityAdvisor_display_overview()
   {
       if(get_option(SA_PREFIX . "customerId", FALSE) === FALSE)
       {
          $f = new SAFTUModel( SAOverview::$_CONF);     
     
        $userId = $f->createNewUser();
        if($userId !== FALSE)
        {
            add_option( SA_PREFIX . "customerId", $userId);
            add_option( SA_PREFIX . "uid", $f->uid);
            update_option( SA_PREFIX . "FTUSeen", 1);
        }
       }
    $ruleset = new SARuleset();
    $rules = json_decode($ruleset->getList());

    echo "<div class=\"SAbody\"><div class=\"SAcontent\">";
    echo "<progress id=\"SA_progress\" value=\"0\" max=\"". count($rules) ."\"></progress>";
    echo "<table id=\"SA_results\">";
    echo "</table>";
?>

    <script>
    var rules = jQuery.parseJSON('<?php echo $ruleset->getList(); ?>');
    for(i = 0; i < rules.length; i++)
    {
        jQuery.post(
         // see tip #1 for how we declare global javascript variables
         "<?php echo admin_url( 'admin-ajax.php' )?>",
         {
         // here we declare the parameters to send along with the request
         // this means the following action hooks will be fired:
         // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
         action : 'SAAjaxCheckRule',

         // other parameters can be added along with "action"
         ruleName : rules[i]
         },
         function( response ) {
            jQuery('#SA_remaining').text(jQuery('#SA_remaining').text()-1);
            jQuery('#SA_progress').attr("value", jQuery('#SA_progress').attr("value")+1);
            jQuery('#SA_results').append('<tr><td class="' + response.status +'"></td><td>' + response.message + '</td></tr>');
         }
        );
    } 
        
    jQuery('#wpwrap').particleground({
        dotColor: '#d0d0d0',
        lineColor: '#d0d0d0',
        maxSpeedX:0.3,
        maxSpeedY:0.3,
        density:15000
    });
    </script>
    </div></div>

    <?php
   }
}
