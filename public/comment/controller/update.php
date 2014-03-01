<?php

/**
 * $c update
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Sess;
        new Auth;
        new Form;
        new Hvc;
        new Trigger('private');
    }
);

$c->func(
    'index',
    function ($id, $status = 'approve') {

        $r = $this->hvc->put('private/comments/update/{'.$id.'}/'.$status);

        $this->form->setNotice($r['message'], $r['success']);  // set flash notice
        $this->url->redirect('/comment/display');
    }
);