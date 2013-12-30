<?php 

$contacts = array(
	'*' => array('colprefix' => 'contact_'),
	
	'id' => array(
		'label' => 'Contact Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'name' => array(
		'label' => 'Contact Name',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|maxLen(50)',
		),
	'email' => array(
		'label' => 'Contact Email',
		'types' => '_null|_varchar(50)',
		'rules' => 'required|validEmail',
		),
	'subject' => array(
		'label' => 'Contact Subject',
		'types' => '_null|_varchar(255)',
		'rules' => 'required|maxLen(100)',
		),
	'body' => array(
		'label' => 'Contact Body',
		'types' => '_null|_text',
		'rules' => 'required|xssClean',
		),
	'creation_date' => array(
		'label' => 'Contact Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */