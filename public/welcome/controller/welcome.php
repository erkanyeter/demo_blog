<?php

/**
 * $c welcome
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Auth;
        new Url;
        new Html;
        new View;
    }
);

$c->func(
    'index',
    function () {
       
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