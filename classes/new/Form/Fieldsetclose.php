<?php
Class Form_FieldsetClose {

    // ------------------------------------------------------------------------

    /**
    * Fieldset Close Tag
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function __invoke($extra = '')
    {
        $form = \Form::getConfig();

        return "</fieldset>".$form['templates'][\Form::$template]['fieldsetClose'].$extra;
    }

}