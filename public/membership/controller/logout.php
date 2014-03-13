<?php

/**
 * $c logout
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
    }
);

$c->func(
    'index.Private_User',
    function () {
        $this->auth->clearIdentity();  // remove auth data
        $this->url->redirect('membership/login');  // redirect user to logout screen
    }
);