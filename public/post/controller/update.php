<?php

/**
 * $c update
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;
    new View;
    new Sess;
    new Auth;
    new Post;

    new Trigger('private','header');

	new Model('posts');
});

$c->func('index', function($id){

    if($this->post->get('dopost')) // if do post click
    {
        $this->posts->data = array(
            'post_user_id'           => $this->auth->getIdentity('user_id'),
            'post_title'             => $this->post->get('post_title'),
            'post_content'           => $this->post->get('post_content'),
            'post_tags'              => $this->post->get('post_tags'),
            'post_status'            => $this->post->get('post_status'),
            'post_modification_date' => date('Y-m-d H:i:s'),
        );
        
        $this->posts->func('save', function() use($id) {
            if ($this->isValid()){

                $this->db->where('post_id', $id);
                $this->db->update('posts', $this);

                return true;
            }
            return false;
        });
        
        if($this->posts->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.',SUCCESS);
            $this->url->redirect('/post/update/index/'.$id);
        }
    } 


    $this->db->where('post_id', $id); // get db data
    $this->db->get('posts');
    $row = $this->db->getRow();

    if($row == false)
    {
        $this->response->show404();
    }

	$this->view->get('update', function() use($id, $row){

		$this->set('title', 'Update Post');
        $this->set('post_id', $id);
        $this->set('row', $row);

		$this->getScheme();
	});

});