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

	new Model('post', 'posts');
});

$c->func('index', function(){

    new View;

    $this->db->join('posts', 'post_id = comment_post_id');  // Get all comments
    $this->db->orderBy('comment_status', 'ASC');
    $this->db->get('comments'); // reset query
    
    $result = $this->db->result();

	$this->view->get('approve', function() use($result){
        
		$this->set('title', 'Approve Comments');
        $this->set('comments', $result);
		$this->getScheme();
	});
});

$c->func('update', function($comment_id, $status = 'approve'){

    $update = ($status == 'approve') ? '1' : '0';
    $this->db->where('comment_id', $comment_id);
    $this->db->update('comments', array('comment_status' => $update));

    $this->url->redirect('/post/approve/');
});

$c->func('delete', function($comment_id){

    $this->db->where('comment_id', $comment_id);
    $this->db->delete('comments');
    $this->url->redirect('/post/approve/');
});