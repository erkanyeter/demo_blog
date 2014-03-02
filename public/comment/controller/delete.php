<?php

/**
 * $c delete
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
        
        $r = $this->hvc->delete('private/comments/delete/{'.$id.'}');

        $this->form->setNotice($r['message'], $r['message']);       // set flash notice
        $this->url->redirect('/comment/display');
    }
);
