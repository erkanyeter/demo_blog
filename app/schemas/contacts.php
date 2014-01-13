<?php 

$contacts = array(
	'*' => array(),
	
	'contact_id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_int(11)|_auto_increment',
		'rules' => '',
		),
	'contact_name' => array(
		'label' => 'Contact Name',
		'types' => '_varchar(50)|_not_null',
		'rules' => 'required|maxLen(40)',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_varchar(50)|_not_null',
		'rules' => 'required|validEmail',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_not_null|_varchar(255)|_default(true)',
		'rules' => 'required|maxLen(160)',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'_enum' => array(
			'data',
			'ersin\'s',
			'bob, Store',
		),
		'types' => '_enum|_not_null',
		'rules' => 'required|xssClean',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_datetime|_null|_primary_key',
		'rules' => '',
		),
	'ersin' => array(
		'label' => 'Ersin',
		'types' => '_null|_int(11)',
		'rules' => '',
		),
	'test1' => array(
		'label' => 'Test1',
		'types' => '_not_null|_int(11)',
		'rules' => '',
		),
	'test4' => array(
		'label' => 'Test3',
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_not_null|_enum',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */