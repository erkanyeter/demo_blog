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
		'types' => '_not_null|_varchar(50)',
		'rules' => 'required|maxLen(40)',
		),
	'contact_email' => array(
		'label' => 'Contact Email',
		'types' => '_varchar(50)|_not_null',
		'rules' => 'required|validEmail',
		),
	'contact_subject' => array(
		'label' => 'Contact Subject',
		'types' => '_not_null|_default(true)|_varchar(255)',
		'rules' => 'required',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'types' => '_null|_text',
		'rules' => 'required',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_not_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */