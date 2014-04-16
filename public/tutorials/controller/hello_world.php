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

        $this->logger->notice('test', array('username' => 'test'));
        $this->logger->push('mongo');
        $this->logger->notice('ehhhehe', array('username' => 'ersiasdasşd_ A_SP*,2.işeç.çöö,şm, ğo ld*-qlçşçdğçdi çşsçd.ç. ğğşğoöçö'));

        // syslog(LOG_NOTICE, 'test');
        // syslog(LOG_EMERG, 'site down !!');

        // $this->hvc->get('private/comments/getuser', array('user_id' => 5), $expiration = 7200);

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