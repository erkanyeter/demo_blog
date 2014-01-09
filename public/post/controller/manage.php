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
    new Get;
});

$c->func('index', function() use($c){

    if($this->get->post('post_title'))
    {
        $this->db->like('post_title', $this->get->post('post_title'));
    }

    if($this->get->post('post_status'))
    {
        $this->db->like('post_status', $this->get->post('post_status'));
    }

    $this->db->get('posts');
    $posts = $this->db->result();

	$c->view('manage', function() use($posts) {
        
        $this->set('title', 'Manage Posts');
		$this->set('posts', $posts);
		$this->getScheme();
	});

});