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
        $c['cache'];
    }
);

$app->func(
    'index', 
    function () {

        $this->cache->set('aly', 'tesasdt deneme', 5);
        // $this->cache->set('aly1', 'tesasdt deneme', 7);
        // $this->cache->set('aly2', 'tesasdt deneme', 10);
        // $this->cache->set('aly3', 'tesasdt deneme', 3);
        echo '<pre>';
        print_r($this->cache->get('aly'));
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