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
        $c['db'];
        $c['cache'];

        

        // $this->config['debug'] = false;
        // echo $a;
    }
);

$app->func(
    'index', 
    function () {

        $this->view->load(
            'hello_world', 
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->getTpl('footer', false));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */