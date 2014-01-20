<?php

/**
 * $c login
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;
	new Get;
	new View;
});

$c->func('index', function(){

	if($this->get->post('dopost'))  // check login button is submit ?
	{
    	$this->form->setRules('email', 'Email', 'required|validEmail');
    	$this->form->setRules('password', 'Password', 'required');

        if($this->form->isValid())  // check form validation
        {	
			$this->auth->attemptQuery(	 // login, query

			    $_POST['email'],
			    $_POST['password']
			    
			);

			if($this->auth->isValid())  // check auth is success
			{
			    $row = $this->auth->getRow();
		        $this->auth->authorizeMe(); // Authorize to user

		        $this->auth->setIdentity('user_username', $row->user_username); // Set user data to auth container
		        $this->auth->setIdentity('user_email', $row->user_email);
		        $this->auth->setIdentity('user_id', $row->user_id);

		        $this->url->redirect('/home'); // Success redirect
			} 

			$this->form->setNotice('Wrong username / password combination.', ERROR);
			$this->url->redirect('/login');
        }
	}

    $this->view->get('login', function() {

        $this->set('title', 'Login to My blog');
        $this->getScheme();
    });
    
});
