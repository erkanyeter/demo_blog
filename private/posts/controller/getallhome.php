<?php

/**
 * $c posts/getallhome
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
    function () {

        $this->db->select(
            "*, IFNULL((SELECT count(*) FROM comments WHERE posts.post_id = comment_post_id AND comment_status = 1 
            GROUP BY posts.post_id LIMIT 1),0) as total_comment", false
        );
        
        $this->db->join('users', 'user_id = post_user_id');
        $this->db->where('post_status', 'Published');
        $this->db->get('posts');

        $r = array(
            'results' => $this->db->getResultArray(),
        );

        echo json_encode($r);
    }
);