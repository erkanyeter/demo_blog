<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Label
    *
    * @access	public
    * @param	string	The text to appear onscreen
    * @param	string	The id the label applies to
    * @param	string	Additional attributes
    * @return	string
    */
    function label($label_text = '', $id = '', $attributes = array())
    {
        $label = '<label';

        if(empty($id))
        {
            $id = strtolower($label_text);
        }

        $label .= " for=\"$id\"";

        if (is_array($attributes) AND count($attributes) > 0)
        {
            foreach ($attributes as $key => $val)
            {
                $label .= ' '.$key.'="'.$val.'"';
            }
        }

        $label .= ">$label_text</label>";

        $form = \Form::getFormConfig();

        return sprintf($form['templates'][\Form::$template]['label'], $label);
    }

}