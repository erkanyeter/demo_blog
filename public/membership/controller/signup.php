<?php

/**
 * $c signup
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Form;
        new View;
        new Sess;
        new Auth;
        new Post;
        new Hvc;
    }
);

$c->func(
    'index.public_user',
    function () {
        if ($this->post->get('dopost')) {  // if isset post submit
            
            $this->form->setRules('user_username', 'Username', 'required|callback_username');
            $this->form->setRules('user_email', 'Email', 'required|validEmail');
            $this->form->setRules('user_password', 'Password', 'required|minLen(6)');
            $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(user_password)');
            $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');
            
            $this->form->func(
                'callback_username',
                function () {
                    $r = $this->hvc->post('private/users/getcount');

                    if ($r['results']['count'] > 0) {   // unique control
                        $this->form->setMessage('callback_username', 'The username is already taken by another member');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->form->isValid()) {  // run validation

                $r = $this->hvc->post(
                    'private/users/create',
                    array('user_password' => $this->auth->hashPassword($this->post->get('user_password'), 8))
                );

                if ($r['success']) {
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect('/login');
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }
        
        $this->view->get(
            'signup',
            function () {
                $this->set('title', 'Signup to my blog');
                $this->getScheme();
            }
        );
    }
);