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

        $this->setup_wizard->setDatabase('demo_blog','/var/www/demo_blog/db.sql');
        $this->setup_wizard->setTitle('Setup Wizard <u>Database Connection</u>');
        $this->setup_wizard->setInput('hostname','Hostname','required');
        $this->setup_wizard->setInput('username','Username','required');
        $this->setup_wizard->setInput('password','Password','required');
        $this->setup_wizard->setIni('demo_blog', 'installed', 1);
        $this->setup_wizard->setRedirectUrl('/home');
        $this->setup_wizard->run();
    } 
    else 
    {
        new Setup_Wizard;

        $this->setup_wizard->setTitle('Welcome to Demo_Blog Setup Wizard - ( Requirements )');
        $this->setup_wizard->setExtension('pdo');
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

    new Trigger('public','header'); // run triggers

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