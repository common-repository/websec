<?php

defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

class SAEulaModel{
    
    function __construct() {
        
        if(get_option(SA_PREFIX."eula",FALSE) === FALSE)
        {
            add_option(SA_PREFIX."eula",FALSE,TRUE);
        }
    }
    
    function accept(){
        update_option(SA_PREFIX."eula",TRUE);
    }
    
    function checkIfAccepted() {
        $eula =  $_GET['eula'];
        
        if($eula == "yes") {
            $this->accept();
        }
    }
    
    function getState() {
        return get_option(SA_PREFIX."eula",FALSE);
    }
}