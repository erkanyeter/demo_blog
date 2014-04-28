<?php

/**
 * $c hello_task
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['Url'];
        $c['Task'];
    }
);

$app->func(
    'index',
    function ($mode = '') {

        echo $this->url->anchor('tutorials/hello_task/index/help', 'Click Here to Help.');

        if ($mode == 'help') {
            $output = $this->task->run('help/index', true);  // use without true when ENV == LIVE.
            echo '<pre>'.$output.'</pre>';
        } else {
            $output = $this->task->run('start/index', true); 
            echo '<pre>'.$output.'</pre>';
        }
    }
);


/* End of file hello_task.php */
/* Location: .public/tutorials/controller/hello_task.php */