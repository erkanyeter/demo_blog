<?php

/**
 * $c members/getby.id.JSON
 * 
 * @var Controller
 */
$c = new Controller(function(){  

    new Post;
    new Form;
    new Model('user', false);
});

$c->func('index', function(){

    $this->user->data = $this->post->get(); // Get all $_POST data

    $this->form->setRules('user_id', 'User ID', 'required|_int');

    $this->user->func('read', function(){
        if ($this->isValid()){              // Validate schema
            
            $this->db->where('user_id', $this->getValue('user_id'));
            $this->db->get('users');
            $results = $this->db->getResultArray();

            $this->setResult($results);

            return true;
        }
        return false;
    });

    $this->user->read();

    echo json_encode($this->user->getAllOutput());

});