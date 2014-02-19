<?php

/**
 * $c manage
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

    new Trigger('private');
});

$c->func('index', function(){

    if($this->post->get('post_title'))
    {
        $this->db->like('post_title', $this->post->get('post_title'));
    }

    if($this->post->get('post_status'))
    {
        $this->db->like('post_status', $this->post->get('post_status'));
    }

    $this->db->get('posts');
    
    $posts = $this->db->getResult();

	$this->view->get('manage', function() use($posts) {
        
        $this->set('title', 'Manage Posts');
		$this->set('posts', $posts);
		$this->getScheme();
	});

});