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
$hooks['pre_controller']              = function(){};
$hooks['post_controller']             = function(){};
$hooks['post_controller_constructor'] = function(){};
$hooks['post_system']                 = function(){};


/* End of file hooks.php */
/* Location: ./app/config/hooks.php */