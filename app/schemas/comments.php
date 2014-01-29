<?php 

$comments = array(
	'*' => array(),
	
	'comment_id' => array(
		'label' => 'Comment Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'comment_post_id' => array(
		'label' => 'Comment Post Id',
		'types' => '_not_null|_int(11)|_key(comment_post_id)(comment_post_id)|_foreign_key(comments_ibfk_1)(posts)(post_id)',
		'rules' => '',
		),
	'comment_name' => array(
		'label' => 'Name',
		'types' => '_not_null|_varchar(50)',
		'rules' => 'required|minLen(3)|maxLen(50)',
		),
	'comment_email' => array(
		'label' => 'Email',
		'types' => '_null|_varchar(255)',
		'rules' => 'required|validEmail',
		),
	'comment_website' => array(
		'label' => 'Website',
		'types' => '_null|_varchar(255)',
		'rules' => 'required',
		),
	'comment_body' => array(
		'label' => 'Comment',
		'types' => '_not_null|_text',
		'rules' => 'required|xssClean',
		),
	'comment_creation_date' => array(
		'label' => 'Comment Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'comment_modification_date' => array(
		'label' => 'Comment Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'comment_status' => array(
		'label' => 'Comment Status',
		'types' => '_not_null|_tinyint(1)|_default(0)',
		'rules' => '',
		),
);
 
/* End of file comments.php */
/* Location: .app/schemas/comments.php */