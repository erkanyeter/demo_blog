<?php

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend framework without hacking
| the core files. Please see the docs for info:
|
|	@see docs/advanced/hooks
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
|	$hooks['pre_controller'] = function() {
|		...
| 	};
|
*/

$hooks = array(
	'pre_controller'              => function(){},
	'post_controller'             => function(){},
	'post_controller_constructor' => function(){},
	'post_system'                 => function(){},
);

/* End of file hooks.php */
/* Location: ./app/config/hooks.php */