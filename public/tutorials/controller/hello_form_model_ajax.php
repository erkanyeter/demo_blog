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

	new Model('user', false); // Disable file schema using second parameter as "false"
							  // Now user object will use just form post values.
});

$c->func('index', function(){
	
	if($this->post->get('dopost'))
	{
		$this->form->setRules('user_email', 'Email', 'required|validEmail');
		$this->form->setRules('user_password', 'Password', 'required|callback_password');

		//-------- Geting post values ( Optional )

		$this->user->data = array(
			'user_email'    => $this->post->get('user_email'),
			'user_password' => $this->post->get('user_password'),
		);

		//--------

		$this->user->func('callback_password', function(){
			$this->setMessage('callback_password', 'Not correct.');
			return false;
		});

		$this->user->isValid();

		print_r($this->user->getAllOutput());

		/*  
		Array
				(
				    [errors] => Array
				        (
				            [user_email] => The Email field is required.
				            [user_password] => The Password field is required.
				        )

				    [messages] => Array
				        (
				            [success] => 0
				            [key] => validationError
				            [code] => 10
				            [string] => There are some errors in the form fields.
				            [translated] => There are some errors in the form fields.
				        )

				    [values] => Array
				        (
				            [user_email] => 
				            [user_password] => 
				        )

				)
		 */
	}

    $this->view->get('hello_form_model_ajax', function(){
    	
        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });
    
});   

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */