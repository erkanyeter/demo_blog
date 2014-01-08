<?php 

$contacts = array(
	'*' => array(),
	
	'contact_id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'contact_name' => array(
		'label' => 'Contact Name',
		'types' => '_null|_varchar(50)',
		'rules' => '',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_null|_varchar(50)|_unique_key(contact_email)(contact_email,contact_id)',
		'rules' => 'required|minVal(10)',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_null|_varchar(255)',
		'rules' => '',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'types' => '_null|_text',
		'rules' => '',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'test' => array(
		'label' => 'Test',
		'types' => '_not_null|_int(11)',
		'rules' => '',
		),
	'test2' => array(
		'label' => 'Test',
		'types' => '_not_null|_int(11)',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */