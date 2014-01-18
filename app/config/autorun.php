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
|$autorun['controller'] = array('load_classes');
|
*/

$autorun['controller'] = array(
	'load_classes',
	'load_menu'
	);

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
	'post/manage/index'   => array('check_auth'),
	'post/create/index'   => array('check_auth'),
	'post/update/index'   => array('check_auth'),
	'post/delete/index'   => array('check_auth'),
	'post/preview/index'  => array('check_auth'),
	'post/approve/index'  => array('check_auth'),
	'post/approve/update' => array('check_auth'),
	'post/approve/delete' => array('check_auth'),
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
	'load_classes' => function(){
		new Sess;
		new Auth;
	},
    'load_menu' => function(){
		$this->config->load('menu');  // load menu config
	},
	'check_auth' => function(){

	    if( ! $this->auth->hasIdentity())
	    {
	    	new Url;
	    	
	        $this->url->redirect('/login');
	    }
	},
);

/* End of file autorun.php */
/* Location: .app/config/autorun.php */