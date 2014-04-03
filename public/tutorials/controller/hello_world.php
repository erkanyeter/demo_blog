<?php

/**
 * $c hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['view'];
      
        $c['translator']->load('email');

        echo $this->translator[i18n_Error_Email::INVALID_ADDRESS];

        // var_dump($this->translator->sprintf(i18n_Errors_Email::INVALID_ADDRESS, 'me@me.com'));
    }
);

$app->func(
    'index',  // visitor.guest
    function () {

        $this->view->get(
            'hello_world', 
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );      
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */