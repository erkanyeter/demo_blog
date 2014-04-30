<?php

if (isset($_SERVER['REMOTE_ADDR'])) die('Access denied');
/*
|--------------------------------------------------------------------------
| IP Address
|--------------------------------------------------------------------------
| Prevent ip adress errors
|
*/
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
*/
require 'constants';
require OBULLO_CONTAINER;
require OBULLO_CORE;
require OBULLO_AUTOLOADER;
/*
|--------------------------------------------------------------------------
| Hello Cli Task
|--------------------------------------------------------------------------
*/

echo "Hello World !\n\n";


/* End of file logger.php */
/* Location: .app/tasks/cli/welcome/start.php */