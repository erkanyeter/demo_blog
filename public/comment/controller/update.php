<?php

/**
 * $c update
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Form;
        new Hvc;
    }
);

$c->func(
    'index.private_user',
    function ($id, $status = 'approve') {

        $r = $this->hvc->put('private/comments/update/'.$id.'/'.$status);

        $this->form->setNotice($r['message'], $r['success']);  // set flash notice
        $this->url->redirect('/comment/display');
    }
);