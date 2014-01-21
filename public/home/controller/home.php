<?php

/**
 * $c home
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
    // new Query; // query hmvc kullanmalı. web request gibi
    // new Restql;
    new Rest;
});

$c->func('index', function(){

    $this->rest->get('getone.user');

    // $this->rest->put(''); upload file, write file

    $this->rest->post('save.user', array(
        'user_username' => 'test',
        'user_email' => 'eguvenc@gmail.com',
    ));

    // $this->rest->post('delete.user', array('user_id' => 1));

    $this->db->select("*, IFNULL((SELECT count(*) FROM comments 
        WHERE posts.post_id = comment_post_id AND comment_status = '1' 
        GROUP BY posts.post_id LIMIT 1),0) as total_comment", false);
    
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');

    $posts = $this->db->getResultArray();

    $this->view->get('home', function() use($posts) {

        $this->set('title', 'Welcome to home');
        $this->set('posts', $posts);
        $this->getScheme();
    });

});

/* End of file home.php */
/* Location: .public/home/controller/home.php */