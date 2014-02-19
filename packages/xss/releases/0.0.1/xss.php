<?php

/**
 * Xss Helper
 *
 * @package     packages
 * @subpackage  xss
 * @category    security
 * @link
 */

Class Xss {
    
    public function __construct()
    {
        if( ! isset(getInstance()->xss))
        {
            getInstance()->xss = $this; // Make available it in the controller $this->xss->method();
        }

        logMe('debug', 'Xss Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * XSS Filtering
     *
     * @access	public
     * @param	string
     * @param	bool	whether or not the content is an image file
     * @return	string
     */
    public function clean($str, $is_image = false)
    {
        return Security::getInstance()->xssClean($str, $is_image);
    }

    // ------------------------------------------------------------------------

    /**
     * Sanitize Filename
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function sanitizeFilename($filename)
    {
        return Security::getInstance()->sanitizeFilename($filename);
    }

    // ------------------------------------------------------------------------

    /**
     * Strip Image Tags
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function stripImageTags($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }

    // ------------------------------------------------------------------------

    /**
     * Convert PHP tags to entities
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function encodePhpTags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }
}
    
/* End of file xss.php */
/* Location: ./packages/xss/releases/0.0.1/xss.php */