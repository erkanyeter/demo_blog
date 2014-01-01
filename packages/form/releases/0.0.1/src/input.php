<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Text Input Field
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    function input($data = '', $value = '', $extra = '')
    {
        $value = getInstance()->form->_getSchemaPost($value, $data);  // $_REQUEST & Db value sync with schema

        $defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        $form = \Form::getFormConfig();

        $inputElement = "<input ".\Form::_parseFormAttributes($data, $defaults).$extra." />";

        if(strpos($inputElement, 'type="text"') > 0)
        {
            return sprintf($form['templates'][\Form::$template]['text'], $inputElement);
        }

        return $inputElement;
    }
    
}