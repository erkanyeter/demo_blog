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
Please run [$php task start help] You are in [ app / tasks ] folder.'."\n\033[0m";

        echo "\nGENERAL HELP FOR TASK OPERATIONS\n";
            
        echo "
    YOU ARE IN APP/TASKS FOLDER\n
        1 . Running a task controller : \n\t > \$php task controller method argument1 argument2 ...\n
        2 . Running php files using task package: \n\t\n\tnew Task; \n\t\n\t\$this->task->run('controller/method/arg1/arg1 ..'); \n\t \n\t\n";
    }
);

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */