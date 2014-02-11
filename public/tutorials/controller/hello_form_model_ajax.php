<?php

/**
 * $c hello_world
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

	new Request;
	new Model('user', false); // Disable file schema using second parameter as "false"
							  // Now user object will use just form post values.
});

$c->func('index', function(){

	if($this->request->isXmlHttp())  // Is request Ajax ? 
	{
		new Post;

		//-------- To geting post values from output ( Optional )

		$this->user->data = array(
			'user_email'    => $this->post->get('user_email'),
			'user_password' => $this->post->get('user_password'),
		);

		//--------

		$this->form->setRules('user_email', 'Email', 'required|validEmail');
		$this->form->setRules('user_password', 'Password', 'required');
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
        $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');

		$this->user->func('callback_password', function(){
			if($_POST['user_password'] != '123'){
				$this->setMessage('callback_password', 'Password not correct.');
				return false; // wrong password
			}
			return true;
		});

        if($this->user->isValid()) // Call save function
        {
            $this->user->setMessage('alert', 'Data Saved !');
            // $this->user->setMessage('redirect', 'http://google.com/');
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json;charset=UTF-8');

        echo json_encode($this->user->getOutput());
        // echo json_encode($this->user->getAllOutput());  // Get all output function also gives the values for debug.

	} 
	else 
	{
        new Url;
        new Html;
        new View;

	    $this->view->get('hello_form_model_ajax', function(){
	    	
	        $this->set('name', 'Obullo');
	        $this->set('footer', $this->tpl('footer', false));
	    });
	}
    
});   

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */