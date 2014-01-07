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

	new Model('contact', 'contacts');
});

$c->func('index', function() use($c){

    if($this->get->post('dopost')) // if do post click
    {
        $this->contact->data['name']          = $this->get->post('name');
        $this->contact->data['email']         = $this->get->post('email');
        $this->contact->data['subject']       = $this->get->post('subject');
        $this->contact->data['body']          = $this->get->post('body');
        $this->contact->data['creation_date'] = date('Y-m-d H:i:s');
        
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

	$c->view('contact', function(){

		$this->set('title', 'Contact');
		$this->getScheme();
	});

});