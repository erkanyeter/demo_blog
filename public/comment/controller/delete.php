<?php

/**
 * $c approve
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
});

$c->func('index', function($comment_id){

    $this->db->where('comment_id', $comment_id);
    $this->db->delete('comments');

    $this->url->redirect('/comment/display');

});