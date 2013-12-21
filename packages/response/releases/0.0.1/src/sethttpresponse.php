<?php
namespace Response\Src {

    // ------------------------------------------------------------------------

    /**
     * Set http header
     * 
     * @access public
     * @param integer $code http response code
     * @param string  $text response text
     */
    function setHttpResponse($code = 200, $text = '')
    {
        http_response_code($code);  // Php >= 5.4.0 
    }

}