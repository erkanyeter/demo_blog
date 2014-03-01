<?php 

$users = array(
    '*' => array(),
    
    'user_id' => array(
        'types' => '_primary_key|_int(11)|_auto_increment|_not_null',
        ),
    'user_email' => array(
        'types' => '_varchar(60)|_not_null',
        ),
    'user_password' => array(
        'types' => '_not_null|_varchar(75)',
        ),
    'user_creation_date' => array(
        'types' => '_datetime|_null',
        ),
    'user_modification_date' => array(
        'types' => '_null|_datetime',
        ),
    'user_username' => array(
        'types' => '_not_null|_varchar(50)',
        ),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */