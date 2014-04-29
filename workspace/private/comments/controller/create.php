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
        // new Pdo_Crud; @new crud but we don't use it in insert
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
            // $this->db->transaction(); !!!!!!! @deprecated
            // $this->db->insert('comments', $data);
            // $this->db->commit();
            
            //----------- Secure Pdo Insert -----------//

            $this->db->transaction();  
            $this->db->prepare(
                'INSERT INTO comments (
                    comment_post_id,
                    comment_name,
                    comment_email,
                    comment_website,
                    comment_body,
                    comment_creation_date
                    ) 
                VALUES (?,?,?,?,?,?);'
            );

            // NOT NEED TO PROVIDE PARAMETER LENGTH
            // http://stackoverflow.com/questions/8901053/pdostatementbindparam-using-pdoparam-str-what-does-the-length-specify

            $this->db->bindValue(1, $data['comment_post_id'], PARAM_INT);
            $this->db->bindValue(2, $data['comment_name'], PARAM_STR);
            $this->db->bindValue(3, $data['comment_email'], PARAM_BOOL); 
            $this->db->bindValue(4, $data['comment_website'], PARAM_STR);
            $this->db->bindValue(5, $data['comment_body'], PARAM_STR);
            $this->db->bindValue(6, $data['comment_creation_date'], PARAM_STR);
            $this->db->execute();
            $this->db->commit();

            //----------- Secure Pdo Insert -----------//

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