<?php

/**
 * $c posts/getallmanage
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Db;
        new Get;
    }
);

$c->func(
    'index',
    function () {

        if ($this->get->get('post_title')) {
            $this->db->like('post_title', $this->get->get('post_title'));
        }
        if ($this->get->get('post_status')) {
            $this->db->like('post_status', $this->get->get('post_status'));
        }
        
        $this->db->join('users', 'user_id = post_user_id');
        $this->db->get('posts');

        $r = array(
            'results' => $this->db->getResultArray(),
        );

        echo json_encode($r);
    }
);