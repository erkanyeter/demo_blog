<?php

/**
 * $c posts/delete
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
    function ($id) {
        try
        {
            $this->db->transaction();
            $this->db->where('comment_id', $id);
            $this->db->delete('comments');
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