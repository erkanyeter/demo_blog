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
		'types' => '_not_null|_default(true)|_varchar(255)|_key(asdsa3)(contact_subject)|_key(asdsa6543)(contact_subject)|_unique_key(asdsad4)(contact_subject)|_unique_key(asdsad4456)(contact_subject)',
		'rules' => '',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_not_null|_default(0)|_unsigned|_int(11)',
		'rules' => '',
		),
	'contact_body' => array(
		'label' => 'Contact Body',
		'types' => '_not_null|_default(0)|_unsigned|_int(11)',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */