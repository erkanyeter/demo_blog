<?php
namespace Form\Src {

    //--------------------------------------------------------------------

    /**
    * Drop-down Menu
    *
    * @access   public
    * @param    string $name
    * @param    mixed $options      @getSchema.posts.field
    * @param    array $selected
    * @param    string extra data
    * 
    * @return   string
    */
    function dropdown($name = '', $options = '', $selected = array(), $extra = '')
    {
        if(is_object($selected))  // $_POST & Db value schema sync
        {
            $selected = getInstance()->form->_getRowValue($selected, $name); 
        }
        
        // --------- Hvc support begin ---------- //

        if(is_string($options) AND strpos($options, '@') === 0) // Use hvc or not
        {
            $matches = explode('.', $val);
            $method  = trim($matches[0], '@');
            $uri     = $matches[1];
            $param   = (isset($matches[2])) ? $matches[2] : null;

            if( ! isset(getInstance()->hvc)) {
                new \Hvc; // call hvc class
            }

            $r = getInstance()->hvc->$method($uri, $param);

            if(isset($r['results']) AND is_array($r['results'])) {
                $options = $r['results'];
            }
        } 

        // --------- Hvc end ---------- //

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

}