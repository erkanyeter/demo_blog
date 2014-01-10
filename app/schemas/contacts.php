<?php 

$contacts = array(
	'*' => array(),
	
	'contact_id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_primary_key|_unsigned|_int(11)|_auto_increment|_unique_key(test)(test,contact_id)|_unique_key(test3)(test,contact_id)',
		'rules' => '',
		),
	'contact_name' => array(
		'label' => 'Contact Name',
		'types' => '_not_null|_unsigned|_int(10)',
		'rules' => '',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_not_null|_default(0)|_int(10)',
		'rules' => '',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_not_null|_varchar(255)|_default(\'trus\')',
		'rules' => '',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'types' => '_null|text',
		'rules' => '',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_null|_enum',
		'rules' => '',
		),
	'test' => array(
		'label' => 'Test',
		'types' => '_not_null|_int(11)',
		'rules' => '',
		),
	'test2' => array(
		'label' => 'Test2',
		'types' => '_null|_int(11)|_key(test34)(test2)|_unique_key(test4)(test2)',
		'rules' => '',
		),
	'test3' => array(
		'label' => 'Test3',
		'types' => '_not_null',
		'rules' => '',
		),
	'test6' => array(
		'label' => 'Test5',
		'types' => '_not_null|_varchar',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */