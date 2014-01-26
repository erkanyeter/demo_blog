<?php

/**
 * $c logout
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Sess;
	new Auth;
});

$c->func('index', function(){

	$this->auth->clearIdentity();  // remove auth data
	$this->url->redirect('/login');

});