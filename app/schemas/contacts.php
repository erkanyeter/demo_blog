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
		'types' => '_null|_text',
		'rules' => 'required|xssClean',
		),
	'contact_creation_date' => array(
		'label' => 'Contact Creation Date',
<<<<<<< HEAD
		'_enum' => array(
			'data',
			'ersin\'s',
		),
		'types' => '_enum|_not_null|_key(contacts2)(ersin)|_key(contacts_ibfk_5)(ersin)',
		'rules' => '',
		),
	'ersin' => array(
		'label' => 'Ersin',
		'types' => '_null|_foreign_key(contacts_ibfk_5)(posts)(post_id)|_int(11)|_key(contacts2)(ersin)|_key(contacts_ibfk_57)(ersin)|_key(contacts_ibfk_56)(ersin)|_key(contacts_ibfk_5)(ersin)',
		'rules' => '',
		),
	'burak' => array(
		'label' => 'Test1',
		'types' => '_null|_int(11)|_key(contact_subject_id)(contact_subject_id)|_foreign_key(contacts_ibfk_56)(posts)(post_id)|_key(contacts_ibfk_56)(burak)',
		'rules' => '',
		),
	'ersin2' => array(
		'label' => 'Ersin2',
		'types' => '_null|_int(11)',
=======
		'types' => '_null|_datetime',
>>>>>>> 6edc05e7fed51ee86670e216da0ce7ab808643f0
		'rules' => '',
		),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */