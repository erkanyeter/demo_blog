<?php

/**
 * example class
 */
Class Doly
{
    /**
     * constructor
     */
    public function __construct()
    {
        global $logger;

        if ( ! isset(getInstance()->doly)) {
            getInstance()->doly = $this;
        }

        $logger->debug('My Doly Class Initialized');
    }

    /**
     * Hello world
     * 
     * @return string
     */
    public function hello()
    {
        echo 'Hello World !';
    }

}