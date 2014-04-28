<?php

/**
 * $c hello_scheme
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['html'];
        $c['view'];
        $c['url'];
    }
);

$app->func(
    'index',
    function () {

        $this->view->load(
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