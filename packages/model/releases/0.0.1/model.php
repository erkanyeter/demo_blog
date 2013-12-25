<?php

/**
 * Model Class.
 *
 * @package       packages 
 * @subpackage    model
 * @category      models
 * @link            
 */                    

Class Model {

    /**
     * Create Models on the fly.
     * 
     * @param string  $modelName  Model Name
     * @param array | string | null $schemaOrTable Schema Object 
     * @param string $dbVar database object variable
     */
    public function __construct($modelName, $schemaOrTable = '', $dbVar = 'db')
    {
        global $packages;

        //------------------- Connect to Database -----------------------//
        
        new Db($dbVar);
        $dbObject = getInstance()->{$dbVar};

        //------------------- Include Form Class -----------------------//

        if( ! isset(getInstance()->form))  // Initialize to Form Validator Class 
        {
            new Form;
        }

        //------------------ Include Model Trait -----------------------//

        if( ! trait_exists('Odm\Src\Model_Trait')) 
        {
            require (PACKAGES .'odm'. DS .'releases'. DS .$packages['dependencies']['odm']['version']. DS .'src'. DS .'trait'. EXT);
        }

        //------------------- Detect tablename -----------------------//

        if( ! empty($schemaOrTable) AND is_string($schemaOrTable))  // Detect tablename.
        {
            $tablename = strtolower($schemaOrTable);   // If tablename provided as string.
        } 
        elseif(is_array($schemaOrTable))  // If schema provided as array.
        {
            $tablename = strtolower(key($schemaOrTable));
        } 
        else
        {
            $tablename = strtolower($modelName); // If schema and table not provided use modelname as tablename.
        }

        //------------- Build Schema & Get Schema array ------------------//

        if($schemaOrTable == '' OR is_string($schemaOrTable))  // If schema provided as string , we understand its tablename ?
        {
            // ** Auto sync enabled in "debug" mode.

            if(config('model_auto_sync')) // Create new schema if not exists.
            {
                $schema     = new Schema($tablename, $modelName, $dbObject);
                $schemaPath = $schema->getPath();

                if( ! file_exists($schemaPath)) // If schema file exists ?
                {
                    $schema->writeToFile($schema->read(), null);  // Write content to schema file
                } 
                else 
                {
                    // Check any changes in the column prefix
                    // If any changes exists in the colprefix remove the valid schema and create new one
                    //---------------------------------------------

                    $shmop = new Shmop;  // Read from shared memory for fast development
                    $memSchema = $shmop->get($tablename);
                    
                    if($memSchema !== null)
                    {
                        // eval('$'.$tablename.' = '.unserialize($memSchema)); // Get current schema from memory to fast file write

                        $currentSchema = unserialize($memSchema);

                        $variableName  = $tablename;
                        // $currentSchema = $$variableName;

                        $fileSchema    = getSchema($tablename);

                        // Sync Memory schema with fileSchema
                        
                        if(strlen(serialize($currentSchema)) != strlen(serialize($fileSchema)))
                        {
                            $shmop->delete($tablename);
                            $shmop->set($tablename, serialize($fileSchema));

                            // $currentSchema = $fileSchema;
                        } 
                    } 
                    else 
                    {
                        $currentSchema = getSchema($tablename);
                    }

                    if(isset($currentSchema['*']['colprefix']) AND $schema->getPrefix() != $currentSchema['*']['colprefix'])
                    {
                        unlink($schema->getPath()); // remove current schema & then rebuild it with new prefix

                        $schema->writeToFile($schema->read(), $currentSchema['*']['colprefix']); // recreate it with new prefix
                    } 
                    else  // Check database table
                    {
                        if( ! $schema->tableExists())  // Check table exits.
                        {
                            $schema->createTable(); // Create sql query & run
                        }
                        else 
                        {
                            $schema->syncTable();  // Display sync table
                        }
                    }
                }
            
                logMe('info', 'Auto sync enabled on your config file you need to close it in production mode');
            }

            $schemaArray = getSchema($tablename); // Get schema configuration.
        } 
        else 
        {
            $schemaArray = (array)$schemaOrTable;
        }

        $schemaArray['*']['_tablename'] = $tablename;  // Set tablename to schemaArray settings, we need it the user's model class.

        //---------------------- Build Model Class -----------------------//
        
        if( ! class_exists($modelName, false))  // Create the Model file on the fly with magic methods.
        {
            eval('Class '.$modelName.' extends Odm { 
                use Odm\Src\Model_Trait; 
                public $_properties = array();

                function __construct($schemaArray, $dbObject) { 
                    $this->_schemaArray = $schemaArray;
                    parent::__construct($schemaArray, $dbObject); 
                } 
                function __set($k, $v){   // Don\'t Db store object into properties variable.
                    if($k == "_schemaArray"){
                        $this->_schemaArray = $v;
                    }
                    if(is_object($v) AND ! isset($this->$k)){
                        $this->$k = $v;  // getInstance object variables  db,config,router,uri,output,lingo ...
                    } elseif(isset($this->_schemaArray[$k])) {
                        $this->_properties[$k] = $v;
                    }
                }
                function __get($k){
                    if(isset($this->_properties[$k])){
                        return $this->_properties[$k];
                    }
                }
            }');
        }

        //--------------- Call the Model Class Magically -----------------------//

        $modelKey = strtolower($modelName);
        getInstance()->{$modelKey} = new $modelName($schemaArray, $dbObject); // Model always must create new instance.

        //--------------- Assign all libraries to model  -----------------------//

        /**
         * So we can use $this->config->method() in model classes.
         * 
         * @var object
         */
        foreach(get_object_vars(getInstance()) as $k => $v)  // Get object variables
        {
            if($k != '_controllerAppMethods' AND $k != $modelKey AND $k != Db::$var) // Do not assign again reserved variables
            {
                getInstance()->$modelKey->{$k} = getInstance()->$k;
            }
        }
       
        logMe('debug', "Model $modelName Initialized");
    }

}

// END Model Class

/* End of file model.php */
/* Location: ./packages/model/releases/0.0.1/model.php */