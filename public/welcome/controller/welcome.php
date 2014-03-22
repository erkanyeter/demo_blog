<?php

/**
 * $c welcome
 * 
 * @var Controller
 */
$o = new Controller(
    function () {
        global $c;
        $c['Html'];
        $c['Url'];
        $c['View'];
    }
);

$o->func(
    'index',
    function () {
       
        global $c;

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