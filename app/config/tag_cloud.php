<?php

/*
|--------------------------------------------------------------------------
| Tag Cloud Package Configuration
|--------------------------------------------------------------------------
| Prototype: 
|
|   $tag_cloud['key'] = value;
| 
*/
$tag_cloud['url']         = new Url; // Url object for $this->url->anchor(); method.
$tag_cloud['seo_segment'] = 'tag';   // Seo segment name e.g. http://www.domain.com/tag/test_tag_name
$tag_cloud['formatting']  = array(
                                'transformation' => function ($str) {
                                    return trim($str);
                                },
                                'url_separator'  => '_',      // tag separator e.g. test_tag_name
                                'link_seperator' => '&nbsp;', // link seperator e.g. <a></a>&nbsp;<a></a>
                                );


/* End of file tag_cloud.php */
/* Location: .app/config/tag_cloud.php */