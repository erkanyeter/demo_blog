<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

	new View;
	new Db;
	new Post;

	new Model('user', 'users');
});

$c->func('index', function(){

	$_POST['user_username'] = 'test';

    $this->user->data = $this->post->get(); // Get all $_POST data

    if(isset($this->user->data['user_username']))  // if user_username selected do callback_username unique validation
    {
        $this->user->func('callback_username', function(){
            $this->db->where('user_username', $this->post->get('user_username', true));
            $this->db->get('users');

            if($this->db->getCount() > 0) {  // unique control
                $this->form->setMessage('callback_username', 'This username is already used');
                return false;
            }
            return true;
        });
    }

    $this->user->func('insert', function(){

        if ($this->isValid()){    // Validate schema
            $bcrypt = new Bcrypt; // use bcrypt
            $this->data['user_password'] = $bcrypt->hashPassword($this->getValue('user_password'), 8);

            try
            {
                $this->db->transaction();
                $this->db->insert('users', $this);
                $this->setValue('insert_id', $this->db->insertId()); // send insert id in value
                $this->db->commit();

                return true;
            } 
            catch(Exception $e)
            {
                $this->db->rollBack();
                $this->setFailure($e);  // Set rollback message to error messages.
            }
        }
        return false;
    });

    //---------------------------------------------

    var_dump($this->user->insert());  // create new user        

    print_r($this->user->getAllOutput());

    //---------------------------------------------

    $this->view->get('hello_world', function(){
    	
        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });
    
});   

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */