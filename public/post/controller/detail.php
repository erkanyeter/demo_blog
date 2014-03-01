<?php

/**
 * $c detail
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Date_Format;
        new Tag_Cloud;
        new Form;
        new View;
        new Sess;
        new Auth;
        new Post;
        new Hvc;
        new Trigger('public');
    }
);

$c->func(
    'index',
    function ($id) {
        if ($this->post->get('dopost')) {  // if we have submit

            $this->form->setRules('comment_name', 'Name', 'required');
            $this->form->setRules('comment_email', 'Email', 'required|validEmail');
            $this->form->setRules('comment_website', 'Website', 'required');
            $this->form->setRules('comment_body', 'Commend', 'required|xssClean');

            if ($this->form->isValid()) {
                
                $r = $this->hvc->post('private/comments/create/');

                if ($r['success']) {  // save comment
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect($this->uri->getRequestUri());
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }

        $r  = $this->hvc->get('private/posts/getone/{'.$id.'}/Published');
        $rc = $this->hvc->get('private/comments/getall/{'.$id.'}/1');

        $this->view->get(
            'detail',
            function () use ($r, $rc) {
                $this->set('post', (object)$r['results']);
                $this->set('comments', (object)$rc['results']);
                $this->set('title', 'Details');
                $this->getScheme();
            }
        );
    }
);

/* End of file detail.php */
/* Location: .public/post/controller/detail.php */
