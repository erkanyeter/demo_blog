<?php

/**
 * $c Header
 *
 * @var "View Controller"
 */
$c = new Controller(function(){
    // __construct

	new Url;
	new Auth;

	$this->config->load('navbar');  // load navigation bar in header template.
});

// $c->func('a', function(){});

$c->func('navbar', function(){

	$firstSegment    = $this->uri->getSegment(0);	   // Get first segnment
	$currentSegment  = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

	$li = '';

	foreach ($this->config->getItem('navigation') as $key => $value)
	{
		$active = ($currentSegment == $key) ? ' id="active" ' : '';

		if(($key == 'login' OR $key == 'signup') AND $this->auth->hasIdentity() == true)
		{
			// don't show login button
		} 
		else 
		{
			$li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
		}
	}

	return $li;

});