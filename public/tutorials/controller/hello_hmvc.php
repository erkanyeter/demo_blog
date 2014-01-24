<?php

/**
 * $c hello_hmvc
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Url;
    new Html;
    new View;
    new Web;
});

$c->func('index',function() {

    $this->view->get('hello_hmvc', function() {

        $this->set('response_a', $this->web->get('tutorials/hello_dummy/test/1/2/3'));
        $this->set('response_b', $this->web->get('tutorials/hello_dummy/test/4/5/6'));

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));

    });
});

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */