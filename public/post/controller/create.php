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
    new View;

	new Model('post', 'posts');
});

$c->func('index', function(){

    if($this->get->post('dopost')) // if do post click
    {
        $this->post->data['post_user_id']       = $this->auth->getIdentity('user_id');
        $this->post->data['post_title']         = $this->get->post('post_title');
        $this->post->data['post_content']       = $this->get->post('post_content');
        $this->post->data['post_tags']          = $this->get->post('post_tags');
        $this->post->data['post_status']        = $this->get->post('post_status');
        $this->post->data['post_creation_date'] = date('Y-m-d H:i:s');
        
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

	$this->view->get('create', function(){

		$this->set('title', 'Create New Post');
		$this->getScheme();
	});

});