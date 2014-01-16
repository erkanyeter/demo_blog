<?php
  
/**
* Form Helper
*
* @package       packages
* @subpackage    form
* @category      html forms
* @link
*/  

Class Form {

    public static $template = 'default';  // Default form template

    public function __construct()
    {
        self::getFormConfig();  // Initialize to form config and validator object.

        if( ! isset(getInstance()->form))
        {
            getInstance()->form = $this; // Make available it in the controller $this->form->method();
        }

        logMe('debug', 'Form Class Initialized');
    }

    // ------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        global $packages;

        if(method_exists(getInstance()->validator, $method))  // Call the Validator object methods
        {
            logMe('debug', 'Form Class '.ucfirst($method).' Executed');

            return call_user_func_array(array(getInstance()->validator, $method), $arguments);
        }

        if( ! function_exists('Form\Src\\'.$method))    // Call Form Class functions
        {
            require (PACKAGES .'form'. DS .'releases'. DS .$packages['dependencies']['form']['version']. DS .'src'. DS .strtolower($method). EXT);

            logMe('debug', 'Form Class '.ucfirst($method).' Executed');
        }

        return call_user_func_array('Form\Src\\'.$method, $arguments);
    }

    // ------------------------------------------------------------------------

    /**
    * Parse the form attributes
    *
    * Helper function used by some of the form helpers
    *
    * @access   private
    * @param    array
    * @param    array
    * @return   string
    */
    public static function _parseFormAttributes($attributes, $default)
    {
        if (is_array($attributes))
        {
            foreach ($default as $key => $val)
            {
                if (isset($attributes[$key]))
                {
                    $default[$key] = $attributes[$key];
                    unset($attributes[$key]);
                }
            }

            if (count($attributes) > 0)
            {
                $default = array_merge($default, $attributes);
            }
        }

        $att = '';
        foreach ($default as $key => $val)
        {
            if ($key == 'value')
            {
                $val = getInstance()->form->prep($val, $default['name']);
            }

            $att .= $key . '="' . $val . '" ';
        }

        return $att;
    }

    // ------------------------------------------------------------------------

    /**
    * Attributes To String
    *
    * Helper function used by some of the form helpers
    *
    * @access   private
    * @param    mixed
    * @param    bool
    * @return   string
    */
    public static function _attributesToString($attributes, $formtag = false)
    {
        $form = \Form::getFormConfig();  // get form template

        if (is_string($attributes) AND strlen($attributes) > 0)
        {
            $attributes = str_replace('\'', '"',$attributes); // convert to double quotes.

            if (strpos($attributes, 'method=') === false)
            {
                $attributes.= ' method="post"';
            }

            if (strpos($attributes, 'accept-charset=') === FALSE)
            {
                $attributes.= ' accept-charset="'.strtolower(config('charset')).'"';
            }

            if($formtag)
            {
                $class = preg_match('/(class)(\s*(=))(\s*("))(.*?)(")/', $attributes, $matches); // Search class attribute

                if ($class == false)
                {
                    $attributes.= sprintf(' class="%s"', $form['templates'][self::$template]['formClass']);
                } 
                elseif(isset($matches[6]))
                {
                    $attributes = str_replace($matches[0], '', $attributes);
                    $attributes.= sprintf(' class="%s %s"', $matches[6], $form['templates'][self::$template]['formClass']);   
                }
            }

            return ' '.$attributes;
        }

        if (is_object($attributes) AND count($attributes) > 0)
        {
            $attributes = (array)$attributes;
        }

        if (is_array($attributes) AND count($attributes) > 0)
        {
            $atts = '';

            if ( ! isset($attributes['method']) AND $formtag == true)
            {
                $atts.= ' method="post"';
            }

            if ( ! isset($attributes['accept-charset']) AND $formtag == true)
            {
                $atts.= ' accept-charset="'.strtolower(config('charset')).'"';
            }

            if(isset($attributes['class']))
            {
                $templateElement = isset($form['use_template']) ? $form['templates'][self::$template]['formClass'].' ' : '';
                $atts.= ' class="'.$templateElement.$attributes['class'].'"';
                unset($attributes['class']);
            } 
            else 
            {
                $templateElement = isset($form['use_template']) ? $form['templates'][self::$template]['formClass'] : '';
                $atts.= ' class="'.$templateElement.'"';
            }

            foreach ($attributes as $key => $val)
            {
                $atts.= ' '.$key.'="'.$val.'"';
            }

            return $atts;
        }
    }

    // ------------------------------------------------------------------------
    
    /**
     * Grab the Validator Instance
     * 
     * @return type
     */
    public function validatorGetInstance()
    {
        return getInstance()->validator;
    }
    
    // ------------------------------------------------------------------------

    /**
    * Validation Object
    *
    * Determines what the form validation class was instantiated as, fetches
    * the object and returns it.
    *
    * @access   public
    * @return   mixed
    */
    public function _getValidatorObject()
    {
        if ( ! class_exists('Validator'))
        {
            return false;
        }
        
        return getInstance()->validator;
    }

    // ------------------------------------------------------------------------

    /**
     * Get form configuration
     * 
     * @return array
     */
    public static function getFormConfig()
    {
        $form = getConfig('form');

        if($form['use_template'] == false)
        {
            $array['templates'][self::$template] = array_map('strip_tags', $form['templates'][self::$template]);
            $array['notifications'] = $form['notifications'];

            return $array;   
        }

        return $form;
    }

}

/* End of file form.php */
/* Location: ./packages/form/releases/0.0.1/form.php */