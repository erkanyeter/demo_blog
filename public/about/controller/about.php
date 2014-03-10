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
        new Hvc;
    }
);
$c->func(
    'index.public_user',
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