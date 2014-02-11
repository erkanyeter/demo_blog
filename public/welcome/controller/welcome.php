<?php

/**
 * $c welcome
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new View;
});

$c->func('index', function(){

	new Setup_Wizard;

	// $this->setup_wizard->setExtension('pdo');
	// $this->setup_wizard->setExtension('mb_string');

	$this->setup_wizard->setDatabase('demo_blog','/var/www/demo_blog/db.sql');
	$this->setup_wizard->setTitle('Setup Wizard <u>Database Connection</u>');
	$this->setup_wizard->setInput('hostname','Hostname');
	$this->setup_wizard->setInput('username','Username');
	$this->setup_wizard->setInput('password','Password');
	$this->setup_wizard->run();


exit;


	// exit;

	// $this->setup_wizard->

    $this->view->get('welcome', function() {

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });
    
});

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */