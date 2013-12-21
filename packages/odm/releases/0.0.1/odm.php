<?php

/**
 * Odm - ( Object Data Model )
 *
 * @package       packages
 * @subpackage    odm
 * @category      model
 * @link            
 */

Class Odm {

    use Odm\Src\Model_Trait;

    public $_odmSchema       = null;      // User schema
    public $_odmTable        = '';        // Table name ( model name )
    public $_odmErrors       = array();   // Validation errors and transactional ( Insert, Update, delete ) messages
    public $_odmValues       = array();   // Filtered safe values
    public $_odmValidation   = false;     // If form validation success we set it to true
    public $_odmValidator;
    public $_odmFormTemplate = 'default'; // default form template defined in app/config/form.php
    public $_odmGet;                      // Get package object
    
    // --------------------------------------------------------------------

    /**
    * Construct the valid schema items
    * 
    * @return void
    */
    public function __construct(array $schemaArray, $dbObject)
    {
        $odm = getConfig('odm'); // Initialize to Validator Object.
        $this->_odmValidator = getInstance()->validator; 

        $this->clear();          // Clear the validator.

        $this->_odmTable = strtolower($schemaArray['*']['_tablename']);
        $this->_odmGet   = $odm['get']; // Get "Get" object

        unset($schemaArray['*']);  // Remove settings

        $this->_odmSchema = $schemaArray;

        getInstance()->lingo->load('odm');  // Odm language file.

        $this->{Db::$var} = $dbObject; // Store database object

        logMe('debug', 'Odm Class Initialized');
    }

    // --------------------------------------------------------------------
    
    /**
    * Grab validator object and set validation rules.
    * 
    * @param fields schema fields.
    * @return boolean
    */
    public function isValid($fields = array())
    {
        $validator = $this->_odmValidator;

        $table  = $this->_odmTable;
        $fields = (sizeof($fields) == 0) ? $this->_odmSchema : $fields;
        $fields = array_merge($fields, $validator->_field_data); // Merge "Odm" and "Validator fields"

        unset($fields['*']); // Remove settings to getting only fields.

        $validator->set('_callback_object', $this);

        foreach($fields as $key => $val)
        {
            if(is_array($val))
            {
                if(isset($val['rules']) AND $val['rules'] != '')
                {
                    if(isset($this->_properties[$key])) // Set selected key to REQUEST.
                    {
                        $label = (isset($val['label'])) ? $val['label'] : '';   // $_REQUEST[$key] = $this->{$key};
                        $validator->setRules($key, $label, $val['rules']);
                    }
                }
            }
        }
        
        if($validator->run())   // Run the validation
        {
            foreach($fields as $key => $val)  // Set filtered values
            {
                if(isset($val['rules']) AND $val['rules'] != '' AND isset($this->_properties[$key])) 
                {
                    $this->_odmValues[$table][$key] = $this->_setValue($key, (isset($this->_properties[$key])) ? $this->_properties[$key] : $this->_odmGet->post($key));
                }
            }
            
            //----------------------------

            $this->_odmValidation = true;

            //----------------------------
            
            return true;

            //----------------------------
        }
        else 
        {
            foreach($fields as $key => $val)  // Set validation errors.
            {
               if(isset($validator->_field_data[$key]['error']))
               {
                   $error = $validator->errors($key, null, null);

                   if( ! empty($error))
                   {
                       $this->_odmErrors[$table]['errors'][$key] = $error;
                   }
               }

                //----------------------------

                $this->_odmErrors[$table]['messages'] = array(
                    'success' => 0, 
                    'errorKey' => 'validationError',
                    'errorCode'  => 10,
                    'errorString' => 'There are some errors in the form fields.',
                    'errorMessage' => lingo('There are some errors in the form fields.')
                    );

                //----------------------------
               
                if(isset($val['rules']) AND $val['rules'] != '' AND isset($this->_properties[$key]))  // Set filtered values.
                {
                    $this->_odmValues[$table][$key] = $this->_setValue($key, (isset($this->_properties[$key])) ? $this->_properties[$key] : $this->_odmGet->post($key)); 
                }
            }
            
            //----------------------------

            $this->_odmValidation = false;

            //----------------------------
            
            return false;

            //----------------------------
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * Sets custom validation rule into validator object.
     * 
     * @param string $key field
     * @param array $val validation rules
     */
    public function setRules($key, $label, $rules = '')
    {
        $this->_odmValidator->setRules($key, $label, $rules);
    }

    // --------------------------------------------------------------------
    
    /**
    * Check Validation is success ?
    *
    * @return boolean
    */
    public function getValidation()
    {
        return $this->_odmValidation;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Do type casting foreach value if we need ?
    * 
    * @param mixed $field
    * @param mixed $default
    * @return string
    */
    public function _setValue($field, $default = '')
    {
        return $default;
    }

    // --------------------------------------------------------------------
    
    /**
    * Clear Validation Object.
    */
    public function clear()
    {        
        $this->_odmValidation = false;

        $validator = $this->_odmValidator;
        $validator->clear();

        $this->_odmSchema    = null;     // Schema object.
        $this->_odmTable     = '';       // Tablename.
        $this->_odmErrors    = array();  // Validation errors.
        $this->_odmMessage   = array();  // Save messsages.
        $this->_odmValues    = array();  // Filtered safe values.
        $this->_odmFormTemplate = 'default'; // Form template.
    }    

    // --------------------------------------------------------------------
    
    /**
    * Return filtered validation values for current model.
    * 
    * @param string $field return to filtered one item's value.
    * @return array
    */
    public function values()
    {
        $table = $this->_odmTable;

        if(isset($this->_odmValues[$table]))
        {
            return $this->_odmValues[$table];
        }

        return;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid field value
     * 
     * @param  string $field
     * @return bool | string
     */
    public function getValue($field)
    {
        $table = $this->_odmTable;

        if(isset($this->_odmValues[$table][$field]))
        {
            return $this->_odmValues[$table][$field];
        }

        return false;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get all outputs.
     * 
     * @return array
     */
    public function output()
    {
        if(isset($this->_odmErrors[$this->_odmTable]))
        {
            return $this->_odmErrors[$this->_odmTable];
        }

        return;
    }

    // --------------------------------------------------------------------

    /**
     * Get all error messages in array format.
     * 
     * @return array
     */
    public function messages()
    {
        $table = $this->_odmTable;

        if(isset($this->_odmErrors[$table]['messages']))
        {
            return $this->_odmErrors[$table]['messages'];
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid error message.
     * 
     * @param  string $key    message key
     * @param  string $prefix
     * @param  string $suffix
     * @return mixed
     */
    public function getMessage($key = 'errorMessage', $prefix = '', $suffix = '')
    {
        $table = $this->_odmTable;

        if(isset($this->_odmErrors[$table]['messages'][$key]))
        {
            if(in_array($key, array('error','errorMessage','success')))
            {
                $form     = Form::getFormConfig();
                $template = empty($prefix) ? $form['errors'][$key] : $prefix.'%s'.$suffix;

                return sprintf($template, $this->_odmErrors[$table]['messages'][$key]);
            }

            return $this->_odmErrors[$table]['messages'][$key];
        }

        return false;
    }

    // --------------------------------------------------------------------
   
    /**
    * Get all validaton errors from valid model.
    *
    * @return array
    */
    public function errors()
    {
        $table = $this->_odmTable;
        
        if(isset($this->_odmErrors[$table]['errors']))
        {
            return $this->_odmErrors[$table]['errors'];
        }

        return false;
    }
    
    // --------------------------------------------------------------------

    /**
     * Get selected error.
     * 
     * @param  string $field
     * @return string | boolean
     */
    public function getError($field)
    {
        $table = $this->_odmTable;

        if(isset($this->_odmErrors[$table]['errors'][$field]))
        {
            return $this->_odmErrors[$table]['errors'][$field];
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Odm error for field.
    *
    * @param string $key or $field
    */
    public function setError($key, $error)
    {
        $this->_odmErrors[$this->_odmTable]['errors'][$key] = $error;

        if(isset($this->_odmSchema->{$key})) // set a validation error.
        {
            $validator = $this->_odmValidator;
            $validator->_field_data[$key]['error'] = $error;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     */
    public function setNotice($message)
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;

        $sess->setFlash('_odmNotice_'.$this->_odmTable, $message);
    }

    // --------------------------------------------------------------------

    /**
     * Get notification message you set it before.
     * 
     * @return string notification
     */
    public function getNotice()
    {
        $Class = getComponent('sess');
        $sess  = (isset(getInstance()->sess)) ? getInstance()->sess : new $Class;

        $form     = Form::getFormConfig();
        $errorKey = ($this->messages('success') == 1) ? 'success' : 'errorMessage';
        $notice   = $sess->getFlash('_odmNotice_'.$this->_odmTable);

        if( ! empty($notice))
        {
            return sprintf($form['errors'][$errorKey], $notice);
        }

        return;
    }

    // --------------------------------------------------------------------

    /**
    * Set value for field.
    *
    * @param string $key or $field
    */
    public function setValue($key, $value)
    {
        $this->_odmValues[$this->_odmTable][$key] = $value;
    }

    // --------------------------------------------------------------------
    
     /**
     * Set Error Message
     *
     * Lets users set their own error messages on the fly.  Note:  The key
     * name has to match the  function name that it corresponds to.
     *
     * @access   public
     * @param    string $methodName callback function name
     * @param    string $message value
     * @return   string
     */
    public function setMessage($methodName, $message)
    {
        $this->_odmValidator->setMessage($methodName, $message);
    }

    // --------------------------------------------------------------------
    
    /**
     * Build Httpd GET friendly error query strings.
     * 
     * errors[user_password]=Wrond%20Password!&errors['user_email']=Wrong%20Email%20Address!
     * 
     * @return string
     */
    public function buildQueryErrors()
    {
        $table = $this->_odmTable;

        $http_query = array();
        if(isset($this->_odmErrors[$table]))
        {
            foreach($this->_odmErrors[$table] as $key => $val)
            {
                if(is_string($val))
                {
                    $http_query['errors'][$key] = $val;
                }
            }
        }
        
        return http_build_query($http_query);
    }

    // --------------------------------------------------------------------
    
    /**
     * Set Transaction Message
     * 
     * @param string $message
     */
    public function setFailure($message = 'We couldn\'t do operation at this time please try again.')
    {
        $errorMessage = (lingo($message) != '') ? lingo($message) : $message;

        $this->_odmErrors[$this->_odmTable]['messages'] = array(
        'success' => 0, 
        'errorKey' => 'failure',
        'errorCode'  => 12,
        'errorString' => $message,
        'errorMessage' => $errorMessage,
        );
    }

}

// END Odm Class

/* End of file odm.php */
/* Location: ./packages/odm/releases/0.0.1/odm.php */