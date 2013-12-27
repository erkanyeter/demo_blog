<?php
namespace Form\Src {

    //--------------------------------------------------------------------

    /**
    * Drop-down Menu
    *
    * @access	public
    * @param	string $name
    * @param	mixed $options
    * @param	array $selected
    * @param	string extra data
    * 
    * @return	string
    */
    function dropdown($name = '', $options = 'getSchema(posts)[field][_enum]', $selected = array(), $extra = '')
    {
        // --------- PARSE SCHEMA ---------- //
        
        if(is_string($options)) // fetch options from schema
        {
            $options = _parseSchemaOptions($options);
        } 

        if(is_array($options) AND (is_string($options[0]) AND strpos($options[0], 'getSchema') === 0))
        {
            if(isset($options[1])) // custom options
            { 
                $customOption = $options[1];
                $options = _parseSchemaOptions($options[0]);
                $options = array_merge($options,$customOption);
            }

        } elseif(is_array($options) 
            AND isset($options[1]) 
            AND strpos($options[1], 'getSchema') === 0)
        {
            $customOption = $options[0];
            $options = _parseSchemaOptions($options[1]);
            $options = array_merge($customOption,$options);
        }

        // --------- PARSE SCHEMA END ---------- //

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

        $multiple = (sizeof($selected) > 1 AND strpos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';
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

    //--------------------------------------------------------------------
    
    /**
     * Parse Schema Options
     * 
     * @param  array $options
     * @return array
     */
    function _parseSchemaOptions($options)
    {
        preg_match('/^(.*?)\((.*?)\)\[(.*?)\]\[(.*?)\]$/', $options, $matches); 

        // Array
        // (
        //     [0] => getSchema(posts)[status][_enum]
        //     [1] => getSchema
        //     [2] => posts
        //     [3] => status
        //     [4] => _enum
        // );

        $schemaName = $matches[2];
        $fieldName  = $matches[3];
        $enumName   = $matches[4];

        $schema  = getSchema($schemaName);
        $options = array();
        foreach($schema[$fieldName][$enumName] as $v)
        {
            $options[$v] = $v;
        }

        return $options;
    }

}