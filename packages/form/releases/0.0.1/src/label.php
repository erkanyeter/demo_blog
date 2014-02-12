<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Label
    *
    * @access   public
    * @param    string  The text to appear onscreen
    * @param    string  The id the label applies to
    * @param    string  Additional attributes
    * @return   string
    */
    function label($label_text = '', $id = '', $attributes = "")
    {
        $label = '<label';

        if (substr($label_text, 0, 10) == 'translate:') // Do we need to translate the field name? 
        {
            $line       = substr($label_text, 10);   // Grab the variable
            $label_text = translate($line);
        }

        if(empty($id))
        {
            $id = mb_strtolower($label_text);
        }

        $label .= " for=\"$id\"";

        if (is_array($attributes) AND count($attributes) > 0)
        {
            foreach ($attributes as $key => $val)
            {
                $label .= ' '.$key.'="'.$val.'"';
            }
        } 
        else 
        {
            $label .= $attributes;
        }

        $label .= ">$label_text</label>";

        $form = \Form::getFormConfig();

        return sprintf($form['templates'][\Form::$template]['label'], $label);
    }

}