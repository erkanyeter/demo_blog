<?php 

$posts = array(
    '*' => array(),
    
    'post_id' => array(
        'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
        ),
    'post_user_id' => array(
        'types' => '_int(11)|_key(post_user_id)(post_user_id)|_not_null|_foreign_key(posts_ibfk_1)(users)(user_id)',
        ),
    'post_title' => array(
        'types' => '_varchar(255)|_not_null',
        ),
    'post_content' => array(
        'types' => '_not_null|_text',
        ),
    'post_tags' => array(
        'types' => '_varchar(255)|_null',
        ),
    'post_status' => array(
        '_enum' => array(
            'Draft',
            'Published',
            'Archived',
        ),
        'types' => '_not_null|_enum|_default(Published)',
        ),
    'post_creation_date' => array(
        'types' => '_null|_datetime',
        ),
    'post_modification_date' => array(
        'types' => '_null|_datetime',
        ),
);
 
/* End of file posts.php */
/* Location: .app/schemas/posts.php */