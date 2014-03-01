<?php

/**
 * $c posts/getone
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Post;
        new Db;
    }
);

$c->func(
    'index', 
    function ($id, $status = '') {

        $this->db->where('post_id', $id); 

        if ( ! empty($status)) {
            $this->db->where('post_status', $status);
        }

        $this->db->join('users', 'user_id = post_user_id');
        $this->db->get('posts');

        $r = array(
            'results' => $this->db->getRowArray(),
        );

        echo json_encode($r);
    }
);