<?php 

$contacts = array(
	'*' => array(),
	
	'contact_id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_auto_increment|_primary_key|_int(11)',
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
		'types' => '_not_null|_enum',
		'rules' => 'required|xssClean',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_not_null|_enum',
		'rules' => '',
		),
	'burak' => array(
		'label' => 'Burak',
		'types' => '_null|_int(11)|_foreign_key(contacts_ibfk_56)(posts)(post_id)|_key(contacts_ibfk_56)(burak)',
		'rules' => '',
		),
	'ersin' => array(
		'label' => 'Ersin',
		'types' => '_null|_int(11)|_foreign_key(contacts_ibfk_5)(posts)(post_id)|_key(contacts_ibfk_5)(ersin)',
		'rules' => '',
		),
	'ersin2' => array(
		'label' => 'Ersin2',
		'types' => '_null|_int(11)',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */