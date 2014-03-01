<?php

/**
 * $c posts/delete
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Db;
        new Post;
    }
);

$c->func(
    'index',
    function ($id) {
        try
        {
            $this->db->transaction();

            $this->db->where('post_id', $id);
            $this->db->where('post_user_id', $this->post->get('user_id'));  // just delete from this user data
            $this->db->delete('posts');

            $this->db->commit();

            $r = array(
                'success' => 1,
                'results' => array(),
                'message' => 'Post deleted successfully',
            );
        } 
        catch(Exception $e)
        {
            $r = array(
                'success' => 0,
                'message' => 'failure',
                'e' => $e->getMessage(),
            );

            $this->db->rollBack();
        }

        echo json_encode($r);
    }
);