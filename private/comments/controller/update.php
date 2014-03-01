<?php

/**
 * $c comments/update
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
    function ($id, $status = 'approve') {
        try
        {
            $value = ($status == 'approve') ? 1 : 0;
            
            $this->db->transaction();
            $this->db->where('comment_id', $id);
            $this->db->update('comments', array('comment_status' => $value));
            $this->db->commit();

            $r = array(
                'success' => 1,
                'results' => array(),
                'message' => 'Comment updated successfully',
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