<?php

/**
 * $c posts/create
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
    function () {
        try
        {
            $data = array(
                'post_user_id'       => $this->post->get('user_id'),
                'post_title'         => $this->post->get('post_title'),
                'post_content'       => $this->post->get('post_content'),
                'post_tags'          => $this->post->get('post_tags'),
                'post_status'        => $this->post->get('post_status'),
                'post_creation_date' => date('Y-m-d H:i:s'),
            );

            $this->db->transaction();
            $this->db->insert('postsss', $data);
            $this->db->commit();

            $r = array(
                'success' => 1,
                'results' => array(),
                'message' => 'Post saved successfully',
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