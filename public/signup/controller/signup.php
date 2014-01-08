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
        $this->user->join('posts', array('title', 'content'));

        $this->user->data['username']      = $this->get->post('username');
        $this->user->data['title']         = 'test';
        $this->user->data['content']       = 'contentesdsa sad';
        $this->user->data['email']         = $this->get->post('email');
        $this->user->data['password']      = $this->get->post('password');
        $this->user->data['creation_date'] = date('Y-m-d H:i:s');

        //--------------------- set non schema rules

        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
        
        //---------------------
        
        $this->user->func('callback_username', function(){

            $this->db->where('user_username', $this->get->post('username', true));
            $this->db->get('users');
            
            if($this->db->count() > 0) // unique control
            {
                $this->form->setMessage('callback_username', 'This username is already used');
                return false;
            }
            return true;
        });

        $this->user->func('save', function() {
            if ($this->isValid()){
                $bcrypt = new Bcrypt; // use bcrypt
                $this->password = $bcrypt->hashPassword($this->getValue('password'), 8);

                $this->db->insert('users', $this);
                $this->db->insert('posts', $this);
            }

            return false;
        });

        if($this->user->save())  // save user
        {        
            $this->form->setNotice('User saved successfully.',SUCCESS);
            $this->url->redirect('/login');
        }
    }

    $c->view('signup_form', function() {

        $this->set('title', 'Signup to my blog');
        $this->getScheme();
    });
    
});