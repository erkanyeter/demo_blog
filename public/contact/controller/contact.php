<?php

/**
 * $c contact
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
    
    new Trigger('public','header');
    
	new Model('contact', 'contacts');
});

$c->func('index', function(){

    if($this->post->get('dopost')) // if do post click
    {
        $this->contact->data = array(
            'contact_name'          => $this->post->get('contact_name'),
            'contact_email'         => $this->post->get('contact_email'),
            'contact_subject'       => $this->post->get('contact_subject'),
            'contact_body'          => $this->post->get('contact_body'),
            'contact_creation_date' => date('Y-m-d H:i:s'),
        );

        $this->contact->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('contacts', $this);
            }
            return false;
        });

        if($this->contact->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.', SUCCESS);
            $this->url->redirect('/contact');
        }
    }

	$this->view->get('contact', function(){

		$this->set('title', 'Contact');
		$this->getScheme();
	});

});