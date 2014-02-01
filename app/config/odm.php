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

	'post' => new Post,   // Get object.
	'form' => new Form,	  // Form object.
	'success_message' => 'Operation succesfull.',  // Odm object use this message for general success messages.
	'failure_message' => 'We couldn\'t do operation at this time please try again.',  // Odm object use this message for general failure messages.
);


/* End of file odm.php */
/* Location: .app/config/odm.php */