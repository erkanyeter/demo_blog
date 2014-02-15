<?php
namespace Uri\Src {

    // --------------------------------------------------------------------
    
    /**
    * When we use HMVC we need to Clean
    * all data.
    *
    * @return  void
    */
    function clear()
    {
        $uri = \Uri::getInstance();

        $uri->keyval        = array();
        $uri->uri_string    = '';
        $uri->segments      = array();
        $uri->rsegments     = array();
        $uri->uri_extension = '';
    }

}