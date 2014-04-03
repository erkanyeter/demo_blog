<?php
/*
| -------------------------------------------------------------------------
| Schemes
| -------------------------------------------------------------------------
| This file lets you define "schemes" to extend views without hacking
| the view function. Please see the docs for info:
|
|   @see docs/advanced/schemes
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
| $config = array(
|
|    'default' => function()
|    {
|        $this->set('header', $this->getTpl('header',false))
|        $this->set('footer', $this->getTpl('footer',false));
|    },
| );
|
*/
$config = array(

    'default' => function () {
        $this->set('header', '@get.private/views/header');
        $this->set('sidebar', '@get.private/views/sidebar');
        $this->set('footer', '@get.tpl.footer');
    },
    'welcome' => function () {
        $this->set('footer', '@get.tpl.footer');
    },
);

/* End of file scheme.php */
/* Location: ./app/config/scheme.php */