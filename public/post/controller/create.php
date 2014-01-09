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

    if($this->get->post('dopost')) // if do post click
    {
        $this->post->data['user_id']       = $this->auth->getIdentity('user_id');
        $this->post->data['title']         = $this->get->post('title');
        $this->post->data['content']       = $this->get->post('content');
        $this->post->data['tags']          = $this->get->post('tags');
        $this->post->data['status']        = $this->get->post('status');
        $this->post->data['creation_date'] = date('Y-m-d H:i:s');
        
        $this->post->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('posts', $this);
            }
            return false;
        });

        if($this->post->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.',SUCCESS);
            $this->url->redirect('/home');
        }
    }

	$c->view('create', function(){
		$this->set('title', 'Create New Post');
		$this->getScheme();
	});

});