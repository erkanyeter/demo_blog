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
});

$c->func('index', function() use($c){

    $c->view('login', function() use($c) {
        
    	if($this->get->post('dopost'))
    	{
        	$this->form->setRules('email', 'Email', 'required|validEmail');
        	$this->form->setRules('password', 'Password', 'required');

            if($this->form->run())
            {
				$this->auth->attemptQuery(
				    $this->get->post('email'),
				    $this->get->post('password')
				);

				if($this->auth->isValid())
				{
				    $row = $this->auth->getRow();
				    
			        $this->auth->authorizeMe(); // Authorize to user

			        // Set user data to auth container
			        $this->auth->setIdentity('user_username', $row->user_username);
			        $this->auth->setIdentity('user_email', $row->user_email);
			        $this->auth->setIdentity('user_id', $row->user_id);

			        $this->url->redirect($this->auth->item('dashboard_url')); // Success redirect
				} 

				$this->form->setNotice('Wrong username / password combination.');
				$this->url->redirect($this->auth->item('login_url'));
            }
    	}

        $this->set('title', 'Login to My blog');
        $this->getScheme();
    });
    
});
