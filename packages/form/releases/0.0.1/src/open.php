<?php
namespace Form\Src {

    /**
    * Form Declaration
    *
    * Creates the opening portion of the form.
    *
    * @access	public
    * @param	string	the URI segments of the form destination
    * @param	array	a key/value pair of attributes
    * @param	array	a key/value pair hidden data
    * @return	string
    */
    function open($action = '', $attributes = '', $hidden = array())
    {
        $config = getConfig();
        
        if ($attributes == '')
        {
            $attributes = 'method="post"';
        }

        $action = ( strpos($action, '://') === false) ? getInstance()->uri->getSiteUrl($action) : $action;

        $form  = '<form action="'.$action.'"';
        $form .= \Form::_attributesToString($attributes, true);
        $form .= '>';

        if ($config['csrf_protection'] === true) // CSRF Support
        {
            $security = getComponentInstance('security');
            $hidden[$security->getCsrfTokenName()] = $security->getCsrfHash();
        }

        if (is_array($hidden) AND count($hidden) > 0)
        {
            $form .= sprintf("<div style=\"display:none\">%s</div>", getInstance()->form->hidden($hidden));
        }

        return $form;
    }

}