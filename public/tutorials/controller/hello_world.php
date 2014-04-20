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
        
        // $obj = new i18n_Form_Error;

        // $this->logger->debug = true;
        
        $this->logger->load(LOGGER_SYSLOG);

        $this->logger->notice('test', array('username' => 'testssssssssssssssssssssssss'));
        $this->logger->push(LOGGER_SYSLOG, LOG_DEBUG);

        $this->logger->info('ehhhehe', array('username' => 'ersiasdasşd_ A_SP*,2.işeç.çöö,şm, ğo ld*-qlçşçdğçdi çşsçd.ç. ğğşğoöçö'));

        // syslog(LOG_NOTICE, 'test');
        // syslog(LOG_EMERG, 'site down !!');

        // $this->hvc->get('private/comments/getuser', array('user_id' => 5), $expiration = 7200);

    }
);

$app->func(
    'index', 
    function () {


        $this->view->load(
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