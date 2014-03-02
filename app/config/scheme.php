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
| $scheme = array(
|
|    'default' => function($file)
|    {
|        $this->set('header', $this->tpl('header',false))
|        $this->set('content', $file);
|        $this->set('footer', $this->tpl('footer',false));
|    },
| );
|
*/
$scheme = array(

    'default' => function () {
        $this->set('header', $this->hvc->get('private/views/header'));
        $this->set('sidebar', $this->hvc->get('private/views/sidebar'));
        $this->set('footer', $this->getTpl('footer', false));
    },
    'welcome' => function () {
        $this->set('footer', $this->getTpl('footer', false));
    },
);

/* End of file scheme.php */
/* Location: ./app/config/scheme.php */