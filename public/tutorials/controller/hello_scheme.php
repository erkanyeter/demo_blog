<?php

/**
 * $c hello_scheme
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Html;
        new Url;
        new View;
    }
);

$c->func(
    'index',
    function () {

        $this->view->get(
            'hello_scheme',
            function () {
                $this->set('name', 'Obullo');
                $this->set('title', 'Hello Scheme World !');
                $this->getScheme('welcome');
            }
        );
    }
);


/* End of file hello_scheme.php */
/* Location: .public/tutorials/controller/hello_scheme.php */