<?php

/**
* Html Helper
*
* @package       packages
* @subpackage    html
* @category      html
* @link
*/

Class Html {
    
    function __construct()
    {
        if( ! isset(getInstance()->html))
        {
            getInstance()->html = $this; // Make available it in the controller $this->html->method();
        }

        logMe('debug', 'Html Helper Initialized');
    }
    
    // --------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Html\Src\\'.$method))
        {
            require PACKAGES .'html'. DS .'releases'. DS .$packages['dependencies']['html']['version']. DS .'src'. DS .strtolower($method). EXT;
        }

        return call_user_func_array('Html\Src\\'.$method, $arguments);
    }

    // ------------------------------------------------------------------------ 

    /**
    * Get assets directory path
    *
    * @access   private
    * @param    mixed $file_url
    * @param    mixed $extra_path
    * @return   string | false
    */
    public static function _getAssetPath($file, $extra_path = '', $ext = '')
    {                                      
        $paths = array();
        if( strpos($file, '/') !== false)
        {
            $paths = explode('/', $file);
            $file  = array_pop($paths);
        }

        $sub_path   = '';
        if( count($paths) > 0)
        {
            $sub_path = implode('/', $paths) . '/';      // .assets/css/sub/welcome.css  sub dir support
        }
        
        $folder = $ext . '/';
        if($extra_path != '')
        {
            $extra_path = trim($extra_path, '/').'/';
            $folder = '';
        }
        
        $assets_url = str_replace(DS, '/', ASSETS);
        $assets_url = str_replace(ROOT, '', ASSETS);

        return getInstance()->uri->baseUrl('', false) .$assets_url. $extra_path . $folder . $sub_path . $file;
    }
}

/* End of file html.php */
/* Location: ./packages/html/releases/0.0.1/html.php */