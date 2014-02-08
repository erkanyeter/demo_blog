<?php

/**
 * $c members/create.one.JSON
 * 
 * @var Controller
 */
$c = new Web_Service('public', function(){  
 	  if(ENV == 'LIVE') // Deny access to this folder in live mode.
    {
        $this->response->show404();
    }
});

$c->func('index', function(){

    new Task;
     
    $output = $this->task->run('web_model_doc/index', true);
    
    echo $output;

});