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

$c->func('index', function($id) use($c){

    $this->post->func('delete', function() use($id) {
        $this->db->where('post_id', $id);
        return $this->db->delete('posts', $this);
    });

    if($this->post->delete())  // save post
    {        
        $this->form->setNotice('Post deleted successfully.',SUCCESS); // set flash notice
        $this->url->redirect('/post/manage');
    } 
});