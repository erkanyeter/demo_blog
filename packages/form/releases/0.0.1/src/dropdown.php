<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
    * Drop-down Menu
    *
    * @access	public
    * @param	string
    * @param	array
    * @param	string
    * @param	string
    * @return	string
    */
    function dropdown($name = '', $options = array(), $selected = array(), $extra = '')
    {
        if($selected === false)   // False == "0" bug fix, false is not an Integer.
        {
            $selected_option = array_keys($options);
            $selected = $selected_option[0];
        }
        
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        if (count($selected) === 0) // If no selected state was submitted we will attempt to set it automatically
        {
            if (isset($_POST[$name]))  // If the form name appears in the $_POST array we have a winner !
            {
                $selected = array($_POST[$name]);
            }
        }

        if ($extra != '') 
        {
            $extra = ' '.$extra;
        }

        $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';
        $selectTag = '<select name="'.$name.'"'.$extra.$multiple.">\n";

        foreach ($options as $key => $val)
        {
            $key = (string) $key;
            
            if (is_array($val))
            {
                $selectTag .= '<optgroup label="'.$key.'">'."\n";

                foreach ($val as $optgroup_key => $optgroup_val)
                {
                    $sel = (in_array($optgroup_key, $selected, true)) ? ' selected="selected"' : '';
                    
                    $selectTag .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
                }

                $selectTag .= '</optgroup>'."\n";
            }
            else
            {
                $sel = (in_array($key, $selected, true)) ? ' selected="selected"' : '';
                
                $selectTag .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
            }
        }

        $selectTag .= '</select>';

        $form = \Form::getFormConfig(); // get template

        return sprintf($form['templates'][\Form::$template]['dropdown'], $selectTag);
    }
    
}