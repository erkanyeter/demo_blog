<?php

/**
 * $c hello_odm
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Model('user', 'users');
    new Get;            
    new Url;
    new Html;
});

$c->func('index', function() use($c){  

    if($this->get->post('dopost'))
    {
        $this->user->setPrefix('user_');
        
        $this->user->data['email']    = $this->get->post('email');
        $this->user->data['password'] = $this->get->post('password');

        //--------------------- set non schema rules
        
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
        
        //---------------------
        
        $this->user->func('save', function() {
            if ($this->isValid()){
                $this->password = md5($this->getValue('password'));
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

    $c->view('hello_odm', function() use($c) {

        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));
    });

});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */