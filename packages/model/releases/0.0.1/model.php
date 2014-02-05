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
     * @param array | string | null | boolean $schemaOrTable Schema Object 
     * @param string $dbVar database object variable
     */
    public function __construct($modelName, $schemaOrTable = '', $dbVar = 'db')
    {
        global $packages, $config;

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
        else // if boolean
        {
            $tablename = strtolower($modelName); // If schema and table not provided use modelname as tablename.
        }

        //------------- Build Schema & Get Schema array ------------------//

        if($schemaOrTable === '' OR is_string($schemaOrTable))  // If schema provided as string , we understand its tablename ?
        {
            // ** Auto sync enabled in "debug" mode.
            
            if($config['model_auto_sync']) // Create new schema if not exists.
            {
                $requestUri = base64_encode(getInstance()->uri->getRequestUri());
                
                $postData   = 'false';
                if(sizeof($_POST) > 0) // Don't send empty post data
                {
                    $postData = base64_encode(serialize($_POST));
                }

                $task      = new Task;    // Get tablename from "sync form" if we have sync request otherwise use current tablename.
                $tablename = isset($_POST['lastCurrentSchema']) ? $_POST['lastCurrentSchema'] : $tablename ;
                $output    = $task->run('sync/index/'.$tablename.'/'.$modelName.'/'.$dbVar.'/'.$requestUri.'/'.$postData, true);

                // print_r($_POST); exit; // debug On / Off

                if( ! empty($output))
                {
                    echo $output;
                    exit;
                }
                
                if(isset($_POST['lastCurrentPage']))  // Do redirect while post array is empty, in cli mode we need do redirect to current page.
                {
                    $url = new Url;
                    $url->redirect(urldecode($_POST['lastCurrentPage']));
                }

                logMe('info', 'Auto sync enabled on your config file you need to close it in production mode');
            }

            $schemaArray = getSchema($tablename); // Get schema configuration.
            
            $schemaArray['*']['_tablename']  = $tablename; 
            $schemaArray['*']['_use_schema'] = true;
        } 
        
        if(is_array($schemaOrTable))
        {
            $schemaArray['*']['_tablename']  = $tablename;  // Set tablename to schemaArray settings, we need it in the user's model class.
            $schemaArray['*']['_use_schema'] = true;
        } 
        elseif(is_bool($schemaOrTable))
        {
            $schemaArray['*']['_tablename']  = $tablename; 
            $schemaArray['*']['_use_schema'] = false;       // Send boolean to Odm object
        }

        //---------------------- Build Model Class -----------------------//

        if( ! class_exists('AppModel_'.$modelName, false))  // Create the Model file on the fly with magic methods.
        {
            eval('Class AppModel_'.$modelName.' extends Odm { 
                use Odm\Src\Model_Trait;
                function __construct($schemaArray, $dbObject) {
                    parent::__construct($schemaArray, $dbObject); 
                }
                function __set($k, $v){
                    if( ! is_object($v) AND $k != "data"){  // Only data variable allowed !
                        throw new Exception(sprintf("Only \"data\" variable allowed in application model class.
                            <pre>this->%s->data = \'\';</pre>", \''.$modelName.'\'));    
                    }
                    $this->$k = $v;
                }
                function __assignColumns(){
                    // We need to check data type for web service requests.
                    if(isset($this->data) AND is_array($this->data) AND count($this->data) > 0){
                        foreach($this->data as $k => $v){
                            if(strpos($k, ".") > 0){     // Column join support
                                unset($this->data[$k]);  // remove join "." data
                                $exp    = explode(".", $k);
                                $table  = $exp[0];
                                $k      = $exp[1]; // pure column name
                                $this->_odmColumnJoins[$table][] = $k; // keep joins in our mind
                            }
                            $this->data[$k] = $v;  // rebuild data
                        }
                    }
                }
            }');
        }

        //--------------- Call the Model Class Magically -----------------------//

        $appModelName = 'AppModel_'.$modelName;

        $modelKey = strtolower($modelName);
        getInstance()->{$modelKey} = new $appModelName($schemaArray, $dbObject); // Model always must create new instance.
       
        logMe('debug', "Application Model $modelName Initialized");
    }

}

// END Model Class

/* End of file model.php */
/* Location: ./packages/model/releases/0.0.1/model.php */