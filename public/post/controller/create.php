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
    new Sess;
    new Auth;
    new Post;

    new Trigger('private','header');
	new Model('posts');
});

$c->func('index', function(){

    if($this->post->get('dopost')) // if do post click
    {
        $this->posts->data = array(
            'post_user_id'       => $this->auth->getIdentity('user_id'),
            'post_title'         => $this->post->get('post_title'),
            'post_content'       => $this->post->get('post_content'),
            'post_tags'          => $this->post->get('post_tags'),
            'post_status'        => $this->post->get('post_status'),
            'post_creation_date' => date('Y-m-d H:i:s'),
        );

        $this->posts->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('posts', $this);
            }
            return false;
        });

        if($this->posts->save())  // save post
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