<?php

/**
 * Web Model Standards
 *        
 *  $results = array(      
 *   'success' => 1,
 *   'results' => array('user_id' => $this->db->insertId(), 'count' => $rows),
 *   'e' => '',
 *  ); 
 */

/**
 * $c members/create.new
 * 
 * @var Controller
 */
$c = new Controller(function(){  

    new Post;
    new Db;
    new Bcrypt;
});

$c->func('index', function(){

    $data = $this->post->get(); // Set all post data

    $data['user_password'] = $this->bcrypt->hashPassword($_POST['user_password'], 8);

    try
    {
        $this->db->transaction();
        $this->db->where('user_emaile', 'me@test.com');
        $rows = $this->db->update('users', $data);

        $results = array(
            'success' => 1,
            'results' => array('user_id' => $this->db->insertId(), 'count' => $rows),
            'e' => '',
        );

        $this->db->commit();
    } 
    catch(Exception $e)
    {
        $results = array(
            'success' => 0,
            'message' => 'translate:failure',
            'e' => $e->getMessage(),
        );

        $this->db->rollBack();
    }

    echo json_encode($results);

});