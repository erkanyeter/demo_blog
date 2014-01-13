<?php

/**
 * $c signup
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
	new Url;
	new Html;
	new Form;
	new Get;
    new Model('user', 'users');
});

$c->func('index', function() use($c){

    if($this->get->post('dopost')) // if do post click
    {
        $this->user->data['user_username']      = $this->get->post('user_username');
        $this->user->data['user_email']         = $this->get->post('user_email');
        $this->user->data['user_password']      = $this->get->post('user_password');
        $this->user->data['user_creation_date'] = date('Y-m-d H:i:s');

        //--------------------- set non schema rules

        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
        $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');
        
        //---------------------
        
        $this->user->func('callback_username', function(){
            $this->db->where('user_username', $this->get->post('user_username', true));
            $this->db->get('users');
            
            if($this->db->count() > 0) {  // unique control
                $this->form->setMessage('callback_username', 'This username is already used');
                return false;
            }
            return true;
        });

        $this->user->func('save', function() {
            if ($this->isValid()){
                
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
                    $this->setFailure($e->getMessage());  // Set rollback message to error messages.
                }
            }

            return false;
        });

        if($this->user->save()) {  // save user        
            $this->form->setNotice('User saved successfully.',SUCCESS);
            $this->url->redirect('/login');
        }
    }

    $c->view('signup_form', function() {

        $this->set('title', 'Signup to my blog');
        $this->getScheme();
    });
});