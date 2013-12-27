<?php 

$comments = array(
	'*' => array('colprefix' => 'comment_'),
	
	'id' => array(
		'label' => 'Comment Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'post_id' => array(
		'label' => 'Comment Post Id',
		'types' => '_not_null|_int(11)|_foreign_key(posts)(post_id)|_key(comment_post_id)(comment_post_id)',
		'rules' => '',
		),
	'name' => array(
		'label' => 'Name',
		'types' => '_not_null|_varchar(50)',
		'rules' => 'required|minLen(3)|maxLen(50)',
		),
	'email' => array(
		'label' => 'Email',
		'types' => '_null|_varchar(255)',
		'rules' => 'required|validEmail',
		),
	'website' => array(
		'label' => 'Website',
		'types' => '_null|_varchar(255)',
		'rules' => 'required',
		),
	'comment' => array(
		'label' => 'Comment',
		'types' => '_not_null|_text',
		'rules' => 'required|xssClean',
		),
	'creation_date' => array(
		'label' => 'Comment Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'modification_date' => array(
		'label' => 'Comment Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'status' => array(
		'label' => 'Comment Status',
		'types' => '_null|_default("0")|_enum',
		'_enum' => array(
			'0',
			'1',
		),
		'rules' => '',
		),
);
 
/* End of file comments.php */
/* Location: .app/schemas/comments.php */