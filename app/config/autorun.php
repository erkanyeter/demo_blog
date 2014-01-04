<?php

/*
| -------------------------------------------------------------------
| Auto-Run File ( Run in Controller )
| -------------------------------------------------------------------
| This file specifies which functions should be run by default 
| in __construct() level of the controller.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are run by default.This file lets
| you globally define which systems you would like run with every
| request.
|
| Note: If you need a bootstrap level autorun functionality look 
| for the Hooks package.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
|$autorun['controller'] = array('init');
|
*/

$autorun['controller'] = array('init','menu');

/*
|--------------------------------------------------------------------------
| Run for defined routes
|--------------------------------------------------------------------------
| 
| Prototype
| 
| $autorun['routes'] = array(
|	'directory/class/method' => array('function_name')
| )
|
*/

$autorun['routes'] = array(
	'post/manage/index' => array('auth'),
	'post/create/index' => array('auth'),
	'post/update/index' => array('auth'),
	'post/delete/index' => array('auth'),
	'post/preview/index' => array('auth'),
	'post/approve/index' => array('auth'),
	'post/approve/update' => array('auth'),
	'post/approve/delete' => array('auth'),
);

/*
|--------------------------------------------------------------------------
| Defined functions for autorun
|--------------------------------------------------------------------------
| 
| Prototype
| 
| $autorun['func'] = array(
|    'function_name' => function(){},
| );
|
*/

$autorun['func'] = array(
	'init' => function(){
		new Sess;
		new Auth;
	},
	'auth' => function(){

	    if( ! $this->auth->hasIdentity())
	    {
	    	new Url;
	    	
	        $this->url->redirect('/login');
	    }
	},
    'menu' => function(){
		$this->config->load('menu');  // load menu config
	}
);

/* End of file autorun.php */
/* Location: .app/config/autorun.php */