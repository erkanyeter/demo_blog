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

	$this->auth->clearIdentity();
	$this->url->redirect('/login');

});