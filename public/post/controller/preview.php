<?php

/**
 * $c preview
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Db;
        new Date_Format;
        new Tag_Cloud;
        new Form;
        new Get;
        new View;
        new Sess;
        new Auth;
        new Hvc;
        new Trigger('private');
    }
);

$c->func(
    'index',
    function ($id) {

        $posts    = $this->hvc->get('private/posts/getone/{'.$id.'}');  // get one post
        $comments = $this->hvc->get('private/comments/getall/{'.$id.'}/1'); // get active post comments 

        $this->view->get(
            'preview',
            function () use ($posts, $comments) {
                $this->set('post', (object)$posts['results']);
                $this->set('comments', $comments['results']);
                $this->set('title', 'Preview');
                $this->getScheme();
            }
        );
    }
);

/* End of file preview.php */
/* Location: .public/home/controller/preview.php */