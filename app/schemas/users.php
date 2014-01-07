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
		'types' => '_varchar(60)|_not_null',
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
		'types' => '_datetime||_null',
		'rules' => '',
		),
	'test' => array(
		'label' => 'User Username',
		'_enum' => array(
			'Business and Finance',
			'Creative Services/Agency',
			'eCommerce',
			'Education and Training',
			'Entertainment and Events',
			'Health and Fitness',
			'Marketing and Advertising',
			'Politics',
			'Professional Services',
			'Real Estate',
			'Retail',
			'Social Networks and Online Communities',
			'Software and Web Ap',
			'Other, sdasd',
			'Market',
		),
		'types' => '_null|_enum',
		'rules' => 'required',
		),
	'username' => array(
		'label' => 'User Username',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|callback_username',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */