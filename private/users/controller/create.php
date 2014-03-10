<?php

/**
 * $c users/create
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
                'user_username'      => $this->post->get('user_username'),
                'user_email'         => $this->post->get('user_email'),
                'user_password'      => $this->post->get('user_password'),
                'user_creation_date' => date('Y-m-d H:i:s'),
            );
            $this->db->transaction();
            $this->db->insert('users', $data);
            $this->db->commit();
            $r = array(
                'success' => 1,
                'results' => array(),
                'message' => 'User saved successfully',
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