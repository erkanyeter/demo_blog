<?php

/**
* Post Class ( Fetch data from superglobal $_POST variable ).
*
* @package       packages
* @subpackage    get
* @category      request
* @link
* 
*/

Class Post
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if( ! isset(getInstance()->post))
        {
            getInstance()->post = $this; // Make available it in the controller $this->post->method();
        }

        logMe('debug', 'Post Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
    * Fetch an item from the POST array
    *
    * @access   public
    * @param    string
    * @param    bool
    * @param    bool    Use global post values instead of HMVC scope.
    * @return   string
    */
    public function get($index = NULL, $xss_clean = FALSE, $use_global_var = false)
    {
        $VAR = ($use_global_var) ? $GLOBALS['_POST_BACKUP'] : $_POST;  // People may want to use hmvc or app superglobals.

        if ($index === NULL AND ! empty($VAR))  // Check if a field has been provided
        {
            $post = array();
            
            foreach (array_keys($VAR) as $key)  // loop through the full _POST array
            {
                $post[$key] = Get::fetchFromArray($VAR, $key, $xss_clean);
            }

            return $post;
        }

        return Get::fetchFromArray($VAR, $index, $xss_clean);
    }

}

// END post Class

/* End of file post.php */
/* Location: ./packages/post/releases/0.0.1/post.php */