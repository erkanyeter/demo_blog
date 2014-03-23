<?php

/**
 * $c hello_hmvc
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['View'];
        $c['Html'];
        $c['Hvc'];
        $c['Url'];
    }
);
$app->func(
    'index',
    function () {

        $ra = $this->hvc->get('tutorials/hello_dummy/1/2/3');
        $rb = $this->hvc->get('tutorials/hello_dummy/4/5/6');

        $this->view->get(
            'hello_hvc', 
            function () use ($ra, $rb) {

                $this->set('response_a', $ra);
                $this->set('response_b', $rb);
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );
    }
);

/* End of file hello_hvc.php */
/* Location: .public/tutorials/controller/hello_hvc.php */