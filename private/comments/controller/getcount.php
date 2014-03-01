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

        $this->db->where('comment_status', $status);
        $this->db->get('comments');

        $r = array(  
            'results' => array('count' => $this->db->getCount()),
        );

        echo json_encode($r);
    }
);