<?php

/**
 * $c login
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Form;
        new Post;
        new View;
        new Hvc;
    }
);

$c->func(
    'index.Public_User',
    function () {

        if ($this->post->get('dopost')) {  // login button is submit ?
            
            $this->form->setRules('email', 'Email', 'required|validEmail');
            $this->form->setRules('password', 'Password', 'required');

            if ($this->form->isValid()) {  // form is valid ?

                $r = $this->hvc->get('private/auth.service/query');

                if ($r['success']) {      // Authorize to user
                    $this->auth->authorize();
                    $this->auth->setIdentity(
                        array(
                        'user_username' => $r['results']['user_username'],  // Set user data to auth container
                        'user_email'    => $r['results']['user_email'],
                        'user_id'       => $r['results']['user_id'],
                        )
                    );
                    $this->form->setNotice('Welcome to my blog !', SUCCESS);
                    $this->url->redirect('/home'); // Success redirect
                }
                $this->form->setMessage($r['message']);  // Set error message
            }

        }   // end do post.

        $this->view->get(
            'login',
            function () {
                $this->set('title', 'Login to My blog');
                $this->getScheme();
            }
        );
    }
);
