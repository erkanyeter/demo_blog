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

        $this->logger->notice('test');
        $this->logger->push('mongo', LOG_NOTICE);
        $this->logger->notice('ehhhehe');

        // $this->hvc->get('private/comments/getuser', array('user_id' => 5), $expiration = 7200);
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