<?php

/**
 * $c users/getcount
 * 
 * @var Controller
 */
$c = new Controller(
    function () {  
        new Post;
        new Db;
        new Pdo_Crud;
    }
);

$c->func(
    'index',
    function () {

        if ($this->post->get('user_username')) {
            $this->db->where('user_username', $this->post->get('user_username', true));
        }
        $this->db->get('users');
        $r = array(  
            'results' => array('count' => $this->db->getCount()),
        );
        echo json_encode($r);
    }
);