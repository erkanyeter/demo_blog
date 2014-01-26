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
	new Sess;
	new Auth;

	new Trigger('public','header'); // run triggers
});

$c->func('index', function(){

	if($this->get->post('dopost'))  // login button is submit ?
	{
    	$this->form->setRules('email', 'Email', 'required|validEmail');
    	$this->form->setRules('password', 'Password', 'required');

        if($this->form->isValid())  // form is valid ?
        {	
			$row = $this->auth->query($_POST['email'], $_POST['password']); // send post data to auth
          
          	if($row !== false) // validate the auth !
            {
		        $this->auth->authorizeMe();   // Authorize to user
		        $this->auth->setIdentity('user_username', $row->user_username); // Set user data to auth container
		        $this->auth->setIdentity('user_email', $row->user_email);
		        $this->auth->setIdentity('user_id', $row->user_id);

		        $this->url->redirect('/home'); // Success redirect
			}

			//----------- End Auth Data ---------//

			$this->form->setNotice('Wrong username / password combination.', ERROR);
			$this->url->redirect('/login');
        }
	}

    $this->view->get('login', function() {

        $this->set('title', 'Login to My blog');
        $this->getScheme();
    });
    
});
