<?php

/**
 * $c create
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;

	new Model('post', 'posts');
});

$c->func('index', function($id) use($c){

	$c->view('update', function() use($id){

        if($this->get->post('dopost')) // if do post click
        {
            $this->post->user_id = $this->auth->getIdentity('user_id');
            $this->post->title   = $this->get->post('title');
            $this->post->content = $this->get->post('content');
            $this->post->tags    = $this->get->post('tags');
            $this->post->status  = $this->get->post('status');
            $this->post->modification_date = date('Y-m-d H:i:s');
            
            $this->post->func('save', function() use($id) {
                if ($this->isValid()){
                    $this->db->where('post_id', $id);
                    return $this->db->update('posts', $this);
                }
                return false;
            });
            
            if($this->post->save())  // save post
            {        
                $this->form->setNotice('Post saved successfully.',SUCCESS);
                $this->url->redirect('/post/update/index/3');
            }
        } 

        $this->db->where('post_id', $id); // get db data
        $this->db->get('posts');
    
		$this->set('title', 'Update Post');
        $this->set('post_id', $id);
        $this->set('row', $this->db->row());

		$this->getScheme();
	});

});