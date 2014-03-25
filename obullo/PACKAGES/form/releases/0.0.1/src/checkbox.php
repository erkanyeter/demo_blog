<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Checkbox Field
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	bool
    * @param	string
    * @return	string
    */
    function checkbox($data = '', $value = '', $checked = false, $extra = '')
    {
        $form = \Form::getConfig();

        if(is_object($value))  // $_POST & Db value schema sync
        {
            $value = getInstance()->form->_getRowValue($selected, $data); 
        }

        $defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

        if (is_array($data) AND array_key_exists('checked', $data))
        {
            $checked = $data['checked'];

            if ($checked == false)
            {
                unset($data['checked']);
            }
            else
            {
                $data['checked'] = 'checked';
            }
        }

        if ($checked == true)
        {
            $defaults['checked'] = 'checked';
        }
        else
        {
            unset($defaults['checked']);
        }

        $type = 'checkbox';

        if(isset($data['type']) AND $data['type'] == 'radio')
        {
            $type = 'radio';
        }

        return sprintf($form['templates'][\Form::$template][$type], "<input ".\Form::_parseFormAttributes($data, $defaults).$extra." />");
    }
    
}