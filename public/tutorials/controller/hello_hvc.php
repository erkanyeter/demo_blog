<?php

/**
 * $c hello_hmvc
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new View;
        new Hvc;
    }
);

$c->func(
    'index',
    function () {

        $ra = $this->hvc->get('tutorials/hello_dummy/test/1/2/3');
        $rb = $this->hvc->get('tutorials/hello_dummy/test/4/5/6');

        $this->view->get(
            'hello_hvc', 
            function () use ($ra, $rb) {

                $this->set('response_a', $ra);
                $this->set('response_b', $rb);
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );
    }
);

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */