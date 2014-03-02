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
        new Sess;
        new Db;
        new Auth;
        new Hvc;
        new Trigger('public'); // run triggers
    }
);

$c->func(
    'index',
    function () {

        if ($this->post->get('dopost')) {  // login button is submit ?
            
            $this->form->setRules('email', 'Email', 'required|validEmail');
            $this->form->setRules('password', 'Password', 'required');

            $email    = $this->post->get('email');
            $password = $this->post->get('password');

            if ($this->form->isValid()) { // form is valid ?
            
                $row = $this->auth->query(
                    $password,
                    function () use ($email) {
                        
                        $this->db->prep();
                        $this->db->select('user_id, user_username, user_password, user_email');
                        $this->db->where('user_email', ':user_email');
                        $this->db->get('users');
                        $this->db->bindParam(':user_email', $email, PARAM_STR, 60); // String (int Length),
                        $this->db->exec();
                        $row = $this->db->getRow();

                        if ($row !== false) {       // Set password for verify 
                            $this->setPassword($row->user_password); 
                        }
                        return $row;   // return to database row
                    }
                );

                if ($row !== false) {      // Authorize to user
                    $this->auth->authorize(
                        function () use ($row) {    
                            $this->setIdentity('user_username', $row->user_username); // Set user data to auth container
                            $this->setIdentity('user_email', $row->user_email);
                            $this->setIdentity('user_id', $row->user_id);
                        }
                    );
                    $this->url->redirect('/home'); // Success redirect
                }

                $this->form->setNotice('Wrong username / password combination.', ERROR);
                $this->url->redirect('/login');
            }
        }

        $this->view->get(
            'login',
            function () {
                $this->set('title', 'Login to My blog');
                $this->getScheme();
            }
        );
    }
);
