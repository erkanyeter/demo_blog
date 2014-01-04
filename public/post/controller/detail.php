<?php

/**
 * $c detail
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

    if($this->get->post('dopost')) // if do post click
    {
        new Model('comment', 'comments');

        $this->comment->post_id       = $this->get->post('post_id');
        $this->comment->name          = $this->get->post('name');
        $this->comment->email         = $this->get->post('email');
        $this->comment->website       = $this->get->post('website');
        $this->comment->comment       = $this->get->post('comment');
        $this->comment->creation_date = date('Y-m-d H:i:s');
        
        $this->comment->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('comments', $this);
            }
            return false;
        });

        if($this->comment->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.');
            $this->url->redirect($this->uri->requestUri());
        }
    }

    // get post detail
    $this->db->where('post_id', $id);
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');
    $post = $this->db->row();

    if($post == false)
    {
        new Response;
        $this->response->show404();  // set 404 response.
    }

    // get comments
    $this->db->where('comment_post_id',$id);
    $this->db->where('comment_status', '1');
    $this->db->get('comments'); // reset query
    $comments = $this->db->result();

    $c->view('detail', function() use($post, $comments) {

        $this->set('post', $post);
        $this->set('comments', $comments);
        $this->set('title', 'Details');
        $this->getScheme();
    });
    
});

/* End of file home.php */
/* Location: .public/home/controller/home.php */