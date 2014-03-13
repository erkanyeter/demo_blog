<?php

/**
 * $c welcome
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new View;

        new Amqp_Rabbit;
    }
);

$c->func(
    'index',
    function () {

        $this->config->load('user/test');
        echo $this->config->getItem('test');


        $this->view->get(
            'welcome', 
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );
    }
);

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */