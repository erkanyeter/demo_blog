<?php

if (isset($_SERVER['REMOTE_ADDR'])) die('Access denied');
/*
|--------------------------------------------------------------------------
| Log writer task
|--------------------------------------------------------------------------
| Send log data to queue
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
require OBULLO_CONFIG;
require OBULLO_AUTOLOADER;
/*
|--------------------------------------------------------------------------
| Hello Cli Task
|--------------------------------------------------------------------------
*/

echo "Hello World !\n\n";


/* End of file logger.php */
/* Location: .app/tasks/cli/welcome/start.php */