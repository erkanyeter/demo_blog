<?php

/**
 * $c about
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new View;
        new Sess;
        new Auth;
        new Hvc;
        new Trigger('public'); // run triggers
    }
);

$c->func(
    'index',
    function () {
        
        $this->view->get(
            'about',
            function () {
                $this->set('title', 'About');
                $this->getScheme();
            }
        );
    }
);

/* End of file about.php */
/* Location: .public/about/controller/about.php */