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
    new View;
    new Sess;
    new Auth;
    new Post;

    new Trigger('public','header');
    new Model('user', 'users');
});

$c->func('index', function(){

    if($this->post->get('dopost')) // if do post click
    {
        $this->user->data = array(
            'user_username'      => $this->post->get('user_username'),
            'user_email'         => $this->post->get('user_email'),
            'user_password'      => $this->post->get('user_password'),
            'user_creation_date' => date('Y-m-d H:i:s'),
        );

        //--------------------- set non schema rules

        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
        $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');
        
        //---------------------
        
        $this->user->func('callback_username', function(){
            $this->db->where('user_username', $this->post->get('user_username', true));
            $this->db->get('users');
            
            if($this->db->getCount() > 0) {  // unique control
                $this->form->setMessage('callback_username', 'This username is already used');
                return false;
            }
            return true;
        });

        $this->user->func('save', function() {

            if ($this->isValid()){
         
                $this->data['user_password'] = $this->auth->hashPassword($this->getValue('user_password'), 8);

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

        if($this->user->save()) {  // save user        
            $this->form->setNotice('User saved successfully.',SUCCESS);
            $this->url->redirect('/login');
        }
    }
    
    $this->view->get('signup_form', function() {

        $this->set('title', 'Signup to my blog');
        $this->getScheme();
    });
});