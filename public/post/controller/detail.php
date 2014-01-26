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
    new Get;
    new View;
    new Sess;
    new Auth;
    
    new Trigger('public','header');
    new Model('comment', 'comments');
});

$c->func('index', function($id){

    if($this->get->post('dopost')) // if do post click
    {
        $this->comment->data['comment_post_id']       = $this->get->post('comment_post_id');
        $this->comment->data['comment_name']          = $this->get->post('comment_name');
        $this->comment->data['comment_email']         = $this->get->post('comment_email');
        $this->comment->data['comment_website']       = $this->get->post('comment_website');
        $this->comment->data['comment_body']          = $this->get->post('comment_body');
        $this->comment->data['comment_creation_date'] = date('Y-m-d H:i:s');
        
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