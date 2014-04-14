<?php

defined('STDIN') or die('Access Denied');

/**
 * $app help
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
    }
);
$app->func(
    'index',
    function () {

        echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
Please run [$php task help] You are in [ app / tasks ] folder.'."\n\033[0m\n";

        echo "\33[1;36mUsage:\33[0m\33[0;36m
php task [command] [arguments]\n\33[0m\n";

        echo "\33[1;36mAvailable commands:\33[0m\33[0;36m
log        : Follows the application log file.
clear      : Clear application log data. It is currently located in data folder.
update     : Update your Obullo version.
help       : See list all of available commands.\33[0m\n";
    }
);

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */