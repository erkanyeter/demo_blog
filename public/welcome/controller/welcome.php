<?php

/**
 * $c welcome
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Html;
        new View;
<<<<<<< HEAD

=======
>>>>>>> bb3a7dd6f6008cfe7b2909e673820f4c00960f7f
    }
);

$c->func(
    'index',
    function () {

<<<<<<< HEAD
=======
        echo md5(rand(time()));
>>>>>>> bb3a7dd6f6008cfe7b2909e673820f4c00960f7f

        $this->view->get(
            'welcome', 
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );
    }
);

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */