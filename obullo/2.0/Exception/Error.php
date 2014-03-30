<?php

namespace Obullo\Exception;

/**
 * Exception Error Class
 *
 * @package       packages
 * @subpackage    exceptions
 * @category      exceptions
 * @link
 */
Class Error
{
    public $logger;

    public function __construct()
    {
        global $c;
        $this->logger = $c['logger'];
        $this->logger->debug('Exceptions Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Display all errors
     * 
     * @param object $e    exception $object
     * @param string $type error type
     * 
     * @return string
     */
    public function display($e, $type = '')
    {
        global $version, $c;
        $type = ($type != '') ? ucwords(strtolower($type)) : 'Exception Error';

        // If user want to close error_reporting in some parts of the application.
        //-----------------------------------------------------------------------

        if ($c['config']['error']['reporting'] == '0') {
            $this->logger->info('Error reporting is Off, check the config.php file "error_reporting" item to display errors.');
            return;
        }
        if (strpos($e->getMessage(), 'shmop_open') === 0) { // Ignore Shmop open segment key warnings.
            return;
        }
        // Database Errors
        //-----------------------------------------------------------------------

        $code = $e->getCode();
        $lastQuery = '';
        if (isset($c['app']->db)) {
            $prepare = (isset($c['app']->db)) ? $c['app']->db->prepare : false;
            $lastQuery = '';
            if (method_exists($c['app']->db, 'lastQuery')) {
                $lastQuery = $c['app']->db->lastQuery($prepare);
            }
        }
        if ( ! empty($lastQuery) AND strpos($e->getMessage(), 'SQL') !== false) { // Yes this is a db error.
            $type = 'Database Error';
            $code = 'SQL';
        }

        // Command Line Errors
        //-----------------------------------------------------------------------

        if (defined('STDIN')) {  // If Command Line Request. 
            echo $type . ': ' . $e->getMessage() . ' File: ' . $c['error']->getSecurePath($e->getFile()) . ' Line: ' . $e->getLine() . "\n";
            $request_type = (defined('TASK')) ? 'Task' : 'Cli';
            $this->logger->error('(' . $request_type . ') ' . $type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());
            return;
        }

        // Load Error Template
        //-----------------------------------------------------------------------

        $isAjax = false;
        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $isAjax = true;
        }
        if ($isAjax) {  // Is Ajax ?
            $error_msg =  $e->getMessage() . ' File: ' . $c['error']->getSecurePath($e->getFile()) . ' Line: ' . $e->getLine() . "\n";
            $error_msg = strip_tags($error_msg);
        } else {
            ob_start();
            include OBULLO .'Exception'. DS .'Html'. EXT;
            $error_msg = ob_get_clean();
        }

        // Log Php Errors
        //-----------------------------------------------------------------------

        $this->logger->error($type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());
        $this->logger->__destruct(); // continue log writing

        // Displaying Errors
        //-----------------------------------------------------------------------            

        $level = $c['config']['error']['reporting'];

        if (is_numeric($level)) {
            switch ($level) {
            case 0: 
                return;
                break;
            case 1:
                echo $error_msg;
                return;
                break;
            }
        }
        $rules = $c['error']->parseRegex($level);
        if ($rules == false) {
            return;
        }
        $allowed_errors = $c['error']->getAllowedErrors($rules);  // Check displaying error enabled for current error.
        if (isset($allowed_errors[$code])) {
            echo $error_msg;
        }
    }

}

// END Error class

/* End of file Error.php */
/* Location: .Obullo/Exception/Error.php */