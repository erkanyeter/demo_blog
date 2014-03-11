<?php

defined('STDIN') or die('Access Denied');

/**
 * $c clear
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
    }
);

$c->func(
    'index',
    function () {
        $this->_clear();  // Start the Clear Task
    }
);

$c->func(
    '_clear',
    function () {

        include APP .'config'. DS . strtolower(ENV) . DS .'logger'. EXT;  // get configuration of logger file driver

        $file = trim($logger['path'], '/');
        $file = str_replace('/', DS, $file);

        if (strpos($file, 'data') === 0) { 
            $PATH = DATA .'logs'. DS;
        } else {
            $PATH = $file;
        }
        if ( ! is_file($file)) {
            echo "\33[1;31mApplication log file does not exists.\33[0m\n";
            exit;
        }

        $exp = explode(DS, $file);
        $filename = end($exp);

        $clear_sh = "
        ####################
        #  CLEAR TASK  ( Clear your all application log files )
        ####################

        PROJECT_DIR=\${PWD}

        if [ ! -d ".PACKAGES." ]; then
            # Check the PACKAGES directory exists, so we know you are in the project folder.
            echo \"You must be in the project root ! Try cd /your/www/path/projectname\".
            return
        fi

        # define your paths.
        APP_LOG_DIR=\"".$PATH."\"

        # delete app directory log files.
        # help https://help.ubuntu.com/community/find

        find \$APP_LOG_DIR -name '".$filename."' -exec rm -rf {} \;
        echo \"\33[0m\33[1;36mApplication log files deleted.\33[0m\";";
        
        echo shell_exec($clear_sh);
    }
);

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */