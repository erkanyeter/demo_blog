<?php
namespace Response\Src {

    // -------------------------------------------------------------------- 

    /**
    * Manually Set General Http Errors
    *
    * @access public
    * @param string $message
    * @param int    $statusCode
    * @param int    $heading
    */
    function showError($message, $statusCode = 500, $heading = 'An Error Was Encountered')
    {
        global $config;

        header('Content-type: text/html; charset='.$config['charset']); // Some times we use utf8 chars in errors.
        
        logMe('error', 'HTTP Error --> '.$message, false);

        echo getComponentInstance('response')->showHttpError($heading, $message, 'general', $statusCode);
        exit();
    }

}