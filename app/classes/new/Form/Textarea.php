<?php
Class Form_Textarea {

    // ------------------------------------------------------------------------

    /**
    * Textarea field
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    public function __invoke($data = '', $value = '', $extra = '')
    {
        if (is_object($value)) {  // $_REQUEST & Db value sync with schema
            $value = getInstance()->form->_getRowValue($value, $data); 
        }
        
        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '90', 'rows' => '12');

        if ( ! is_array($data) OR ! isset($data['value'])) {
            $val = $value;

            if (strpos($extra, 'rows') !== false OR strpos($extra, 'cols') !== false) {
                $defaults = array('name' => ( ! is_array($data)) ? $data : '');
            }
        } else {
            $val = $data['value']; 
            unset($data['value']); // textareas don't use the value attribute
        }

        $name = (is_array($data)) ? $data['name'] : $data;

        $form = \Form::getConfig();

        $textarea = '<textarea '.\Form::_parseFormAttributes($data, $defaults).$extra.">".getInstance()->form->prep($val, $name).'</textarea>';

        return sprintf($form['templates'][\Form::$template]['textarea'], $textarea);
    }

}