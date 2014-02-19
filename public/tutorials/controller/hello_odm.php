<?php

/**
 * $c hello_odm
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

    new Url;
    new Html;
    new View;
    new Post;

    new Model('user', 'users');
});

$c->func('index', function(){

    if($this->post->get('dopost'))
    {
        $this->user->data['user_email']    = $this->post->get('user_email');
        $this->user->data['user_password'] = $this->post->get('user_password');

        //--------------------- set non schema rules
        
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
        $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');
        
        //---------------------
        
        $this->user->func('save', function() {
            if ($this->isValid()){
                $this->data['user_password'] = md5($this->getValue('user_password'));  // get secure value
                return $this->db->insert('users', $this);
            }
            return false;
        });

        if($this->user->save())
        {   
            $this->form->setNotice('User saved successfully.',SUCCESS);
            $this->url->redirect('tutorials/hello_odm');
        }
    }

    $this->view->get('hello_odm',function() {

        $this->set('name', 'Obullo');
        $this->set('footer', $this->getTpl('footer', false));
    });

});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */