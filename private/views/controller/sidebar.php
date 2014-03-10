<?php

/**
 * $c Header Controller
 *
 * @var Private View Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Auth;
        new View;
        new Hvc;
    }
);

$c->func(
    'index',
    function () {

        // Note: Hvc View Controller output must be string !

        echo $this->view->get(
            'sidebar',
            function () {
                if ($this->auth->hasIdentity()) {

                    $comments = $this->hvc->get('private/comments/getcount')['results']['count'];
                    
                    $this->set('auth', $this->auth->hasIdentity());
                    $this->set('username', $this->auth->getIdentity('user_username'));
                    $this->set('total_comments', $comments);
                }
            },
            false
        );
    }
);