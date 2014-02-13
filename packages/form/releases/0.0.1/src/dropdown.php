<?php
namespace Form\Src {

    //--------------------------------------------------------------------

    /**
    * Drop-down Menu
    *
    * @access	public
    * @param	string $name
    * @param	mixed $options      @getSchema.posts.field
    * @param	array $selected
    * @param	string extra data
    * 
    * @return	string
    */
    function dropdown($name = '', $options = '', $selected = array(), $extra = '')
    {
        // --------- PARSE SCHEMA BEGIN ---------- //
        
        if(is_object($selected))  // $_POST & Db value schema sync
        {
            $selected = getInstance()->form->_getSchemaPost($selected, $name); 
        }

        if(is_string($options) AND strpos($options, '@') === 0) // fetch options from schema
        {
            $options = _parseSchemaOptions($options);
        } 

        // if(is_array($options))
        // {
        //     if(isset($options[0]) AND is_string($options[0]) AND strpos($options[0], '@getSchema') === 0)
        //     {
        //         if(isset($options[1])) // custom options
        //         { 
        //             $customOption = $options[1];
        //             $options      = _parseSchemaOptions($options[0]);
        //             $options      = array_merge($options, $customOption);
        //         }

        //     } elseif(isset($options[1]) AND strpos($options[1], '@getSchema') === 0)
        //     {

        //         $customOption = $options[0];
        //         $options      = _parseSchemaOptions($options[1]);
        //         $options      = array_merge($customOption,$options);
        //     }
        // }

        // --------- PARSE SCHEMA END ---------- //

        if($selected === false)   // False == "0" bug fix, false is not an Integer.
        {
            $selected_option = array_keys($options);
            $selected        = $selected_option[0];
        }
        
        if ( ! is_array($selected))
        {
            $selected = array($selected);
        }

        if (sizeof($selected) === 0) // If no selected state was submitted we will attempt to set it automatically
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

        $multiple  = (sizeof($selected) > 1 AND strpos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';
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

        $form = \Form::getConfig(); // get template

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
        // @getSchema.users._enum.func

        $trimmedOptions   = trim($options, '@');
        $formattedOptions = explode('.', $trimmedOptions);

        // Array
        // (
        //     [0] => getSchema
        //     [1] => posts
        //     [2] => business_size
        //     [3] => func
        //     [4] => low  // high // list
        // );

        $schemaName = $formattedOptions[1];
        $fieldName  = $formattedOptions[2];
        $funcName   = $formattedOptions[3]; // _enum / _set / func


        $schema  = getSchema($schemaName);

        if($funcName == 'func')
        {
            $function = $schema[$fieldName][$funcName];
            $closure  = $function;

            if(is_array($function) AND isset($formattedOptions[4]))  // Associative array closure
            {       
                $method  = $formattedOptions[4];
                $closure = $schema[$fieldName][$funcName][$method];
            }
            
            if(is_callable($closure))   // Pure Closure
            {
                $options = call_user_func_array(\Closure::bind($closure, getInstance(), 'Controller'), array());
            }
        } 
        else 
        {
            $options = array();
            foreach($schema[$fieldName][$funcName] as $v)
            {
                $options[$v] = $v;
            }
        }

        return $options;
    }

}