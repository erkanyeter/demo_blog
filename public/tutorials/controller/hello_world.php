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

        $collection = new MongoCollection($c['mongo'], 'users');
        $cursor = $collection->find(array('user_email' => 'eguvenc@gmail.com'));

        foreach ($cursor as $docs) {
            echo $docs['user_email'];
        }

    }
);

$app->func(
    'index',  // visitor.guest
    function () {

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