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
        logMe('error', 'HTTP Error --> '.$message, false);

        header('Content-type: text/html; charset='.config('charset')); // Some times we use utf8 chars in errors.
        
        $response = new \Response;
        echo $response->showHttpError($heading, $message, 'general', $statusCode);
        exit();
    }

}