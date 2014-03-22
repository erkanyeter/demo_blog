<?php

/**
 * $c hello_world
 * 
 * @var Controller
 */
$o = new Controller(
    function () {
        global $c;
        $c['View'];
        $c['Sess'];

        echo $this->config->get('base_url');
    }
);

$o->func(
    'index',
    function () {

        // $this->logger->debug('test', 'sd');

        $this->view->get(
            'hello_world', 
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );      
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */