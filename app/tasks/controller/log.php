<?php

defined('STDIN') or die('Access Denied');

error_reporting(E_ERROR | E_WARNING | E_PARSE); // error reporting

/**
 * Catch errors
 * 
 * @param string $errno   number
 * @param string $errstr  str
 * @param string $errfile file
 * @param string $errline line
 * 
 * @return string
 */
function logErrorHandler($errno, $errstr, $errfile, $errline)
{
    echo("\n\033[1;33mError: $errstr . ' - error code: '.$errno.' errorfile : - '.$errfile.' - errorline: '.$errline \033[0m"); // Do something other than output message.
}
set_error_handler('logErrorHandler');

// ------------------------------------------------------------------------

/**
 * $c log
 * @var Controller
 */
$c = new Controller(
    function () {
    }
);

$c->func(
    'index',
    function ($level = '') {
        if ($level == '') {
            $this->_displayLogo();
            $this->_follow(DATA .'logs'. DS .'app.log'); // Display the debugging.
        } else {
            $this->_follow(DATA .'logs'. DS .'app.log', $level);
        }
    }
);
    
// ------------------------------------------------------------------------

$c->func(
    '_displayLogo',
    function () {
        echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager v2.0 (c) 2014
Display logs [$php task log], to filter logs [$php task log index $level]'."\n\033[0m";
    }
);

// ------------------------------------------------------------------------

/**
 * Console log 
 * Print colorful log messages to your console.
 * @param  $file
 */ 
$c->func(
    '_follow',
    function ($file, $level = '') {

        static $lines = array();

        $size = 0;
        while (true) {

            clearstatcache(); // clear the cache
            if ( ! file_exists($file)) { // start process when file exists.
                continue;
            }
            $currentSize = filesize($file); // continue the process when file size change.
            if ($size == $currentSize) {
                usleep(50);
                continue;
            }
            if ( ! $fh = fopen($file, 'rb')) {
                echo("\n\n\033[1;31mPermission Error: You need to have root access or log folder has not got write permission.\033[0m\n"); exit;
            }
            fseek($fh, $size);

            $i = 0;
            while ($line = fgets($fh)) {
                if ($i == 0) {
                    $line = str_replace("\n", '', $line);
                }
                // remove all newlines
                // $line = trim(preg_replace('/[\r\n]/', ' ', $line), "\n");
                
                $line = trim(preg_replace('/[\r\n]/', "\n", $line), "\n");
                $line = str_replace('[@]', "\n", $line); // new line
                $out  = explode('.', $line);  // echo print_r($out, true)."\n\n";
                
                // print_r($out);

                if ($level == '' OR $level == 'debug') {
                    
                    if (isset($out[1])) {

                        if (strpos($out[1], '[') !== false) {   // colorful logs.
                            $line = "\033[0;33m".$line."\033[0m";
                        }

                        if (strpos($out[1], 'SQL') !== false) {   // remove unnecessary spaces from sql output
                            $line = str_replace('SQL: ', '', "\033[1;32m".preg_replace('/\s+/', ' ', $line)."\033[0m");
                        }

                        if (strpos($out[1], '$_') !== false) {
                            $line = preg_replace('/\s+/', ' ', $line);
                            $line = preg_replace('/\[/', "[", $line);  // do 

                            if (strpos($out[1], '$_REQUEST_URI') !== false) {
                                $break = "\n------------------------------------------------------------------------------------------";
                                $line = "\033[1;36m".$break."\n".$line.$break."\n\033[0m";
                            } else {
                                $line = "\033[1;35m".$line."\033[0m";
                            }

                            if (strpos($out[1], '$_HVC') !== false) {
                                $line = "\033[1;36m".strip_tags($line)."\033[0m";
                            }
                        }

                        if (strpos($out[1], 'Task') !== false) {
                            $line = "\033[1;34m".$line."\033[0m";
                        }
                        $debug_output = explode(' ', $out[1]);
                        
                        if (isset($debug_output[4]) AND strpos($debug_output[4], 'loaded:') !== false) {
                            $line = "\033[0;35m".$line."\033[0m";
                        }
                    }
                }

                if (strpos($out[1], 'debug') !== false) {   // Do not write two times
                    if ($level == '' OR $level == 'debug') {
                        $line = "\033[0;35m".$line."\033[0m";
                        if ( ! isset($lines[$line])) {
                            echo $line."\n";
                        }
                        $debug_output = explode(' ', $out[1]);
                        // print_r($debug_output);
                        if (isset($debug_output[2]) AND trim($debug_output[2]) == 'Final' AND trim($debug_output[3]) == 'output') {
                            $line = "\033[1;36m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                }
                if (strpos($out[1], 'error') !== false) {
                    if ($level == '' OR $level == 'error') {
                        $line = "\033[1;31m".$line."\033[0m";
                        if ( ! isset($lines[$line])) {
                            echo $line."\n";
                        }
                    }
                }
                if (strpos($out[1], 'info') !== false) {
                    if ($level == '' OR $level == 'info') {
                        $line = "\033[1;35m".$line."\033[0m";
                        if ( ! isset($lines[$line])) {
                            echo $line."\n";
                        }
                    }
                }
                $i++;
                $lines[$line] = $line;
            }

            fclose($fh);
            clearstatcache();
            $size = $currentSize;
        }
    
    }
);

// Terminal Colour Codes ( TERMINAL SCREEN BASH CODES )
/*
$BLACK="33[0;30m";
$DARKGRAY="33[1;30m";
$BLUE="33[0;34m";
$LIGHTBLUE="33[1;34m";
$MAGENTA="33[0;35m";
$CYAN="33[0;36m";
$LIGHTCYAN="33[1;36m";
$RED="33[0;31m";
$LIGHTRED="33[1;31m";
$GREEN="33[0;32m";
$LIGHTGREEN="33[1;32m";
$PURPLE="33[0;35m";
$LIGHTPURPLE="33[1;35m";
$BROWN="33[0;33m";
$YELLOW="33[1;33m";
$LIGHTGRAY="33[0;37m";
$WHITE="33[1;37m";
*/

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */