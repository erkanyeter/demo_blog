<?php

/**
 * $c Header Functions
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

    new Sess;
    new Auth;
    
	new Trigger('private', 'header');
});

$c->func('index', function(){

	$menuConfig      = $this->config->getItem('menu');  // Get menu array
	$firstSegment    = $this->uri->getSegment(0);	    // Get first segnment
	$currentSegment  = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty
	$userHasIdentity = $this->auth->hasIdentity(); 		// get auth Identity of user

	echo $this->uri->getSegment(0);

});