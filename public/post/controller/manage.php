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

    if( ! $this->auth->hasIdentity())
    {
        $this->url->redirect('/login');
    }

});

$c->func('index', function() use($c){

	$c->view('manage', function(){

        $this->db->get('posts');

        $this->set('title', 'Manage Posts');
		$this->set('posts', $this->db->result());
		$this->getScheme();
	});

});