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
        new Hvc;
    }
);

$c->func(
    'index.private_user',
    function ($id) {
        
        $r = $this->hvc->delete('private/comments/delete/'.$id);

        $this->form->setNotice($r['message'], $r['message']);       // set flash notice
        $this->url->redirect('/comment/display');
    }
);
