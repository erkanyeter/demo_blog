<?php

/**
 * $c members/getby.id
 * 
 * @var Controller
 */
$c = new Controller(function(){  

    new Db;   // load database
    new Json;
});

$c->func('index', function(){

    $this->db->where('user_id', $_POST['user_id']);
    $this->db->get('users');

    $this->json->data = array(
    	'success' => 1, 
    	'results' => $this->db->getResultArray()
    );

    echo $this->json->encode();

});