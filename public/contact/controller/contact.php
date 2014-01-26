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
    new Get;
    new View;
    new Sess;
    new Auth;

    new Trigger('public','header');
	new Model('contact', 'contacts');
});

$c->func('index', function(){


    if($this->get->post('dopost')) // if do post click
    {
        $this->contact->data['contact_name']          = $this->get->post('contact_name');
        $this->contact->data['contact_email']         = $this->get->post('contact_email');
        $this->contact->data['contact_subject']       = $this->get->post('contact_subject');
        $this->contact->data['contact_body']          = $this->get->post('contact_body');
        $this->contact->data['contact_creation_date'] = date('Y-m-d H:i:s');
        
        $this->contact->func('save', function() {
            if ($this->isValid()){
                return $this->db->insert('contacts', $this);
            }
            return false;
        });

        if($this->contact->save())  // save post
        {        
            $this->form->setNotice('Post saved successfully.', 'success');
            $this->url->redirect('/contact');
        }
    }

	$this->view->get('contact', function(){

		$this->set('title', 'Contact');
		$this->getScheme();
	});

});