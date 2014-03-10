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

        $sql = "SELECT * FROM posts JOIN users ON user_id = post_user_id";

        if ( ! empty($status)) {
            $sql.= " WHERE post_id = ".$this->db->escape($id);
        }
        $this->db->query($sql);

        $r = array(
            'results' => $this->db->getRowArray(),
        );

        echo json_encode($r);
    }
);