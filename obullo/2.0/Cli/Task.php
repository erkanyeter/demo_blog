<?php

/**
 * Task Class
 * 
 * @category  Cli
 * @package   Task
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/task
 */
Class Task
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        $this->logger = $c['logger'];
        $this->logger->debug('Task Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Run cli task
     * 
     * @param string  $uri   task uri
     * @param boolean $debug On / Off print debugger
     * 
     * @return void
     */
    public function run($uri, $debug = false)
    {
        $uri    = explode('/', trim($uri));
        $module = array_shift($uri);

        foreach ($uri as $i => $section) {
            if ( ! $section) {
                $uri[$i] = 'false';
            }
        }

        $shell = PHP_PATH . ' ' . FPATH . '/' . TASK_FILE . ' ' . $module . ' ' . implode('/', $uri) . ' OB_TASK_REQUEST';

        if ($debug) { // Enable debug output to log folder.

            // @todo escapeshellcmd();
            // clear console colors
            // $output = trim(preg_replace('/\n/', '#', $output), "\n");
            
            // clean cli color codes
            $output = preg_replace(array('/\033\[36m/', '/\033\[31m/', '/\033\[0m/'), array('', '', ''), shell_exec($shell));
            $this->logger->debug('$_TASK request: ' . $shell, array('output' => $output));

            return $output;

        } else {   // continious task

            shell_exec($shell . ' > /dev/null &');
        }

        $this->logger->debug('$_TASK command: ' . $shell);
    }

}

// END Task.php File
/* End of file Task.php

/* Location: .Obullo/Cli/Task.php */
