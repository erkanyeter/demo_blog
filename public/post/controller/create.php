<?php

/**
 * $c create
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;

	new Model('post', 'posts');
});

$c->func('index', function() use($c){

	$c->view('create', function(){

		if($this->get->post('dopost')) // if do post click
    	{
    		$this->post->user_id = $this->auth->getIdentity('user_id');
    		$this->post->title 	 = $this->get->post('title');
    		$this->post->content = $this->get->post('content');
            $this->post->tags 	 = $this->get->post('tags');
            $this->post->status  = $this->get->post('status');
            $this->post->creation_date = date('Y-m-d H:i:s');
            
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

		$this->set('title', 'Create New Post');
		$this->getScheme();
	});

});