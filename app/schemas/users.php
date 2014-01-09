<?php 

$users = array(
	'*' => array(),
	
	'user_id' => array(
		'label' => 'User Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'user_email' => array(
		'label' => 'User Email',
		'types' => '_varchar(60)|_null',
		'rules' => 'required|validEmail',
		),
	'user_password' => array(
		'label' => 'User Password',
		'types' => '_not_null|_varchar(75)',
		'rules' => 'required|minLen(6)',
		),
	'user_creation_date' => array(
		'label' => 'User Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'user_modification_date' => array(
		'label' => 'User Modification Date',
		'types' => '_datetime|_null',
		'rules' => '',
		),
<<<<<<< HEAD
	'username' => array(
		'label' => 'User Username',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|callback_username',
=======
	'user_username' => array(
		'label' => 'User Username',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|callback_username',
		),
	'user_test' => array(
		'label' => 'User Test',
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
		'rules' => '',
>>>>>>> 14bf39df8a101530a20fd6d26cfe3645e03ca64a
		),
	'name' => array(
		'label' => 'Name',
		'types' => '_null|_varbinary(50)',
		'rules' => '',
		),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */