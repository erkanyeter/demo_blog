<?php 

$users = array(
	'*' => array('colprefix' => 'user_'),
	
	'id' => array(
		'label' => 'User Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'email' => array(
		'label' => 'User Email',
		'types' => '_varchar(60)|_null',
		'rules' => 'required|validEmail',
		),
	'password' => array(
		'label' => 'User Password',
		'types' => '_not_null|_varchar(75)',
		'rules' => 'required|minLen(6)',
		),
	'creation_date' => array(
		'label' => 'User Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'modification_date' => array(
		'label' => 'User Modification Date',
		'types' => '_datetime|_null',
		'rules' => '',
		),
	'username' => array(
		'label' => 'User Username',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|callback_username',
		),
	'name' => array(
		'label' => 'Name',
		'types' => '_null|_varbinary(50)',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */