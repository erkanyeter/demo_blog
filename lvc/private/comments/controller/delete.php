<?php

/**
 * $c posts/delete
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Db;
        new Get;
    }
);

$c->func(
    'index',
    function () { // deprecated
        try
        {
            $this->db->transaction();
            $this->db->prepare("DELETE FROM comments WHERE comment_id = ?");
            $this->db->bindValue(1, $this->get->get('id'), PARAM_INT);
            $this->db->execute();
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