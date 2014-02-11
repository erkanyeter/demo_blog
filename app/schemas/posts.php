<?php 

$posts = array(
	'*' => array(),
	
	'post_id' => array(
		'label' => 'Post Id',
		'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
		'rules' => '',
		),
	'post_user_id' => array(
		'label' => 'Post User Id',
		'types' => '_int(11)|_key(post_user_id)(post_user_id)|_not_null|_foreign_key(posts_ibfk_1)(users)(user_id)',
		'rules' => '',
		),
	'post_title' => array(
		'label' => 'Post Title',
		'types' => '_not_null|_varchar(255)',
		'rules' => 'required|maxLen(255)',
		),
	'post_content' => array(
		'label' => 'Post Content',
		'types' => '_not_null|_text',
		'rules' => 'required',
		),
	'post_tags' => array(
		'label' => 'Post Tags',
		'types' => '_varchar(255)|_null',
		'rules' => 'required|maxLen(255)',
		),
	'post_status' => array(
		'label' => 'Post Status',
		'func' => array(
			'all' => function(){
				$post_status = getSchema('posts')['post_status']['_enum'];
				$options = array();
				foreach($post_status as $k => $v)
				{
					$options[$v] = $v;
				}
				return $options;
			},
			'list' => function(){  // remap
				$post_status = getSchema('posts')['post_status']['func']['all'];
				return array_merge(array('' => 'All'), $post_status());
			}
		),
		'_enum' => array(
			'Draft',
			'Published',
			'Archived',
		),
		'types' => '_not_null|_enum|_default(Published)|_key(post_status)(post_status)',
		'rules' => 'required',
		),
	'post_creation_date' => array(
		'label' => 'Post Creation Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
	'post_modification_date' => array(
		'label' => 'Post Modification Date',
		'types' => '_null|_datetime',
		'rules' => '',
		),
);
 
/* End of file posts.php */
/* Location: .app/schemas/posts.php */