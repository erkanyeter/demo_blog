<?php

/**
 * $c display
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Form;
        new Date_Format;
        new View;
        new Hvc;
    }
);

$c->func(
    'index.Private_User',
    function () {
        
        $r = $this->hvc->get('private/comments/getall');

        $this->view->get(
            'display',
            function () use ($r) {
                $this->set('title', 'Display Comments');
                $this->set('comments', $r['results']);
                $this->getScheme();
            }
        );
    }
);
