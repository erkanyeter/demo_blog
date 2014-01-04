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

    $this->db->where('post_id', $id);  // get post detail
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts'); // reset query
    $post = $this->db->row();

    if($post == false)
    {
        new Response();  // send 404 response 
        $this->response->show404();
    }

    // get comments
    $this->db->where('comment_post_id',$id);
    $this->db->where('comment_status', '1');
    $this->db->get('comments'); // reset query
    $comments = $this->db->result();

    $c->view('preview', function() use($post, $comments) {

        $this->set('post', $post);
        $this->set('comments', $comments);
        $this->set('title', 'Welcome to home');
        $this->getScheme();
    });
    
});

/* End of file preview.php */
/* Location: .public/home/controller/preview.php */