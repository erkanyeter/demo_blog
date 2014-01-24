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

	new Model('post', 'posts');
});

$c->func('index', function($id){

    if($this->get->post('dopost')) // if do post click
    {
        $this->post->data['post_user_id']           = $this->auth->getIdentity('user_id');
        $this->post->data['post_title']             = $this->get->post('post_title');
        $this->post->data['post_content']           = $this->get->post('post_content');
        $this->post->data['post_tags']              = $this->get->post('post_tags');
        $this->post->data['post_status']            = $this->get->post('post_status');
        $this->post->data['post_modification_date'] = date('Y-m-d H:i:s');
        
        $this->post->func('save', function() use($id) {
            if ($this->isValid()){

                $this->db->where('post_id', $id);
                $this->db->update('posts', $this);

                return true;
            }
            return false;
        });
        
        if($this->post->save())  // save post
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