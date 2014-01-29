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
    new Sess;
    new Auth;

    new Trigger('private','header');

    new Model('posts');
});

$c->func('index', function($id) use($c){

    $this->posts->func('delete', function() use($id) {
        $this->db->where('post_id', $id);
        return $this->db->delete('posts', $this);
    });

    if($this->posts->delete())  // save post
    {        
        $this->form->setNotice('Post deleted successfully.',SUCCESS); // set flash notice
        $this->url->redirect('/post/manage');
    } 
});