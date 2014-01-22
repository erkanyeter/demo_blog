<?php

/**
 * $c save.user
 * @var Controller
 */
$c = new Rest_Controller(function(){
    // __construct

    new Request; // listen requests
});

$c->func('create.user', function(){

    if($this->request->getMethod() == 'POST') // if request is post.
    {
        new Model('user', 'users');

        foreach($this->get->post('data') as $key => $val) // catch selected fields
        {
            $this->user->data[$key] = $val;
        }
        
        if(isset($this->user->data['user_username']))  // if user_username selected do callback_username unique validation
        {
            $this->user->func('callback_username', function(){
                $this->db->where('user_username', $this->get->post('user_username', true));
                $this->db->get('users');
                
                if($this->db->getCount() > 0) {  // unique control

                    $this->form->setMessage('callback_username', 'This username is already used');

                    return false;
                }
                return true;
            });
        }

        $this->user->func('insert', function() {

            if ($this->isValid()){  // Validate schema

                $bcrypt = new Bcrypt; // use bcrypt
                $this->data['user_password'] = $bcrypt->hashPassword($this->getValue('user_password'), 8);

                try
                {
                    $this->db->transaction();
                    $this->db->insert('users', $this);
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

        echo json_encode($this->user->getOutput());
    }

});