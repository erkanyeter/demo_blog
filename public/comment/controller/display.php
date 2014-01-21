<?php

/**
 * $c approve
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;
    new Date_Get;
    new View;
    new Culture;

	new Model('post', 'posts');
});

$c->func('index', function(){

    $this->db->join('posts', 'post_id = comment_post_id');  // Get all comments
    $this->db->orderBy('comment_status', 'ASC');
    $this->db->get('comments'); // reset query
    
    $result = $this->db->getResult();

	$this->view->get('display', function() use($result){
        
		$this->set('title', 'Display Comments');
        $this->set('comments', $result);
		$this->getScheme();
	});
});