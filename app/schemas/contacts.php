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
		'rules' => 'required|minVal(10)',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_null|_varchar(255)',
		'rules' => 'required',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'types' => '_null|_text',
		'rules' => 'required',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */