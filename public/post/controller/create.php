<?php

/**
 * $c create
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Form;
        new View;
        new Post;
        new Hvc;
    }
);

$c->func(
    'index.Private_User',
    function () {
        if ($this->post->get('dopost')) {

            $this->form->setRules('post_title', 'Title', 'required');
            $this->form->setRules('post_content', 'Content', 'required|xssClean');
            $this->form->setRules('post_status', 'Status', 'required');

            if ($this->form->isValid()) {  // create new post if form is valid

                $r = $this->hvc->post('private/posts/create', array('user_id' => $this->auth->getIdentity('user_id')));

                if ($r['success']) {
                    $this->form->setNotice($r['message'], SUCCESS);  // set flash notice using sess
                    $this->url->redirect('/post/manage');
                } else {
                    $this->form->setMessage($r['message']);  // set your message to form to object
                }
            }
        }

        $this->view->get(
            'create',
            function () {
                $this->set('title', 'Create New Post');
                $this->getScheme();
            }
        );
    }
);