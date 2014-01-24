<?php

/**
 * $c test.create
 * 
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

$c->func('index', function(){

    $this->db->select("*, IFNULL((SELECT count(*) FROM comments 
        WHERE posts.post_id = comment_post_id AND comment_status = '1' 
        GROUP BY posts.post_id LIMIT 1),0) as total_comment", false);
    
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');

    $posts = $this->db->getResultArray();

    print_r($posts);

});

/* End of file home.php */
/* Location: .public/home/controller/home.php */