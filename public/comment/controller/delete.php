<?php

/**
 * $c approve
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Sess;
	new Auth;

	new Trigger('private','header');
});

$c->func('index', function($comment_id){

    $this->db->where('comment_id', $comment_id);
    $this->db->delete('comments');

    $this->url->redirect('/comment/display');

});