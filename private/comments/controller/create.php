<?php

/**
 * $c comments/create
 * 
 * @var Private Controller
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
        try
        {
            $data = array(
                'comment_post_id'       => $this->post->get('comment_post_id'),
                'comment_name'          => $this->post->get('comment_name'),
                'comment_email'         => $this->post->get('comment_email'),
                'comment_website'       => $this->post->get('comment_website'),
                'comment_body'          => $this->post->get('comment_body'),
                'comment_creation_date' => date('Y-m-d H:i:s'),
            );
            $this->db->transaction();
            $this->db->insert('comments', $data);
            $this->db->commit();
            $r = array(
                'success' => 1,
                'results' => array(),
                'message' => 'Comment saved successfully',
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