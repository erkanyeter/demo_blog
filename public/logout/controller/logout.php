<?php

/**
 * $c logout
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
});

$c->func('index', function() use($c){

	$this->auth->clearIdentity();  // remove auth data
	$this->url->redirect('/login');

});