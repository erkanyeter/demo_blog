<?php

/**
 * $c home
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    //------------- Installation Wizard ------------------

    new Post;

    if($this->post->get('submit_step1') OR $this->post->get('submit_step2'))
    {
        new Setup_Wizard;

        $this->setup_wizard->setCssFile('/assets/css/setup_wizard.css');
        $this->setup_wizard->setDatabaseConfigFile('/app/config/debug/database.php');
        $this->setup_wizard->setDatabasePath('/var/www/demo_blog/db.sql');
        $this->setup_wizard->setDatabaseTemplate('/app/templates/database.tpl');
        $this->setup_wizard->setDatabaseDriver('Pdo_Mysql');

        $this->setup_wizard->setTitle('Demo_Blog Setup Wizard - Database Connection');
        $this->setup_wizard->setSubTitle('Configuration');

        $this->setup_wizard->setInput('hostname','Hostname','required');
        $this->setup_wizard->setInput('username','Username','required');
        $this->setup_wizard->setInput('password','Password','required', '', ' id="password" ');
        $this->setup_wizard->setInput('sql_path','Sql Path', '', $this->setup_wizard->getDatabasePath(), ' disabled ');
        
        $this->setup_wizard->setDatabaseItem('hostname', $this->post->get('hostname'));
        $this->setup_wizard->setDatabaseItem('username', $this->post->get('username'));
        $this->setup_wizard->setDatabaseItem('password', $this->post->get('password'));
        $this->setup_wizard->setDatabaseItem('database', 'demo_blog');

        $this->setup_wizard->setNote('* Configure your database connection settings then click to install.');   
        $this->setup_wizard->setIni('installed', 1);
        $this->setup_wizard->setRedirectUrl('/home');
        $this->setup_wizard->run();
    } 
    else 
    {
        new Setup_Wizard;

        $this->setup_wizard->setCssFile('/assets/css/setup_wizard.css');
        $this->setup_wizard->setTitle('Demo_Blog Setup Wizard - Requirements');
        $this->setup_wizard->setExtension(array('pdo', 'mcrypt'));
        $this->setup_wizard->setNote('* Please install above the requirements then click next. "Otherwise application will not work correctly."');
        $this->setup_wizard->run();
    }

    //--------------------------------------------

	new Url;
	new Html;
	new Db;
	new Date_Format;
	new Tag_Cloud;
    new View;
    new Sess;
    new Auth;

    new Trigger('public'); // run triggers
});

$c->func('index', function(){

    $this->db->select("*, IFNULL((SELECT count(*) FROM comments 
        WHERE posts.post_id = comment_post_id AND comment_status = 1 
        GROUP BY posts.post_id LIMIT 1),0) as total_comment", false);
    
    $this->db->where('post_status', 'Published');
    $this->db->join('users', 'user_id = post_user_id');
    $this->db->get('posts');

    $posts = $this->db->getResultArray();

    $this->view->get('home', function() use($posts) {

        $this->set('title', 'Welcome to home');
        $this->set('posts', $posts);
        $this->getScheme();
    });

});

/* End of file home.php */
/* Location: .public/home/controller/home.php */