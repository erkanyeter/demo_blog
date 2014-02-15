<?php

/**
 * $c members/getby.id
 * 
 * @var Controller
 */
$c = new Controller(function(){  

    new Db;   // load database
    new Post;
});

$c->func('index', function(){

    $this->db->where('user_id', $this->post->get('user_id'));
    $this->db->get('users');

    $r = array(
    	'success' => 1, 
    	'results' => $this->db->getResultArray()
    );

    echo json_encode($r);

});