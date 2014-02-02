<?php

/**
 * $c detail
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Date_Format;
	new Tag_Cloud;
    new Form;
    new View;
    new Sess;
    new Auth;
    new Post;
    
    new Trigger('public','header');
    new Model('comment', 'comments');
});

$c->func('index', function($id){

    if($this->post->get('dopost')) // if do post click
    {
        $this->comment->data = array(
            'comment_post_id'       => $this->post->get('comment_post_id'),
            'comment_name'          => $this->post->get('comment_name'),
            'comment_email'         => $this->post->get('comment_email'),    
            'comment_website'       => $this->post->get('comment_website'),
            'comment_body'          => $this->post->get('comment_body'),
            'comment_creation_date' => date('Y-m-d H:i:s'),
        );

        $this->comment->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('comments', $this);
            }
            return false;
        });

        if($this->comment->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.');
            $this->url->redirect($this->uri->getRequestUri());
        }
    }

    $this->db->where('post_id', $id);     // get post detail
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');
    
    $post = $this->db->getRow();

    if($post == false)
    {
        $this->response->show404();  // set 404 response.
    }

    // get comments
    $this->db->where('comment_post_id',$id);
    $this->db->where('comment_status', 1);
    $this->db->get('comments');  // reset query
    
    $comments = $this->db->getResult();

    $this->view->get('detail', function() use($post, $comments) {

        $this->set('post', $post);
        $this->set('comments', $comments);
        $this->set('title', 'Details');
        $this->getScheme();
    });
    
});

/* End of file home.php */
/* Location: .public/home/controller/home.php */