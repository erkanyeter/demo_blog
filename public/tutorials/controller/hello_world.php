<?php

/**
 * $c hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['view'];
        // $c['db'];
        
        // $this->config['debug'] = true;
    }
);

$app->func(
    'index', 
    function () {

        $nested = new Nested_Category;
        
        // $nested->buildTree('first');
        // $nested->insertFirstChild(1, 1, 'node');

        $this->view->load(
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