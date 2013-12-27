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

    $c->view('signup_form', function() use($c) {
        
    	if($this->get->post('dopost')) // if do post click
    	{
    		$this->user->username = $this->get->post('username');
    		$this->user->email    = $this->get->post('email');
            $this->user->password = $this->get->post('password');
            $this->user->creation_date = date('Y-m-d H:i:s');

            //--------------------- set non schema rules
            
            $this->user->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->user->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
            
            //---------------------
            
            $this->user->func('check_username', function(){
            	$this->db->where('user_username', $this->get->post('username', true));
            	$this->db->get('users');

            	if($this->db->count() > 0) // unique control
            	{
            		$this->setMessage('check_username', 'This username is already used');
            		return false;
            	}
            	return true;
            });

            $this->user->func('save', function() {
                if ($this->isValid()){
                	$bcrypt = new Bcrypt; // use bcrypt

                    $this->password = $bcrypt->hashPassword($this->getValue('password'), 8);

                    return $this->db->insert('users', $this);
                }
                return false;
            });

            if($this->user->save())  // save user
            {        
                $this->form->setNotice('User saved successfully.');
                $this->url->redirect('/login?success=true');
            }

    	}

        $this->set('title', 'Signup to my blog');
        $this->getScheme();
        
    });
    
});