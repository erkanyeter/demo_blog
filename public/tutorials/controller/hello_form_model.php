<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

	new Url;
	new Html;
	new View;
	new Post;

	new Model('user', false); // Disable file schema, using second parameter as "false"
							  // Now user object will use Form object as a model.
});

$c->func('index', function(){
	
	if($this->post->get('dopost')) // if submit button click !
	{
		$this->form->setRules('user_email', 'Email', 'required|validEmail');
		$this->form->setRules('user_password', 'Password', 'required|callback_password');

		$this->user->func('callback_password', function(){
			if($_POST['user_password'] != '123'){
				$this->setMessage('callback_password', 'Password not correct.');
				return false; // wrong password
			}
			return true;
		});

		if($this->user->isValid())  // check validation
		{
			$this->user->setMessage('message', 'Form is successfully validated.');
		}
	}

    $this->view->get('hello_form_model', function(){
    	
        $this->set('name', 'Obullo');
        $this->set('footer', $this->getTpl('footer', false));
    });
    
});   

/* End of file hello_form_model.php */
/* Location: .public/tutorials/controller/hello_form_model.php */