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
	new Date_Get;
	new Tag_Cloud;
});

$c->func('index', function($tag) use($c){

    $c->view('tag', function() use($c, $tag) {
	   
        $this->db->like('post_tags', $tag);
    	$this->db->where('post_status', 'Published');
    	$this->db->join('users', 'user_id = post_user_id');
    	$this->db->get('posts');

        $this->set('title', 'Welcome to home');
        $this->set('posts', $this->db->resultArray());
        $this->getScheme();
        
    });
    
});

/* End of file home.php */
/* Location: .public/home/controller/home.php */