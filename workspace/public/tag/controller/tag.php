<?php

/**
 * $c tag
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new Date_Format;
        new Tag_Cloud;
        new View;
        new Hvc;
    }
);

$c->func(
    'index.Public_User',
    function ($tag) {

        $r = $this->hvc->get('private/posts/getallbytag/'.$tag);

        $this->view->get(
            'tag',
            function () use ($r) {
                $this->set('title', 'Tagged Posts');
                $this->set('posts', $r['results']);
                $this->getScheme(); 
            }
        );
    }
);

/* End of file tag.php */
/* Location: .public/tag/controller/tag.php */