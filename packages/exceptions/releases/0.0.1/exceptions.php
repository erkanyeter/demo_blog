<?php

/**
 * Exceptions Class
 *
 * @package       packages
 * @subpackage    exceptions
 * @category      exceptions
 * @link
 */

Class Exceptions {

    function __construct()
    {
        logMe('debug', "Exceptions Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
    * Display all errors
    * 
    * @param object $e
    * @param string $type
    * 
    * @return string
    */
    public function write($e, $type = '')
    {
        global $packages;

        $error = new Error;
        $core  = strtolower($packages['components']['core']);
        $type  = ($type != '') ? ucwords(strtolower($type)) : 'Exception Error';
        
        // If user want to close error_reporting in some parts of the application.
        //-----------------------------------------------------------------------
        
        if(config('error_reporting') == '0')
        {
            logMe('info', 'Error reporting is Off, check the config.php file "error_reporting" item to display errors.');
            
            return;
        }

        if(strpos($e->getMessage(), 'shmop_open') === 0) // Ignore Shmop open segment key warnings.
        {
            return;
        }

        // Database Errors
        //-----------------------------------------------------------------------
        
        $code = $e->getCode();
        $lastQuery = '';
        if(isset(getInstance()->db))
        {
            $prepare   = (isset(getInstance()->db->prepare)) ? getInstance()->db->prepare : false;
            $lastQuery = getInstance()->db->lastQuery($prepare);
        }
        
        if( ! empty($lastQuery) AND strpos($e->getMessage(), 'SQL') !== false) // Yes this is a db error.
        {
            $type = 'Database Error';
            $code = 'SQL';  
            $sql  = $lastQuery;
        }
        
        // Command Line Errors
        //-----------------------------------------------------------------------
        
        if(defined('STDIN'))  // If Command Line Request. 
        {
            echo $type .': '. $e->getMessage(). ' File: ' .$error->getSecurePath($e->getFile()). ' Line: '. $e->getLine(). "\n";
            
            $cmd_type = (defined('TASK')) ? 'Task' : 'Cmd';
            
            logMe('error', '('.$cmd_type.') '.$type.': '.$e->getMessage(). ' '.$error->getSecurePath($e->getFile()).' '.$e->getLine(), true); 
            
            return;
        }

        // Load Error Template
        //-----------------------------------------------------------------------

        ob_start();
        
        include (PACKAGES .'exceptions'. DS .'releases'. DS .$packages['dependencies']['exceptions']['version']. DS .'src'. DS .'view'. EXT);
        
        $error_msg = ob_get_clean();
        
        // Log Php Errors
        //-----------------------------------------------------------------------
        
        logMe('error', $type.': '.$e->getMessage(). ' '.$error->getSecurePath($e->getFile()).' '.$e->getLine(), true); 
             
        // Displaying Errors
        //-----------------------------------------------------------------------            
        
        $level = config('error_reporting');
        
        if(is_numeric($level)) 
        {
            switch ($level) 
            {               
               case  0: return; break; 
               case  1: 

               echo $error_msg;

               return; break;
            }   
        }       
    
        $rules = $error->parseRegex($level);

        if($rules == false) 
        {
            return;
        }
        
        $allowed_errors = $error->getAllowedErrors($rules);  // Check displaying error enabled for current error.

        if(isset($allowed_errors[$code]))
        {
            echo $error_msg; 
        }
    }
}

/* End of file Exceptions.php */
/* Location: ./packages/releases/0.0.1/exceptions.php */