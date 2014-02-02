<?php
namespace Odm\Src {

    trait Model_Trait
    {
        private $_modelMethods = array();
        private $_modelDefinedMethods = array('save','update','delete','remove','insert','replace','push','send');

        // --------------------------------------------------------------------

        /**
         * Create the model function.
         * 
         * @param  string $methodName  
         * @param  closure $methodCallable
         * @param  transaction $transaction on / off switch
         * @return void
         */
        public function func($methodName, $methodCallable, $transaction = null)
        {
            if( strpos($methodName, 'callback_') !== 0 AND ! in_array($methodName, $this->_modelDefinedMethods))
            {
                throw new \Exception('Method "'.$methodName.'()" not allowed in the model, available methods listed below 
                    <pre>'.implode("\n", $this->_modelDefinedMethods)."\n".'callback_</pre>');
            }

            $this->__assignObjects(); // Assign all objects of Contoller to Model.
            $this->__assignColumns(); // Render column names & detects the column join request to another schema.

            if ( ! is_callable($methodCallable)) // @todo throw new InvalidArgumentException
    	    {
                throw new \Exception('Model '.get_class().' error: Second param must be callable.');
            }
            
            $this->_modelMethods[$methodName] = \Closure::bind($methodCallable, $this, get_class());
        }

        // --------------------------------------------------------------------

        /**
         * Run the called model _modelMethods.
         * 
         * @param  string $methodName method name
         * @param  array  $args       method arguments
         * @return mixed  
         */
        public function __call($methodName, array $args)
        {
            $method = strtolower($methodName);

            if (isset($this->_modelMethods[$methodName]))
            {
                $return = call_user_func_array($this->_modelMethods[$methodName], $args);

                if($return) // Set Success Message
                {
                    $this->_buildSuccessMessage($method);

                    // $this->clear();  
                    // This occurs an error in callback data , no need clear the validator Object.
                    // User have to manually do it.
                }

                return $return;
            }

            // @todo throw new RunTimeException
            throw new \Exception('Model '.get_class().' error: There is no method "'.$methodName.'()" to call.');
        }

        // --------------------------------------------------------------------

        /**
         * Get all defined methods
         * 
         * @return array
         */
        public function getAllMethods()
        {
            return array_keys($this->_modelMethods);
        }

        // --------------------------------------------------------------------

        /**
         * Build Success Message
         * 
         * @return void
         */
        private function _buildSuccessMessage($method)
        {
            $form   = \Form::getFormConfig();
            $string = $this->_odmConfig['success_message'];
            
            // We need do append to array data otherwise $this->setMessage(); function
            // does not work, because of it reset all array wrong way ---> $this->_odmMessages[$this->_odmTable]['messages'] = array()     

            $this->_odmMessages[$this->_odmTable]['messages']['success']    = 1;
            $this->_odmMessages[$this->_odmTable]['messages']['key']        = $method;
            $this->_odmMessages[$this->_odmTable]['messages']['code']       = $string;
            $this->_odmMessages[$this->_odmTable]['messages']['translated'] = translate($string);
            $this->_odmMessages[$this->_odmTable]['messages']['message']    = sprintf($form['notifications']['successMessage'], translate($string));
        }

        // --------------------------------------------------------------------

        /**
         * Assign all objects to current
         * Model.
         * 
         * @return void
         */
        private function __assignObjects()
        {
            $modelKey = strtolower(get_class());

            foreach(get_object_vars(getInstance()) as $k => $v)  // Get object variables
            {
                if(is_object($v) AND $k != $modelKey AND $k != \Db::$var) // Do not assign again reserved variables
                {
                    getInstance()->$modelKey->{$k} = getInstance()->$k;
                }
            }
        }

    }
}

// END Trait

/* End of file model_trait.php */
/* Location: ./packages/odm/releases/0.0.1/src/model_trait.php */