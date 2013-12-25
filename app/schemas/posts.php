<?php 

$posts = array(
	'*' => array('colprefix' => 'post_'),
	
	'id' => array(
		'label' => 'Post Id',
		'types' => '_primary_key|_int(11)|_auto_increment|_not_null',
		'rules' => '',
		),
	'user_id' => array(
		'label' => 'Post User Id',
		'types' => '_int(11)|_foreign_key(users)(user_id)|_key(post_user_id)(post_user_id)|_null',
		'rules' => '',
		),
	'title' => array(
		'label' => 'Post Title',
		'types' => '_varchar(50)|_not_null',
		'rules' => '',
		),
	'content' => array(
		'label' => 'Post Content',
		'types' => '_varchar(60)|_not_null',
		'rules' => '',
		),
	'tags' => array(
		'label' => 'Post Tags',
		'types' => '_varchar(256)|_null',
		'rules' => '',
		),
	'status' => array(
		'label' => 'Post Status',
		'_enum' => array(
			'Draft',
			'Published',
			'Archived',
		),
		'types' => '_not_null|_enum',
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