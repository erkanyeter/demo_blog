<?php

/**
 * $app welcome
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['Html'];
        $c['Url'];
        $c['View'];
    }
);

$app->func(
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