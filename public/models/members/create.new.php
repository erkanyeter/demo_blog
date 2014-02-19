<?php

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

    $data = $this->post->get(); // Get all post data

    $data['user_password'] = $this->bcrypt->hashPassword($data['user_password'], 8);

    try
    {
        $this->db->transaction();

        $this->db->where('user_emaile', 'me@test.com');
        $rows = $this->db->update('users', $data);

        $r = array(
            'success' => 1,
            'results' => array(
                'user_id' => $this->db->insertId(), 
                'count' => $rows
            ),
        );

        $this->db->commit();
    } 
    catch(Exception $e)
    {
        $r = array(
            'success' => 0,
            'message' => 'translate:failure',
            'e' => $e->getMessage(),
        );

        $this->db->rollBack();
    }

    echo json_encode($r);

});