<?php
namespace Response\Src {

    //----------------------------------------------------------------------- 

    /**
    * 404 Page Not Found Handler
    *
    * @access   private
    * @param    string
    * @return   string
    */
    function show404($page = '')
    {
        logMe('error', '404 Page Not Found --> '.$page, false);

        $response = new \Response;
        echo $response->showHttpError('404 Page Not Found', $page, '404', 404);
        exit();
    }

}