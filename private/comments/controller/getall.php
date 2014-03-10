<?php

/**
 * $c posts/getall
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Db;
    }
);

$c->func(
    'index',
    function ($id = '', $status = 1) {

        $sql = "SELECT * FROM comments JOIN posts ON post_id = comment_post_id";

        if ( ! empty($id)) {
            $sql.= " WHERE comment_post_id = ".$this->db->escape($id);
            $sql.= " AND comment_status = ".$this->db->escape($status);
        }
        $sql.= " ORDER BY comment_status ASC";
        $this->db->query($sql);

        $r = array(
            'results' => $this->db->getResultArray(),
        );

        echo json_encode($r);
    }
);

