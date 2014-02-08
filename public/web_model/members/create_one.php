<?php

/**
 * $c members/create.one.JSON
 * @var Controller
 */
$c = new Web_Service('private', function(){  

    new Post;
    new Model('user', 'users');
});

$c->func('index', function(){

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

    $this->user->insert();  // create new user        

    //---------------------------------------------

    echo json_encode($this->user->getAllOutput());

});

/*
@Visibilities

 Public    : Everybody can access this web controller.

 Protected : If you declare a web service controller as protected, 
             aynone couldn't access this web controller without sending a secret
            "SECRET KEY" which is defined in your web_service.php config file.

 Private   : If you declare a web service controller as private, 
             aynone couldn't access this web controller only you can access it using HMVC requests.
*/