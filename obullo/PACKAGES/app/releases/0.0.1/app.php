<?php

/**
 * Control the app 
 *
 * @category  Controller
 * @package   App
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/package/app
 */
Class App
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;
        $c['Logger']->debug('App Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Set a object to 
     * controller instance 
     * 
     * @param string $key string
     * @param string $val object
     *
     * @return void
     */
    public function __set($key, $val)
    {
        if (is_object($val)) {  // Just store object type variables to Controller
            Controller::$instance->{$key} = $val;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Get application objects
     * 
     * @param string $key class or object name
     * 
     * @return object
     */
    public function __get($key)
    {
        if ($key == 'instance') {
            return Controller::$instance;
        }
        return $this->{$key};
    }
}

/* End of file App.php */
/* Location: ./packages/app/releases/0.0.1/app.php */