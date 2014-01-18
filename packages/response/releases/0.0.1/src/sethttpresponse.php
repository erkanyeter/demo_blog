<?php
namespace Response\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Set HTTP Status Header
    * 
    * @access   public
    * @param    int     the status code
    * @param    string    
    * @return   void
    */    
    function setHttpResponse($code = 200, $text = '')
    {
        http_response_code($code);  // Php >= 5.4.0 
    }

}