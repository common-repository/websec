<?php
defined( 'SA_PATH' ) or die( 'No script kiddies please!' );

class SALogfile{
    
    public static $logfile = "";
    public static $logpath = "";
    public static $logext = "";
    public static $endl = PHP_EOL;
    public static $MAX_FILE_SIZE = 1048576; // 10Kb 
    
    private static function createSALogfile()
    {
        $logfile = SALogfile::$logpath . SALogfile::$logfile . SALogfile::$logext;
        $date = SALogfile::timestamp();
        
        $str =  "##############################################################" . SALogfile::$endl;
        $str .= "#                                                            #" . SALogfile::$endl;
        $str .= "# Security Advisor                                           #" . SALogfile::$endl;
        $str .= "# Plugin SALogfile                                             #" . SALogfile::$endl;
        $str .= "#                                                            #" . SALogfile::$endl;
        $str .= "# Created: $date                               #" . SALogfile::$endl;
        $str .= "# Customer-ID: " . get_option(SA_PREFIX . "customerId", "Not defined") . "   #". SALogfile::$endl;
        $str .= "##############################################################" . SALogfile::$endl;
        
        // Checks if log dir is existing and creates it if not
        if ( !file_exists( SALogfile::$logpath )) {
            mkdir( SALogfile::$logpath );
        }
        
        file_put_contents( $logfile, $str );
    }
    
    private static function timestamp()
    {
        return date('Y-m-d H:i:s');
    }
    
    private static function log($level, $source,$msg)
    {
        $logfile = SALogfile::$logpath . SALogfile::$logfile . SALogfile::$logext;
        
        if(!file_exists($logfile) ||
           filesize($logfile) > SALogfile::$MAX_FILE_SIZE)
        {
            SALogfile::createSALogfile();
        }
        
        file_put_contents($logfile, SALogfile::timestamp() . " [$level] $source: $msg" . SALogfile::$endl, FILE_APPEND);
        flush();
    }
    
    public static function logError($source,$msg)
    {
        SALogfile::log("Error",$source,$msg);
    }
    public static function logDebug($source,$msg)
    {
        // ToDO: Introduce debug variable in plugin conf
        //       which includes all classes which should be debugged
        //       This should be checked in this method.
        SALogfile::log("Debug",$source,$msg);
    }
    public static function logInfo($source,$msg)
    {
        SALogfile::log("Info",$source,$msg);
    }    
    public static function logWarning($source,$msg)
    {
        SALogfile::log("Warning",$source,$msg);
    }
    
    public static function logVar($source, $var)
    {
        SALogfile::logDebug($source, print_r($var,TRUE));
    }
    
}