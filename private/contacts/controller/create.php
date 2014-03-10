<?php

/**
 * $c contacts/create
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
                'contact_name'          => $this->post->get('contact_name'),
                'contact_email'         => $this->post->get('contact_email'),
                'contact_subject'       => $this->post->get('contact_subject'),
                'contact_body'          => $this->post->get('contact_body'),
                'contact_creation_date' => date('Y-m-d H:i:s'),
            );
            $this->db->transaction();
            $this->db->insert('contacts', $data);
            $this->db->commit();
            
            $r = array('success' => 1,
                'results' => array(),
                'message' => 'Contact saved successfully',
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