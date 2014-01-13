<?php 

$contacts = array(
	'*' => array(),
	
	'contact_id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_int(11)|_auto_increment|_unique_key(test)(test,contact_id)|_unique_key(id)(contact_id)|_primary_key(contact_id,contact_name,contact_creation_date,contact_body,contact_subject,contact_email)',
		'rules' => '',
		),
	'contact_name' => array(
		'label' => 'Contact Name',
		'types' => '_varchar(50)|_primary_key(contact_id,contact_name,contact_creation_date,contact_body,contact_subject,contact_email)|_null',
		'rules' => 'required',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_varchar(50)|_primary_key(contact_id,contact_name,contact_creation_date,contact_body,contact_subject,contact_email)|_not_null',
		'rules' => '',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_not_null|_varchar(255)|_default(true)',
		'rules' => '',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'_enum' => array(
			'data',
			'ersin\'s',
			'bob, Store',
		),
		'types' => '_enum|_primary_key(contact_id,contact_name,contact_creation_date,contact_body,contact_subject,contact_email)|_not_null',
		'rules' => '',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_enum|_primary_key(contact_id,contact_name,contact_creation_date,contact_body,contact_subject,contact_email)|_not_null',
		'rules' => '',
		),
	'test1' => array(
		'label' => 'Test',
		'types' => '_not_null|_int(11)|_unique_key(test)(test,contact_id)',
		'rules' => '',
		),
	'test3' => array(
		'label' => 'Test3',
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_not_null|_enum|_primary_key',
		'rules' => '',
		),
	'ersin' => array(
		'label' => 'Ersin',
		'types' => '_null|_int(11)',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */