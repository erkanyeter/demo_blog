<?php

/**
 * Schema Class
 *
 * @package       packages 
 * @subpackage    schema
 * @category      models
 * @link            
 */

Class Schema {

	public $tablename;  // Schema tablename
	public $modelName;	// Schema modelname
	public $driver;		// Schema driver object
    public $debug;      // Debug on / off
    public $debugOutput;  // Debug output string
    public $config;     // Schema config
    public $output;     // Set schema content

	/**
	 * Constructor
	 * 
	 * @param string $tablename
	 */
	public function __construct($tablename, $modelName, $dbObject = null)
	{
        $this->tablename   = strtolower($tablename);
        $this->modelName   = strtolower($modelName);
        $this->dbObject    = $dbObject;
        $this->debug       = false;
        $this->debugOutput = '';
        $this->config      = getConfig('schema');

		$schemaDriver = $this->getDriverName();

        if( ! packageExists(strtolower($schemaDriver)))  // Check schema driver is exists
        {
            throw new Exception('Schema driver '.$schemaDriver.' package not installed');
        }

		$this->driver = new $schemaDriver($this);  // Call valid schema driver

		logMe('debug', 'Schema Class Initialized');
	}

    // --------------------------------------------------------------------

	/**
	 * Read column schema from database
	 * 
	 * @return string schema string array data
	 */
	public function read()
	{
		return $this->driver->read();
	}

    // --------------------------------------------------------------------

    /**
     * Check table is exists
     * 
     * @return boolean
     */
    public function tableExists()
    {
	    if($this->driver->tableExists())
	    {
	        return true;
	    }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Create table
     * 
     * @return void
     */
    public function createTable()
    {
        $sql = $this->driver->create();    
        
        $this->runQuery();
        $this->displaySqlQueryHtml($sql); // display sql query form

        exit; // stop the current process
    }

    // --------------------------------------------------------------------

    /**
     * Check post action & run sql query
     * 
     * @return void
     */
    public function runQuery()
    {
        if(isset($_POST['query']))
        {
            $this->dbObject->query($_POST['query']);
            $this->redirect();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Show sql query to developer
     * 
     * @param  string $sql
     * @return string
     */
    public function displaySqlQueryForm($sql, $queryWarning = '',$disabled = false)
    {
        $sql = str_replace('"', "'", $sql);

        $html = '<h1>Run sql query for <i><u>'.strtolower($this->getTableName()).'</u></i> table</h1>';
        $html.= '<form action="'.$_SERVER['PHP_SELF'].'" method="POST" name="query_form" id="query_form" />';
            $html.= '<div id="query"><pre><textarea id="query" name="query" rows="8" cols="90">'.$sql.'</textarea></pre></div>';
            
            $value = 'Are you sure of this action ?';

            if( ! empty($queryWarning))
            {
                $value = $queryWarning;
            }

            $html.= '<input type="hidden" id="sure" name="sure" value="'.$value.'">';
            $html.= '<input type="hidden" id="confirm" name="confirm" value="no">';

            $disabledText = ($disabled) ? ' disabled="disabled" ' : '';

            $html.= '<input type="button" onclick="runQuery();" value="Run Query" '.$disabledText.' >';

            
        $html.= '</form>';

        return $html;
    }

    // --------------------------------------------------------------------
    
    /**
     * Display Html Output
     * 
     * @return string
     */
    public function displaySqlQueryHtml($sql)
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';

        $html.= $this->displaySqlQueryForm($sql);
        $html.= $this->writeScript();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;">* You see this screen because of <kbd>auto sync</kbd> feature enabled in <kbd>development</kbd> mode, you can configure it from your config file. Don\'t forget to close it in <kbd>production</kbd> mode.</p>';
        $html.= "\n</body>";
        $html.= "\n</html>";

        echo $html;
    }
    
    // --------------------------------------------------------------------

    /**
     * Sync table ( Sync with Database Schema )
     *
     * @return void
     */
    public function syncTable()
    {
        echo __FUNCTION__.'<br>';
        $this->driver->sync();
    }

    // --------------------------------------------------------------------

    /**
     * Write schema content to file
     * 
     * @param  string $fileContent schema file content
     * @param  string $prefix schema column prefix
     * @return void
     */
    public function writeToFile($fileContent, $prefix = '')
    {
        echo __FUNCTION__.'<br>';

        $shmop = new \Shmop;

        if(file_exists($this->getPath()))
        {
            $memSchema = $shmop->get($this->tablename);
            
            if($memSchema !== null)
            {
                eval(unserialize($memSchema)); // Get current schema from memory to fast file write

                $variableName  = $this->tablename;
                $currentSchema = $$variableName;

                $shmop->delete($this->tablename);   // Delete memory segment
            } 
            else 
            {
                $currentSchema = getSchema($this->tablename); // Get current schema
            }
        }

        $prefix = (is_null($prefix)) ? $this->getModelName().'_' : $prefix;

        // We need this for first time schema creation
        if($this->config['use_column_prefix']) // replace the prefix if its enabled globally
        {
            $fileContent = str_replace("'$prefix", "'", $fileContent);
        }

        $colPrefix = "'colprefix' => '".$prefix."'";     // Add coll prefix automatically.

        if(isset($currentSchema['*']['colprefix']) AND empty($currentSchema['*']['colprefix'])) // if not exists colprefix in current schema do not add.
        {
            $colPrefix = "'colprefix' => ''"; // set to empty
        }

        if($fileContent != false AND ! empty($fileContent)) // Write schema content.
        {
            if( ! is_writable(APP .'schemas'. DS))
            {
                throw new Exception("app/schemas/ path is not writable. Please give write permission to this folder.
                <pre>+ app\n+ <b>schemas</b>\n\t*.php\n+ public</pre>");
            }

            if(file_exists($this->getPath())) // remove current file if it exists.
            {
                unlink($this->getPath());
            }
           
            // unlink($this->getPath());
            
            $content = str_replace(
                array('{schemaName}','{filename}','{content}','{colprefix}'),
                array('$'.$this->getTableName(), $this->getTableName(). EXT, $fileContent, $colPrefix),
                file_get_contents(APP .'templates'. DS .'newschema.tpl')
            );

            // Write to Shared Memory for fast development
             
            $shmop->set($this->tablename, serialize($content));

            /*
            $readSchema = $shmop->get($this->tablename);

            if($readSchema == null)
            {
                $shmop->set($this->tablename, serialize($content));
            } 
            else // We need to first remove the key to prevent to "Couldn't create shared memory segment" error.
            {
                // $shmop->delete($this->tablename);
                $shmop->set($this->tablename, serialize($content));
            }
            */
           
            $content = "<?php \n".$content;

            if ($fp = fopen($this->getPath(), 'ab')) // Create New Schema If Not Exists.
            {
                flock($fp, LOCK_EX);    
                fwrite($fp, $content);
                flock($fp, LOCK_UN);
                fclose($fp);

                chmod($this->getPath(), 0777);

                logMe('debug', 'New Schema '.$this->getTableName().' Created');
            }
           
            $this->redirect(); // redirect to user current page
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * Get schema prefix
     * 
     * @return string
     */
    public function getPrefix()
    {
        $currentSchema = getSchema($this->tablename);
        unset($currentSchema['*']);

        $currentSchema = array_keys($currentSchema);
        $prefix = $this->getModelName().'_';

        if(isset($currentSchema[0]) AND strpos($currentSchema[0], $prefix) === 0)
        {
            return ''; // no prefix
        }

        return $prefix;
    }

    // --------------------------------------------------------------------

    /**
     * Get path of the schema
     * 
     * @return string
     */
    public function getPath()
    {
		return APP .'schemas'. DS .$this->tablename. EXT;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid tablename
     * 
     * @return string
     */
    public function getTableName()
    {
    	return $this->tablename;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid modelname
     * 
     * @return string
     */
    public function getModelName()
    {
    	return $this->modelName;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid database object
     * 
     * @return object
     */
    public function getDbObject()
    {
    	return $this->dbObject;
    }

    // --------------------------------------------------------------------

    /**
     * Get the schema driver name
     * 
     * @return string
     */
    public function getDriverName()
    {
        $dbConfig = getConfig('database');
        $exp = explode('_', get_class($dbConfig[Db::$var]));

        return 'Schema_'.ucfirst($exp[1]);
    }

    // --------------------------------------------------------------------

    /**
     * Redirect to current page
     * 
     * @return void
     */
    public function redirect()
    {
        $url = new \Url;

        if($this->debug == false)
        {
            $url->redirect(getInstance()->uri->uriString());
        }
    }

    // --------------------------------------------------------------------

    /**
     * Write javascript
     * 
     * @return string
     */
    public function writeScript()
    {
        return "<script type=\"text/javascript\" />
        function removeKey(columnKey, columnType, method){
            var command;
            command = columnKey + '|' + columnType + '|' + method;
            <!-- alert(command); -->
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'removeKey';
            document.forms['sync_table'].submit();
        }
        function addKey(columnKey, columnType, method){
            var command;
            command = columnKey + '|' + columnType + '|' + method;
            <!-- alert(command); -->
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'addKey';
            document.forms['sync_table'].submit();
        }
        function removeType(columnKey, columnType, method, isNew){
            var command;
            if(typeof isNew == \"undefined\"){
                command = columnKey + '|' + columnType + '|' + method;
            } else {
                command = columnKey + '|' + columnType + '|' + method + '|' + isNew;
            }
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'removeType';
            document.forms['sync_table'].submit();
            return false;
        }
        function addType(columnKey, columnType, method, isNew){
            var command;
            if(typeof isNew == \"undefined\"){
                command = columnKey + '|' + columnType + '|' + method;
            } else {
                command = columnKey + '|' + columnType + '|' + method + '|' + isNew;
            }
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'addType';
            document.forms['sync_table'].submit();
            return false;
        }
        function runQuery(){
            var conf = confirm(document.getElementById('sure').value);
            if (conf == true) {
                var query = document.getElementById('query').innerHTML;
                document.forms['query_form'].submit();
            }   
        }
        </script>";
    }

    // --------------------------------------------------------------------
    
    /**
     * Write css output
     * 
     * @return string
     */
    public function writeCss()
    {  
        global $packages;

        $css_file = PACKAGES .'schema'. DS .'releases'. DS .$packages['dependencies']['schema']['version']. DS .'src'. DS .'schema_sync.css';

        $css = '<style type="text/css">';
        $css.= file_get_contents($css_file);
        $css.= "</style>";

        return $css;
    }

    // --------------------------------------------------------------------

    /**
     * Set output for debugging
     * 
     * @param string $str debug
     */
    public function setDebugOutput($str)
    {
        $this->debugOutput.= $str.'<br />';
    }

    // --------------------------------------------------------------------

    /**
     * Return to debug string
     * 
     * @return string
     */
    public function getDebugOutput()
    {
        if(empty($this->debugOutput))
        {
            return;
        }

        return '<div class="debug"><h1>Debug Output</h1><pre>'.$this->debugOutput.'</pre></div>';
    }

    // --------------------------------------------------------------------

    public function setOutput($ruleString)
    {
        $this->output = $ruleString;
    }

    // --------------------------------------------------------------------

    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Debug On / Off to fix easily 
     * sync development issues
     * 
     * @return 
     */
    public function debug()
    {
        $this->debug = true;
    }
}

// END Schema class

/* End of file schema.php */
/* Location: ./packages/schema/releases/0.0.1/schema.php */