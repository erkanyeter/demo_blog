<?php

/**
 * $c manage
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Form;
        new View;
        new Sess;
        new Auth;
        new Post;
        new Db;
        new Hvc;
    }
);

$c->func(
    'index.private_user',
    function () {

        $r = $this->hvc->get('private/posts/getallmanage'); // get all post data

        $this->view->get(
            'manage',
            function () use ($r) {
                $this->set('title', 'Manage Posts');
                $this->set('posts', $r['results']);
                $this->getScheme();  // hmvc yapınca request resetleniyor
            }
        );
    }
);