<?php

/**
 * $c create
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Form;
        new Sess; 
        new Auth;
        new Hvc;
        new Trigger('private');
    }
);

$c->func(
    'index',
    function ($id) {

        $r = $this->hvc->post('private/posts/delete/{'.$id.'}', array('user_id' => $this->auth->getIdentity('user_id')));

        $this->form->setNotice($r['message'], $r['success']); // set flash notice
        $this->url->redirect('/post/manage');
    }
);