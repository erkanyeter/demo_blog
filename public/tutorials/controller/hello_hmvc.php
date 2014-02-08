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

    $response_a = $this->web->get('tutorials/hello_dummy/test/1/2/3');
    $response_b = $this->web->get('tutorials/hello_dummy/test/4/5/6');

    $this->view->get('hello_hmvc', function() use($response_a, $response_b) {

        $this->set('response_a', $response_a);
        $this->set('response_b', $response_b);

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));

    });
});

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */