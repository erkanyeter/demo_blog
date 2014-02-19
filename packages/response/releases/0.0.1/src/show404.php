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

        echo \Response::getInstance()->showHttpError('404 Page Not Found', $page, '404', 404);
        exit();
    }

}