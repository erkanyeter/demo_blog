<?php

/**
 * $c preview
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Db;
	new Date_Get;
	new Tag_Cloud;
    new Form;
    new Get;
});

$c->func('index', function($id) use($c){

    $c->view('preview', function() use($c, $id) {

        // get post detail
        $this->db->where('post_id', $id);
    	$this->db->join('users', 'user_id = post_user_id');
    	$this->db->get('posts'); // reset query

        $this->set('post', $this->db->row());

        // get comments
        $this->db->where('comment_post_id',$id);
        $this->db->where('comment_status', '1');
        $this->db->get('comments'); // reset query

        $this->set('comments', $this->db->result());
        $this->set('title', 'Welcome to home');

        $this->getScheme();
        
    });
    
});

/* End of file preview.php */
/* Location: .public/home/controller/preview.php */