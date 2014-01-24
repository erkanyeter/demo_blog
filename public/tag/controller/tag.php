<?php

/**
 * $c tag
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Db;
	new Date_Format;
	new Tag_Cloud;
    new View;
});

$c->func('index', function($tag){

    $this->db->like('post_tags', $tag);
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');

    $posts = $this->db->getResultArray();

    $this->view->get('tag', function() use($posts) {
	   
        $this->set('title', 'Welcome to home');
        $this->set('posts', $posts);
        $this->getScheme(); 
    });
    
});

/* End of file tag.php */
/* Location: .public/tag/controller/tag.php */