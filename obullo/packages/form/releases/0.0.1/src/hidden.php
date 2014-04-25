<?php
namespace Form\Src {    

    // ------------------------------------------------------------------------

    /**
    * Hidden Input Field
    *
    * Generates hidden fields.  You can pass a simple key/value string or an associative
    * array with multiple values.
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @return	string
    */
    function hidden($name, $value = '', $extra = '', $recursing = false)
    {
        static $hiddenTag;

        $form = \Form::getConfig();

        if(is_object($value)) // $_POST & Db value schema sync
        {
            $value = getInstance()->form->_getRowValue($value, $name); 
        }

        if ($recursing === false)
        {
            $hiddenTag = "\n";
        }

        if (is_array($name))
        {
            foreach ($name as $key => $val)
            {
                getInstance()->form->hidden($key, $val, '', true);
            }
            
            return sprintf($form['templates'][\Form::$template]['hidden'], $hiddenTag);
        }

        if ( ! is_array($value))
        {
            $hiddenTag .= '<input type="hidden" name="'.$name.'" value="'.getInstance()->form->prep($value, $name).'"'. $extra . '/>'."\n";
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $k = (is_int($k)) ? '' : $k;

                getInstance()->form->hidden($name.'['.$k.']', $v, '', true);
            }
        }

        return sprintf($form['templates'][\Form::$template]['hidden'], $hiddenTag);
    }
    
}