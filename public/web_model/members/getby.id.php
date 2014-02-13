<?php

/**
 * $c members/getby.id
 * 
 * @var Controller
 */
$c = new Controller(function(){  

    new Db;   // load database
});

$c->func('index', function(){

    $this->db->where('user_id', $_POST['user_id']);
    $this->db->get('users');

    echo json_encode(array('success' => 1, 'results' => $this->db->getResultArray()));

});