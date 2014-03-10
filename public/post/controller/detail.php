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
    }
);

$c->func(
    'index.private_user',
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

        $post  = $this->hvc->get('private/posts/getone/'.$id.'/Published');
        $comments = $this->hvc->get('private/comments/getall/'.$id.'/1');

        $this->view->get(
            'detail',
            function () use ($post, $comments) {
                $this->set('post', (object)$post['results']);
                $this->set('comments', (object)$comments['results']);
                $this->set('title', 'Details');
                $this->getScheme();
            }
        );
    }
);

/* End of file detail.php */
/* Location: .public/post/controller/detail.php */
