<?php 

$comments = array(
    '*' => array(),
    
    'comment_id' => array(
        'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
        ),
    'comment_post_id' => array(
        'types' => '_not_null|_int(11)|_key(comment_post_id)(comment_post_id)|_foreign_key(comments_ibfk_1)(posts)(post_id)',
        ),
    'comment_name' => array(
        'types' => '_not_null|_varchar(50)',
        ),
    'comment_email' => array(
        'types' => '_null|_varchar(255)',
        ),
    'comment_website' => array(
        'types' => '_null|_varchar(255)',
        ),
    'comment_body' => array(
        'types' => '_not_null|_text',
        ),
    'comment_creation_date' => array(
        'types' => '_null|_datetime',
        ),
    'comment_modification_date' => array(
        'types' => '_null|_datetime',
        ),
    'comment_status' => array(
        'types' => '_not_null|_tinyint(1)|_default(0)',
        ),
);
 
/* End of file comments.php */
/* Location: .app/schemas/comments.php */