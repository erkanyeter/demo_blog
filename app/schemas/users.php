<?php 

$users = array(
	'*' => array(),
	
	'user_id' => array(
		'label' => 'User Id',
		'types' => '_primary_key|_int(11)|_auto_increment|_not_null',
		'rules' => '',
		),
	'user_email' => array(
		'label' => 'User Email',
		'types' => '_not_null|_varchar(60)',
		'rules' => 'required|validEmail',
		),
	'user_password' => array(
		'label' => 'User Password',
		'types' => '_not_null|_varchar(75)',
		'rules' => 'required|matches(confirm_password)',
		),
	'user_creation_date' => array(
		'label' => 'User Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'user_modification_date' => array(
		'label' => 'User Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'user_name' => array(
		'label' => 'User Name',
		'types' => '_not_null|_varchar(50)',
		'rules' => '',
		),
	'user_test' => array(
		'label' => 'User Test',
		'types' => '_null|_int(11)',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */