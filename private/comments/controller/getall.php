<?php

/**
 * $c posts/getall
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Db;
        new Pdo_Crud;
    }
);

$c->func(
    'index',
    function ($id = '', $status = 1) {

        $this->db->join('posts', 'post_id = comment_post_id');

        if ( ! empty($id)) {
            $this->db->where('comment_post_id', $id);
            $this->db->where('comment_status', $status);
        }
        $this->db->orderBy('comment_status', 'ASC');
        $this->db->get('comments');

        $r = array(
            'results' => $this->db->getResultArray(),
        );

        echo json_encode($r);
    }
);

