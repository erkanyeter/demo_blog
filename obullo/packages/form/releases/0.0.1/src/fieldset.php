<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Fieldset Tag
    *
    * Used to produce <fieldset><legend>text</legend>.  To close fieldset
    * use form_fieldset_close()
    *
    * @access	public
    * @param	string	The legend text
    * @param	string	Additional attributes
    * @return	string
    */
    function fieldset($legend_text = '', $attributes = array())
    {
        $fieldset = "<fieldset";
        $fieldset .= \Form::_attributesToString($attributes, false);
        $fieldset .= ">\n";

        if ($legend_text != '')
        {
            $fieldset .= "<legend>$legend_text</legend>\n";
        }

        $form = \Form::getConfig();

        return $form['templates'][\Form::$template]['fieldsetOpen'].$fieldset;
    }

}