<?php

/**
 * $c hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['View'];
        // $c['Cache'];

        // $this->cache->set('a', 'Wssssssss');
        // echo $this->cache->get('a');

        // echo $this->config['base_url'];
    }
);

$app->func(
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