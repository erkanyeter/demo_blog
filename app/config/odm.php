<?php

/*
| -------------------------------------------------------------------
| Odm Package Configuration
| -------------------------------------------------------------------
| This file specifies the Odm package settings.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
| $odm = array();
|
*/

$odm = array(

	// Objects
	'post' => new Post,   // Get object.
	'form' => new Form,	  // Form object.

	// Form Notifications
	'notifications' => array(
		'errorMessage' 	 => '<div class="notification error">%s</div>',
		'failureMessage' => '<div class="notification error">%s</div>',
		'successMessage' => '<div class="notification success">%s</div>',
	),

	// Experiential Settings ( Response Data )
	'response' => array(
		'odm_error_message' 		=> 'There are some errors in the form fields.',
		'validation_error_key'      => 'validation_error',
		'validation_error_code'     => '10',
		'operation_failure_key' 	=> 'failure',
		'operation_failure_code' 	=> '12',
		'operation_success_key'     => 'success',
		'operation_success_code'    => '11',
		'operation_success_message' => 'Operation succesfull.',  									// Odm object use this message for general success messages.
		'operation_failure_message' => 'We couldn\'t do operation at this time please try again.',  // Odm object use this message for general failure messages.
	),

);


/* End of file odm.php */
/* Location: .app/config/odm.php */