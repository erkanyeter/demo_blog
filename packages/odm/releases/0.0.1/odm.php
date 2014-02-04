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

    public $_odmSchema       = array();   // User schema
    public $_odmTable        = '';        // Table name ( model name )
    public $_odmConfig       = array();   // Odm configuration variable
    public $_odmMessages     = array();   // Validation errors and transactional ( Insert, Update, delete ) messages
    public $_odmValues       = array();   // Filtered safe values
    public $_odmValidation   = false;     // If form validation success we set it to true
    public $_odmValidator;
    public $_odmFormTemplate = 'default'; // Default form template defined in app/config/form.php
    public $_odmColumnJoins  = array();   // Do join foreach columns with related schema
    public $_odmUseSchema    = true;      // Use schema file as default

    public $post;                         // Post package object
    public $form;                         // Form package object
    
    // --------------------------------------------------------------------

    /**
     * Constructor
     * 
     * @param mixed $schemaArray  array or booelan type
     * @param object $dbObject
     */
    public function __construct($schemaArray, $dbObject)
    {
        $this->_odmConfig    = getConfig('odm');   // Initialize to Validator Object.
        $this->_odmValidator = getInstance()->validator; 

        $this->clear();            // Clear the validator.

        $this->_odmTable     = strtolower($schemaArray['*']['_tablename']);
        $this->_odmUseSchema = $schemaArray['*']['_use_schema'];
        unset($schemaArray['*']);  // Remove settings

        $this->_odmSchema = $schemaArray;

        if($this->_odmUseSchema == false)   // reset schema variable if its disabled.
        {
            $this->_odmSchema = array();
        }

        $this->form = (isset(getInstance()->form)) ? getInstance()->form : $odm['form'];
        $this->post = (isset(getInstance()->post)) ? getInstance()->post : $odm['post'];

        getInstance()->translator->load('odm');  // Load Odm package language file.

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
    public function isValid()
    {
        $validator = $this->_odmValidator;
        $table     = $this->_odmTable;

        $validator->set('_callback_object', $this);

        // ----------------------------------------
        // @ Column join
        // ----------------------------------------

        $joinSchemaFields = array();
        foreach($this->_odmColumnJoins as $tablename => $columns)
        {
            if( ! isset($this->_odmColumnJoins[$table])) // if column join exists merge schemas
            {
                $joinSchema = getSchema($tablename);
                unset($joinSchema['*']);

                foreach ($columns as $col)
                {
                    if(isset($this->data[$col]))
                    {
                        $joinSchemaFields[$col] = $joinSchema[$col];
                    }
                }
            }
        }

        $fields = array_merge($this->_odmSchema, $validator->_field_data);// Merge "Odm" and "Validator fields"
        $fields = array_merge($joinSchemaFields, $fields);  // Merge with $this->_odmSchema
        
        foreach($fields as $key => $val)
        {
            if(is_array($val))
            {
                if(isset($val['rules']) AND $val['rules'] != '')
                {
                    if(isset($this->data[$key])) // Set selected key to REQUEST.
                    {
                        $label = (isset($val['label'])) ? $val['label'] : '';   // $_REQUEST[$key] = $this->{$key};
                        $validator->setRules($key, $label, $val['rules']);
                    }
                }
            }
        }
        
        if($validator->isValid())   // Run the validation
        {
            foreach($fields as $key => $val)  // Set filtered values
            {
                // Using schema "false" means Form Model validation
                // schema "true" means Odm Validation

                if($this->_odmUseSchema == false OR isset($this->data[$key]))  // Set filtered values.
                {
                    if(isset($val['rules']) AND $val['rules'] != '') // If we have rules
                    {
                        $this->_odmValues[$table][$key] = $this->_setValue($key, (isset($this->data[$key])) ? $this->data[$key] : $this->post->get($key));  
                    }
                }
            }
            
            if($this->_odmSchema == false) // If Model Trait save function not used we need send back success for "Form Model Ajax" Tutorial.
            {                              
                $this->_buildSuccessMessage('send');
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
                   $error = $validator->getErrors($key, null, null);

                   if( ! empty($error))
                   {
                       $this->_odmMessages[$table]['errors'][$key] = $error;
                   }
                }

                //----------------------------
                
                $string = (isset($validator->_error_messages['message'])) ? $validator->_error_messages['message'] : 'There are some errors in the form fields.';

                //----------------------------

                // We need do append to array data otherwise $this->setMessage(); function
                // does not work, because of it reset all array wrong way ---> $this->_odmMessages[$this->_odmTable]['messages'] = array()

                $this->_odmMessages[$table]['messages']['success']    = 0;
                $this->_odmMessages[$table]['messages']['key']        = $this->_odmConfig['validation_error_key'];
                $this->_odmMessages[$table]['messages']['code']       = $this->_odmConfig['validation_error_code'];
                $this->_odmMessages[$table]['messages']['string']     = $string;
                $this->_odmMessages[$table]['messages']['translated'] = translate($string);
                $this->_odmMessages[$table]['messages']['message']    = sprintf($this->_odmConfig['notifications']['errorMessage'], translate($string));

                //----------------------------

                // Using schema "false" means Form Model validation
                // schema "true" means Odm Validation
                 
                if($this->_odmUseSchema == false OR isset($this->data[$key])) // Set filtered values.
                {
                    if(isset($val['rules']) AND $val['rules'] != '') // If we have rules
                    {
                        $this->_odmValues[$table][$key] = $this->_setValue($key, (isset($this->data[$key])) ? $this->data[$key] : $this->post->get($key));    
                    }
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

        $this->_odmSchema       = null;     // Schema object.
        $this->_odmTable        = '';       // Tablename.
        $this->_odmMessages     = array();  // Validation errors.
        $this->_odmValues       = array();  // Filtered safe values.
        $this->_odmFormTemplate = 'default'; // Form template.
        $this->_odmColumnJoins  = array(); 
    }    

    // --------------------------------------------------------------------
    
    /**
    * Return filtered validation values for current model.
    * 
    * @param string $field return to filtered one item's value.
    * @return array
    */
    public function getValues()
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
    public function getOutput()
    {
        if(isset($this->_odmMessages[$this->_odmTable]))
        {
            return $this->_odmMessages[$this->_odmTable];
        }

        return array();
    }

    // --------------------------------------------------------------------
    
    /**
     * WARNING !! Gives unsecure data !!!! 
     * be carefull when you use the all output.
     * 
     * returns to all outputs of the post values.
     * 
     * @return array
     */
    public function getAllOutput()
    {
        return array_merge($this->getOutput(), array('values' => $this->getValues()));
    }

    // --------------------------------------------------------------------

    /**
     * Customize odm outputs
     * 
     * @param string $key
     * @param mixed $val
     */
    public function setOutput($key, $val)
    {
        $this->_odmMessages[$this->_odmTable][$key] = $val;
    }

    // --------------------------------------------------------------------

    /**
     * Get all error messages in array format.
     * 
     * @return array
     */
    public function getMessages()
    {
        $table = $this->_odmTable;

        if(isset($this->_odmMessages[$table]['messages']))
        {
            return $this->_odmMessages[$table]['messages'];
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
    public function getMessage($key = 'message')
    {
        $table = $this->_odmTable;

        if(isset($this->_odmMessages[$table]['messages'][$key]))
        {
            return $this->_odmMessages[$table]['messages'][$key];
        }

        return;
    }

    // --------------------------------------------------------------------
   
    /**
    * Get all validaton errors from valid model.
    *
    * @return array
    */
    public function getErrors()
    {
        $table = $this->_odmTable;
        
        if(isset($this->_odmMessages[$table]['errors']))
        {
            return $this->_odmMessages[$table]['errors'];
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

        if(isset($this->_odmMessages[$table]['errors'][$field]))
        {
            return $this->_odmMessages[$table]['errors'][$field];
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Odm error for valid field.
    *
    * @param string $key or $field
    */
    public function setError($key, $error)
    {
        $this->_odmMessages[$this->_odmTable]['errors'][$key] = $error;
        $this->_odmValidation = false; // set validation to false.

        if(isset($this->_odmSchema[$key])) // set a validation error.
        {
            $validator = $this->_odmValidator;
            $validator->_field_data[$key]['error'] = $error;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Odm  message
    *
    * @param string $key or $field
    */
    public function setMessage($key, $message)
    {
        if(strpos($key, 'callback_') === 0) // if user want to setMessage using Form Object
        {                                   // call the $form->setMessage() function because of we have same function in here.
            
            return getInstance()->validator->setMessage($key, $message);
        }

        $this->_odmMessages[$this->_odmTable]['messages'][$key] = $message;
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
        if(isset($this->_odmMessages[$table]))
        {
            foreach($this->_odmMessages[$table] as $key => $val)
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
    public function setFailure($e = '')
    {
        $this->_odmValidation = false; // set validation to false;

        $errorMessage = $errorString = $this->_odmConfig['failure_message'];

        if(is_object($e) AND (ENV == 'DEBUG' OR ENV == 'TEST'))
        {
            $errorMessage = $errorString = $e->getMessage();
        } 

        if(is_string($e))
        {
            $errorMessage = (hasTranslate($e)) ? translate($e) : $e; // Is Translated ?
        }

        $this->_odmMessages[$this->_odmTable]['messages'] = array(
            'success'    => 0, 
            'key'        => $this->_odmConfig['operation_failure_key'],
            'code'       => $this->_odmConfig['operation_failure_code'],
            'string'     => (is_string($e)) ? $e : $errorString,
            'translated' => $errorMessage,
            'message'    => sprintf($this->_odmConfig['notifications']['failureMessage'], $errorMessage),
        );
    }

    // --------------------------------------------------------------------

    /**
     * Get tablename
     * 
     * @return string
     */
    public function getTableName()
    {
        return $this->_odmTable;
    }

    // --------------------------------------------------------------------

    /**
     * Get current schema array
     * 
     * @return array
     */
    public function getSchema()
    {
        return $this->_odmSchema;
    }

    // --------------------------------------------------------------------

    /**
     * Get multiple schemas
     * if user did join to another table
     * We use this function in database crud 
     * set() method.
     * 
     * @return array
     */
    public function getMultiSchema()
    {
        $schemas[$this->getTableName()] = $this->getSchema();  // get current schema

        if(sizeof($this->_odmColumnJoins) > 0)
        {
            foreach($this->_odmColumnJoins as $tablename => $column)
            {
                $joinSchema = getSchema($tablename);
                unset($joinSchema['*']); // remove settings

                $schemas[$tablename] = $joinSchema;
            }
        }

        return $schemas;
    }

}

// END Odm Class

/* End of file odm.php */
/* Location: ./packages/odm/releases/0.0.1/odm.php */