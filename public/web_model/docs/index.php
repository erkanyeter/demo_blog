<?php

/**
 * $c members/create.one.JSON
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
     
    $output = $this->task->run('web_model/index', true);
    echo $output;
});