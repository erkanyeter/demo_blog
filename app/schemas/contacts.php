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
		'rules' => 'required',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_null|_varchar(50)',
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
		'types' => '_enum|_null',
		'rules' => '',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */