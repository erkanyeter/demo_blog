<?php

/**
 * $c logout
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Sess;
        new Auth;
    }
);

$c->func(
    'index.private_user',
    function () {
        $this->auth->clearIdentity();  // remove auth data
        $this->url->redirect('membership/login');
    }
);