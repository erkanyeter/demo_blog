<?php 

$contacts = array(
    '*' => array(),
    
    'contact_id' => array(
        'types' => '_not_null|_auto_increment|_primary_key|_int(11)',
        ),
    'contact_name' => array(
        'types' => '_not_null|_varchar(50)',
        ),
    'contact_email' => array(
        'types' => '_varchar(50)|_not_null',
        ),
    'contact_subject' => array(
        'types' => '_not_null|_varchar(255)',
        ),
    'contact_body' => array(
        'types' => '_null|_text',
        ),
    'contact_creation_date' => array(
        'types' => '_not_null|_datetime',
        ),
);
 
/* End of file contacts.php */
/* Location: .app/schemas/contacts.php */