<?php

/**
 * $c get/getstatuslist
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {

    }
);

$c->func(
    'index',
    function () {

        $posts = getSchema('posts');  // read database schema from app/schemas 
        $list  = array();

        foreach ($posts['post_status']['_enum'] as $v) {
            $list[$v] = $v;
        }

        $r = array(
            'results' => $list,
        );

        echo json_encode($r);
    }
);