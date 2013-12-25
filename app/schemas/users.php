<?php 

$users = array(
	'*' => array('colprefix' => 'user_'),
	
	'id' => array(
		'label' => 'User Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'username' => array(
		'label' => 'User Username',
		'types' => '_null|_varchar(50)',
		'rules' => '',
		),
	'email' => array(
		'label' => 'User Email',
		'types' => '_not_null|_varchar(60)',
		'rules' => '',
		),
	'password' => array(
		'label' => 'User Password',
		'types' => '_not_null|_varchar(75)',
		'rules' => '',
		),
	'creation_date' => array(
		'label' => 'User Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'modification_date' => array(
		'label' => 'User Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */