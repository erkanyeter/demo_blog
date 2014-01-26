<?php

/**
 * $c update
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Sess;
	new Auth;

	new Trigger('private','header');
});

$c->func('index', function($comment_id, $status = 'approve'){

    $update = ($status == 'approve') ? 1 : 0;

    $this->db->where('comment_id', $comment_id);
    $this->db->update('comments', array('comment_status' => $update));

    $this->url->redirect('/comment/display');
});