<?php

/**
 * $c docs
 * 
 * @var Controller
 */
$c = new Controller(function(){

 	if(ENV == 'LIVE') // Deny access to this folder in live mode.
    {
    	$this->response->show404();
    }
});

$c->func('index', function(){

    new Task;
     
    $output = $this->task->run('doc/index', true);
    echo $output;
});