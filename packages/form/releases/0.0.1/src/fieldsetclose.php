<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Fieldset Close Tag
    *
    * @access   public
    * @param    string
    * @return   string
    */
    function fieldsetClose($extra = '')
    {
        $form = \Form::getConfig();

        return "</fieldset>".$form['templates'][\Form::$template]['fieldsetClose'].$extra;
    }

}