<?php
Class Form_Label {

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
    public function __invoke($label_text = '', $id = '', $attributes = "")
    {
        $label = '<label';
        $label_text = translate($label_text);
        
        if (empty($id)) {
            $id = mb_strtolower($label_text);
        }

        $label .= " for=\"$id\"";

        if (is_array($attributes) AND count($attributes) > 0) {
            foreach ($attributes as $key => $val) {
                $label .= ' '.$key.'="'.$val.'"';
            }
        } else {
            $label .= $attributes;
        }

        $label .= ">$label_text</label>";

        $form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['label'], $label);
    }

}