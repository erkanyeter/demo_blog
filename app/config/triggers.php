<?php

/*
| -------------------------------------------------------------------
| Trigger File ( Used by Trigger Class )
| -------------------------------------------------------------------
| This file specifies which functions run by default 
| in __construct() level of the controller.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are run by default. This file lets
| you globally define which systems you would like run with every
| request of "Trigger Object".
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
| $tiggers['func'] = array(
|	'trigger_function' => function(){
|						
|	}
| )
|
*/

$triggers['func'] = array(
	
	'private' => function(){ 	// identity level

		if( ! $this->auth->hasIdentity()) {  // if user has not identity ?
 			new Url;
			$this->url->redirect('/login');  // redirect user to login page
		}

	},
	'public' => function(){ 		 

	},
);

/* End of file triggers.php */
/* Location: .app/config/triggers.php */