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
        
        new Unit_Test;

        // TEST DATABASE NOT

        // $r = $this->hvc->delete('private/comments/delete/', array('id' => $id));

        $this->unit->run($r['success'], true, 'delete comment');

    }
);
