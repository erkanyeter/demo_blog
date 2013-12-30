<?php

/**
 * $c manage
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new Form;
    new Get;

    if( ! $this->auth->hasIdentity())
    {
        $this->url->redirect('/login');
    }
});

$c->func('index', function() use($c){

	$c->view('manage', function(){

        if($this->get->post('title'))
        {
            $this->db->like('post_title', $this->get->post('title'));
        }

        if($this->get->post('status'))
        {
            $this->db->like('post_status', $this->get->post('status'));
        }

        $this->db->get('posts');

        $this->set('title', 'Manage Posts');
		$this->set('posts', $this->db->result());

		$this->getScheme();
	});

});