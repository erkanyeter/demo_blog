<?php
namespace Odm\Src {

    trait Model_Trait
    {
        private $_modelMethods = array();
        private $_modelDefinedMethods = array('save','update','delete','remove','insert','replace','put');

        /**
         * Create the function.
         * 
         * @param  string $methodName  
         * @param  closure $methodCallable
         * @return void
         */
        public function func($methodName, $methodCallable)
        {
            if ( ! is_callable($methodCallable))
    	    {
                // @todo throw new InvalidArgumentException
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
            $db = $this->_odmDbVar;
            $method = strtolower($methodName);

            if (isset($this->_modelMethods[$methodName]))
            {
                $return = call_user_func_array($this->_modelMethods[$methodName], $args);

                if($return) // Set Success Message
                {
                    $errorString = 'Operation succesfull.';

                    if($method == 'delete' || $method == 'remove')
                    {
                        $errorString = 'Data '.$method.'d succesfully.';
                    } 
                    
                    if(in_array($method, array('save','update','insert','replace','put')))
                    {
                        $errorString = 'Data saved succesfully.';
                    }

                    $this->_odmErrors[$this->_odmTable]['messages'] = array(
                    'success' => 1, 
                    'errorKey' => $method.'Success',
                    'errorCode'  => 11,
                    'errorString' => $errorString,
                    'errorMessage' => lingo($errorString)
                    );

                    // $this->clear();  
                    // This occurs an error in callback data , no need clear the validator Object.
                    // User manually do it.
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

    }
}

// END Trait

/* End of file trait.php */
/* Location: ./packages/odm/releases/0.0.1/trait.php */