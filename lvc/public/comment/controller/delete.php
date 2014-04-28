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
    'index.Private_User',
    function ($id) {
        
        $r = $this->hvc->delete('private/comments/delete/', array('id' => $id));

        $this->form->setNotice($r['message'], $r['message']);       // set flash notice
        $this->url->redirect('/comment/display');
    }
);
