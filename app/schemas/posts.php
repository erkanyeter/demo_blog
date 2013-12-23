<?php 

$posts = array(
	'*' => array('colprefix' => 'post_'),
	
	'id' => array(
		'label' => 'Post Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'user_id' => array(
		'label' => 'Post User Id',
		'types' => '_null|_int(11)|_foreign_key(users)(user_id)|_key(post_user_id)(post_user_id)',
		'rules' => '',
		),
	'title' => array(
		'label' => 'Post Title',
		'types' => '_not_null|_varchar(50)',
		'rules' => '',
		),
	'content' => array(
		'label' => 'Post Content',
		'types' => '_not_null|_varchar(50)',
		'rules' => '',
		),
	'tags' => array(
		'label' => 'Post Tags',
		'types' => '_null|_varchar(255)',
		'rules' => '',
		),
	'status' => array(
		'label' => 'Post Status',
		'types' => '_not_null|_enum',
		'_enum' => array(
			'draft',
			'published',
			'archived',
		),
		'rules' => '',
		),
	'creation_date' => array(
		'label' => 'Post Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'modification_date' => array(
		'label' => 'Post Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file posts.php */
/* Location: .app/schemas/posts.php */