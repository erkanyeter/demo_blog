<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Reset Button
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    string
    * @return   string
    */
    function reset($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'reset', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        $form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['reset'], "<input ".\Form::_parseFormAttributes($data, $defaults).$extra." />");
    }

}