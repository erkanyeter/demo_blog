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
    new Uform;
});

$c->func('index', function() use($c){  
    /*
    if($this->get->post('dopost'))
    {
        $this->user->data['email']    = $this->get->post('email');
        $this->user->data['password'] = $this->get->post('password');
        //--------------------- set non schema rules
        
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
        
        //---------------------
        $this->user->func('save', function() {
            if ($this->isValid()){
                $this->data['password'] = md5($this->getValue('password'));
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
    */
    $this->uform->open('/tutorials/hello_uform', array('method' => 'post'), function() {

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('user_email', $this->setValue('user_email')),
        ));
        /*

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Password',
            'rules' => 'required|minLen(6)',
            'input' => $this->input('user_password', $this->setValue('user_password')),
        ) );

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Confirm',
            'rules' => 'required|matches(user_password)',
            'input' => $this->input('confirm_password', $this->setValue('confirm_password'), ' id="confirm" ' ),
        ));
*/
        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('dopost', 'Do Post', ' id="dopost" '),
        ));
    });
    $this->uform->close();

    if($this->get->post('dopost'))
    {
        $this->uform->isValid();
        $this->uform->render();
    }

    $c->view('hello_uform', function() use($c) {

        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));
    });

});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */