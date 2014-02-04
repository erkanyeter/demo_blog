<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

	new View;
});

$c->func('index', function(){

    $this->view->get('hello_world', function(){
    	
        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });
    
});   

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
