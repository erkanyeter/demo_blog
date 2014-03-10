<?php

/**
 * $c comments/getcount
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Post;
        new Db;
    }
);

$c->func(
    'index',
    function ($status = 0) {

        $this->db->query('SELECT * FROM comments WHERE comment_status = '.$status);
        
        $r = array(
            'results' => array('count' => $this->db->getCount()),
        );

        echo json_encode($r);
    }
);