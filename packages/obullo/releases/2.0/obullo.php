<?php
 
 /**
 * Obullo Core Component
 * 
 * ( All Components are Replaceable )
 * 
 * @package       packages 
 * @subpackage    obullo
 * @category      core
 * @version       2.0
 * 
 */
   function runFramework()
   {
        global $packages;

        $router = getComponentInstance('router');

        /*
         * ------------------------------------------------------
         *  Instantiate the hooks class
         * ------------------------------------------------------
         */
        if(config('enable_hooks'))
        {
            $hooks = getComponentInstance('hooks');

            /*
             * ------------------------------------------------------
             *  Is there a "pre_system" hook?
             * ------------------------------------------------------
             */
            $hooks->_callHook('pre_system');
        }

        /*
         * ------------------------------------------------------
         *  Sanitize Inputs
         * ------------------------------------------------------
         */
        if (config('enable_query_strings') == false) // Is $_GET data allowed ? If not we'll set the $_GET to an empty array
        {
            $_GET = array();
        }
        else
        {
            $_GET = cleanInputData($_GET);
        }

        $_POST = cleanInputData($_POST);  // Clean $_POST Data
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); // Sanitize PHP_SELF

        if (config('csrf_protection'))  // CSRF Protection check
        {
            getComponentInstance('security')->csrfVerify();
        }

        // Clean $_COOKIE Data
        // Also get rid of specially treated cookies that might be set by a server
        // or silly application, that are of no use to a OB application anyway
        // but that when present will trip our 'Disallowed Key Characters' alarm
        // http://www.ietf.org/rfc/rfc2109.txt
        // note that the key names below are single quoted strings, and are not PHP variables
        unset($_COOKIE['$Version']);
        unset($_COOKIE['$Path']);
        unset($_COOKIE['$Domain']);
        
        $_COOKIE = cleanInputData($_COOKIE);

        logMe('debug', "Global POST and COOKIE data sanitized");

        /*
         * ------------------------------------------------------
         *  Load core components
         * ------------------------------------------------------
         */
        $output = getComponentInstance('output');

        $pageUri    = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
        $controller = PUBLIC_DIR .$router->fetchDirectory(). DS .'controller'. DS .$router->fetchClass(). EXT;

        if( ! file_exists($controller))
        {
            $response = new Response;
            $response->show404($pageUri);
        }

        /*
         * ------------------------------------------------------
         *  Is there a "pre_controller" hook?
         * ------------------------------------------------------
         */
        if (config('enable_hooks'))
        {
            $hooks->_callHook('pre_controller');
        }

        require($controller);  // call the controller.

        // $c variable Available in HERE !!!

        if ( strncmp($router->fetchMethod(), '_', 1) == 0 // Do not run private methods. ( _output, _remap, _getInstance .. )
                OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
            )
        {
            $response = new Response;
            $response->show404($pageUri);
        }

        /*
         * ------------------------------------------------------
         *  Is there a "post_controller_constructor" hook?
         * ------------------------------------------------------
         */
        if (config('enable_hooks'))
        {
            $hooks->_callHook('post_controller_constructor');
        }

        // Check method exist or not 
        if ( ! in_array(strtolower($router->fetchMethod()), array_keys($c->_controllerAppMethods)))
        {
            $response = new Response;
            $response->show404($pageUri);
        }

        $arguments = array_slice($c->uri->rsegments, 3);

        if (method_exists($c, '_remap'))  // Is there a "remap" function? If so, we call it instead
        {
            $c->_remap($router->fetchMethod(), $arguments);
        }
        else
        {
            // Call the requested method. Any URI segments present (besides the directory / class / method) 
            // will be passed to the method for convenience
            // directory = 0, class = 1, method = 2
            call_user_func_array(array($c, $router->fetchMethod()), $arguments);
        }

        /*
         * ------------------------------------------------------
         *  Is there a "post_controller" hook?
         * ------------------------------------------------------
         */
        if (config('enable_hooks'))
        {
            $hooks->_callHook('post_controller');
        }

        /*
         * ------------------------------------------------------
         *  Send the final rendered output to the browser
         * ------------------------------------------------------
         */
        if (config('enable_hooks'))
        {
            if($hooks->_callHook('display_override') === FALSE)
            {
                $output->_display();  // Send the final rendered output to the browser
            }
        } 
        else 
        {
            $output->_display();    // Send the final rendered output to the browser
        }

        /*
         * ------------------------------------------------------
         *  Is there a "post_system" hook?
         * ------------------------------------------------------
         */
        if (config('enable_hooks'))
        {
            $hooks->_callHook('post_system');
        }

    }  // end Run.

    // Common Functions
    // ------------------------------------------------------------------------

    /**
    * Clean Input Data
    *
    * This is a helper function. It escapes data and
    * standardizes newline characters to \n
    *
    * @access   private
    * @param    string
    * @return   string
    */
    function cleanInputData($str)
    {
        if (is_array($str))
        {
            $new_array = array();
            foreach ($str as $key => $val)
            {
                $new_array[cleanInputKeys($key)] = cleanInputData($val);
            }

            return $new_array;
        }

        if (function_exists('get_magic_quotes_gpc') AND get_magic_quotes_gpc()) // We strip slashes if magic quotes is on to keep things consistent
        {
            $str = stripslashes($str);
        }

        $str = removeInvisibleCharacters($str); // Remove control characters

        if (config('global_xss_filtering')) // Should we filter the input data?
        {
            $str = getComponentInstance('security')->xssClean($str);
        }
        
        return $str;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Clean Keys
    *
    * This is a helper function. To prevent malicious users
    * from trying to exploit keys we make sure that keys are
    * only named with alpha-numeric text and a few other items.
    *
    * @access   private
    * @param    string
    * @return   string
    */
    function cleanInputKeys($str)
    {
        if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str))
        {
            die('Disallowed Key Characters.');
        }

        return $str;
    }

    // ------------------------------------------------------------------------

    /**
    * Logging
    *
    * We use this as a simple mechanism to access the logging
    * functions and send messages to be logged.
    *
    * @access    public
    * @return    void
    */
    function logMe($level = 'error', $message = '', $php_errors = true)
    {    
        if (config('log_threshold') == 0)
        {
            return;
        }
        
        $class = getComponent('log');
        $logComponent = new $class();

        return $logComponent->dump($level, $message);
    }

    // --------------------------------------------------------------------

    /**
    * Load configuration files.
    * 
    * @access    private
    * @param     string $filename file name
    * @param     string $var variable of the file
    * @param     string $folder folder of the file
    * @param     string $ext extension of the file
    * @return    array
    */
    function getStatic($filename = 'config', $var = '', $folder = '', $ext = '')
    {
        static $loaded    = array();
        static $variables = array();

        $key = trim($folder. DS .$filename. $ext);

        if ( ! isset($loaded[$key]))
        {
            require($folder. DS .$filename. $ext);
        
            if($var == '') { $var = &$filename; }

            if ( ! isset($$var) OR ! is_array($$var))
            {
                die('The static file '. $folder. DS .$filename. $ext.' file does not appear to be formatted as an array.');
            }

            $variables[$key] =& $$var;
            $loaded[$key] = $key;
         }

        return $variables[$key];
    }

    // --------------------------------------------------------------------

    /**
    * Get configuration files from "app/config/$env/" folder.
    * 
    * @access   public
    * @param    string $filename
    * @param    string $var
    * @return   array
    */
    function getConfig($filename = 'config', $var = '', $folder = '', $extension = '')
    {
        $ext    = ($extension == '') ? EXT : $extension;
        $folder = ($folder == '') ? APP .'config' : $folder;

        if(in_array($filename, array('config','routes','sess','database','mongo')))
        {
            $folder = APP .'config'. DS .strtolower(ENV);
        }

        return getStatic($filename, $var, $folder, $ext);
    }

    // --------------------------------------------------------------------

    /**
    * Grab the Controller Instance
    *
    * @access public
    * @param object $new_istance  
    */
    function getInstance($newInstance = '')
    {
        if(is_object($newInstance)) // fixed HMVC object type of integer bug.
        {
            Controller::$instance = $newInstance;
        }

        return Controller::$instance;
    }

    // --------------------------------------------------------------------

    /**
     * If custom component available get it 
     * otherwise return to given value.
     *
     * @access public
     * @param string $component
     * @return string
     */
    function getComponent($component)
    {
        global $packages;

        if(isset($packages['components'][$component]))
        {
            return $packages['components'][$component];
        } 

        die('Component '.$component.' not found please add it to your package.json then run obm update.');
    }

    // --------------------------------------------------------------------

    /**
     * If custom component available get it instance.
     *
     * @access public
     * @param string $component
     * @return string
     */
    function getComponentInstance($component)
    {
        global $packages;

        if(isset($packages['components'][$component]))
        {
            $className = $packages['components'][$component];
            
            return $className::getInstance();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the language item using sprintf().
     *
     * @access public
     * @param string $item
     * @return string
     */
    function lingo()
    {
        $args = func_get_args();
        $item = $args[0];

        $lingo = getComponentInstance('lingo');
        $item  = ($item == '' OR ! isset($lingo->language[$item])) ? false : $lingo->language[$item];

        if(count($args) > 1)
        {   
            $args[0] = $item;
            return call_user_func_array('sprintf', $args);
        }

        return $item;
    }

    // ------------------------------------------------------------------------

    /**
     * Get the schema configuration.
     * 
     * @param  string $filename schema filename
     * @return array  $schema array
     */
    function getSchema($filename)
    {
        return getConfig($filename, '',  APP .'schemas');
    }

    // ------------------------------------------------------------------------

    /**
    * Autoload php files.
    *
    * @access private
    * @param string $packageRealname
    * @return void
    */
    function autoloader($packageRealname)
    {
        if(class_exists($packageRealname, false)) ## https://github.com/facebook/hiphop-php/issues/947
        {
            return;
        }

        global $packages;

        $parts = explode('\\', $packageRealname);
        $packageFilename = mb_strtolower($parts[0], config('charset'));

        //--------------- PACKAGE LOADER ---------------//
        
        $src = '';
        if(isset($parts[1]) AND $parts[1] == 'Src')
        {
            $src = 'src'. DS;
        }

        if(isset($packages['dependencies'][$packageFilename])) // check is it a Package ?
        {
            $version = $packages['dependencies'][$packageFilename]['version'];
            $fileUrl = PACKAGES .$packageFilename. DS .'releases'. DS .$version. DS .$src. mb_strtolower(end($parts), config('charset')). EXT;

            require_once($fileUrl);

            return;
        }
        else 
        {
            if(file_exists(CLASSES .$packageFilename. EXT))   // If its not a package, 
            {                                                 // load User Classes from Classes Directory.
                require_once(CLASSES .$packageFilename. EXT);   
            }
        }
    }

    spl_autoload_register('autoloader', true);

    // --------------------------------------------------------------------

    /**
    * Gets a config item
    *
    * @access    public
    * @param     string $configName file name
    * @return    mixed
    */
    function config($item, $configName = 'config')
    {
        static $configItem = array();

        if ( ! isset($configItem[$item]))
        {
            $configName = getConfig($configName);

            if ( ! isset($configName[$item]))
            {
                return false;
            }

            $configItem[$item] = $configName[$item];
        }

        return $configItem[$item];
    }

    // --------------------------------------------------------------------

    /**
     * Check requested package whether to installed.
     *
     * @access public
     * @param  string $package
     * @return bool
     */
    function packageExists($package)
    {
        global $packages;

        if(isset($packages['dependencies'][$package]))
        {
            return true;
        }

        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Remove Invisible Characters
    *
    * This prevents sandwiching null characters
    * between ascii characters, like Java\0script.
    *
    * @access   public
    * @param    string
    * @return   string
    */
    function removeInvisibleCharacters($str, $url_encoded = true)
    {
        $non_displayables = array();  // every control character except newline (dec 10)
        if ($url_encoded)             // carriage return (dec 13), and horizontal tab (dec 09)
        {
            $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
        }
        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        }
        while ($count);

        return $str;
    }

    // Exception & Errors
    // ------------------------------------------------------------------------

    /**
    * Catch All Exceptions
    *
    * @access private
    * @param  object $e
    * @return void
    */
    function exceptionsHandler($e, $type = '')
    { 
        global $packages;
        
        $core = strtolower($packages['components']['core']);

        $shutdownErrors = array(
        'ERROR'            => 'ERROR',            // E_ERROR 
        'PARSE ERROR'      => 'PARSE ERROR',      // E_PARSE
        'COMPILE ERROR'    => 'COMPILE ERROR',    // E_COMPILE_ERROR   
        'USER FATAL ERROR' => 'USER FATAL ERROR', // E_USER_ERROR
        );
        
        $shutdownError = false;
        if(isset($shutdownErrors[$type]))  // We couldn't use any object for shutdown errors.
        {
            $error = new Error; // Load error package.

            $shutdownError = true;
            $type  = ucwords(strtolower($type));
            $code  = $e->getCode();
            $level = config('error_reporting');

            if(defined('STDIN'))  // If Command Line Request.
            {
                echo $type .': '. $e->getMessage(). ' File: ' .$error->getSecurePath($e->getFile()). ' Line: '. $e->getLine(). "\n";
                $cmdType = (defined('TASK')) ? 'Task' : 'Cmd';

                logMe('error', '('.$cmdType.') '.$type.': '.$e->getMessage(). ' '.$error->getSecurePath($e->getFile()).' '.$e->getLine(), true);

                return;
            }

            if($level > 0 OR is_string($level))  // If user want to display all errors
            {
                if(is_numeric($level)) 
                {
                    switch ($level) 
                    {              
                        case  0: return; break; 
                        case  1: 
                            include (PACKAGES .$core. DS .'releases'. DS .$packages['dependencies'][$core]['version']. DS .'src'. DS .'exception'. EXT);
                            return; 
                        break;
                    }   
                }

                $rules = $error->parseRegex($level); 
                if($rules == false) 
                {
                    return;
                }

                $allowedErrors = $error->getAllowedErrors($rules);  // Check displaying error enabled for current error.
                if(isset($allowedErrors[$code]))
                {
                    include (PACKAGES .$core. DS .'releases'. DS .$packages['dependencies'][$core]['version']. DS .'src'. DS .'exception'. EXT);
                }
            }
            else  // If error_reporting = 0, we show a blank page template.
            {
                include(APP .'errors'. DS .'disabled_error'. EXT);
            }

            logMe('error', $type.': '.$e->getMessage(). ' '.$error->getSecurePath($e->getFile()).' '.$e->getLine(), true); 
        } 
        else  // Is It Exception ? Initialize to Exceptions Component.
        {
            $exception = getComponent('exception');
            $exceptionObject = new $exception();     
            $exceptionObject->write($e, $type);
        }

        return;
    }

    // --------------------------------------------------------------------

    /**
    * Main Error Handler
    * Predefined error constants
    * http://usphp.com/manual/en/errorfunc.constants.php
    * 
    * @access private
    * @param int $errno
    * @param string $errstr
    * @param string $errfile
    * @param int $errline
    */
    function errorHandler($errno, $errstr, $errfile, $errline)
    {                           
        if ($errno == 0){ return; }

        switch ($errno)
        {
            case '1':       $type = 'ERROR'; break;             // E_ERROR
            case '2':       $type = 'WARNING'; break;           // E_WARNING
            case '4':       $type = 'PARSE ERROR'; break;       // E_PARSE
            case '8':       $type = 'NOTICE'; break;            // E_NOTICE
            case '16':      $type = 'CORE ERROR'; break;        // E_CORE_ERROR
            case '32':      $type = "CORE WARNING"; break;      // E_CORE_WARNING
            case '64':      $type = 'COMPILE ERROR'; break;     // E_COMPILE_ERROR
            case '128':     $type = 'COMPILE WARNING'; break;   // E_COMPILE_WARNING
            case '256':     $type = 'USER FATAL ERROR'; break;  // E_USER_ERROR
            case '512':     $type = 'USER WARNING'; break;      // E_USER_WARNING
            case '1024':    $type = 'USER NOTICE'; break;       // E_USER_NOTICE
            case '2048':    $type = 'STRICT ERROR'; break;      // E_STRICT
            case '4096':    $type = 'RECOVERABLE ERROR'; break; // E_RECOVERABLE_ERROR
            case '8192':    $type = 'DEPRECATED ERROR'; break;  // E_DEPRECATED
            case '16384':   $type = 'USER DEPRECATED ERROR'; break; // E_USER_DEPRECATED
            case '30719':   $type = 'ERROR'; break;             // E_ALL
        }
        exceptionsHandler(new ErrorException($errstr, $errno, 0, $errfile, $errline), $type);   
        return;
    }

    // -------------------------------------------------------------------- 

    /**
    * Catch last occured errors.
    *
    * @access private
    * @return void
    */
    function shutdownHandler()
    {                      
        $error = error_get_last();
        if( ! $error) { return; }

        ob_get_level() AND ob_clean(); // Clean the output buffer

        $shutdownErrors = array(
        '1'   => 'ERROR',            // E_ERROR 
        '4'   => 'PARSE ERROR',      // E_PARSE
        '64'  => 'COMPILE ERROR',    // E_COMPILE_ERROR
        '256' => 'USER FATAL ERROR', // E_USER_ERROR
        );

        $type = (isset($shutdownErrors[$error['type']])) ? $shutdownErrors[$error['type']] : '';
        exceptionsHandler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']), $type);
    }
               
    // --------------------------------------------------------------------

    set_error_handler('errorHandler');   
    set_exception_handler('exceptionsHandler');
    register_shutdown_function('shutdownHandler');

// END obullo.php File

/* End of file obullo.php
/* Location: ./packages/obullo/releases/2.0/obullo.php */