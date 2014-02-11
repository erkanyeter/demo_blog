<?php

/**
 * Setup Wizard Class
 *
 * @package       packages
 * @subpackage    setup_wizard
 * @category      
 * @link
 */


Class Setup_Wizard {

    private $_setExtension = array();
    private $_input        = array();
    private $_ini_file;
    private $_title;
    private $_db_path;
    private $_dbName;
    private $_db;

    protected $ini_line = array();
    protected $wizard; // for model

    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        if( ! isset(getInstance()->setup_wizard))
        {
            getInstance()->setup_wizard = $this; // Available it in the contoller $this->setup_wizard->method();
        }

        $this->form = new \Form;

        
        logMe('debug', "Setup Wizard Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Set extension
     * 
     * @param array $driver
     */
    public function setExtension($driver)
    {
        array_push($this->_setExtension, $driver);
    }

    // --------------------------------------------------------------------

    /**
     * Extension control for requirements driver
     * 
     * @return html $extension
     */
    private function _extensionControl()
    {
        $extension = '';
        foreach($this->_setExtension as $driver)
        {
            if( ! extension_loaded($driver))
            {
                $extension.= '<tr><td class="newColumn">'.$driver.'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td id="driverError" class="columnTypeError">Not installed<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
            else
            {
                $extension.= '<tr><td class="newColumn">'.$driver.'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td class="green">Pass<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
        }

        return $extension;
    }

    // --------------------------------------------------------------------

    /**
     * Create SQL
     * 
     * @return boolean true or exception
     */
    private function _createSQL()
    {
        $sql    = $this->post->get('sql');
        $result = $this->_db->exec($sql);

        if($result > 0)
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------


    /**
     * Get SQL output
     * 
     * @return string
     */
    public function getSQL()
    {
        return $sql = file_get_contents($this->_db_path);
    }

    // --------------------------------------------------------------------

    /**
     * Set setup ini file
     * 
     * @return function _fileRewrite()
     */
    public function setIni($segment = 'demo_blog', $key = 'installed', $value = 1)
    {
        $ini_line = '['.$segment.'] '.$key.' = '.$value;

        return array_push($this->ini_line, $ini_line);
    }

    // --------------------------------------------------------------------

    /**
     * Write ini
     * 
     * @return string
     */
    public function writeIni()
    {
        print_r($this->ini_line);
        return $this->_fileRewrite($this->_ini_file, implode('',$this->ini_line));
    }

    // --------------------------------------------------------------------

    /**
     * Get ini content
     * 
     * @return array
     */
    public function getIni()
    {
        return parse_ini_file($this->getIniFile());
    }

    // --------------------------------------------------------------------

    /**
     * File rewrite
     * 
     * @param  string $fileName
     * @param  string $data
     * @return write setup.ini file
     */
    private function _fileRewrite($fileName, $data)
    {
        if( ! file_put_contents($fileName, $data, LOCK_EX))
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Set database and db path
     * 
     * @param  string $database
     * @param  string $db_path
     * @return string $this
     */
    public function setDatabase($database, $db_path)
    {
        $this->_path($db_path);
        $this->_dbName = $database;

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Set title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        return $this->_title = $title;
    }

    // --------------------------------------------------------------------

    /**
     * Set input
     * 
     * @param  string $name
     * @param  string $label
     * @return array
     */
    public function setInput($name, $label, $rule)
    {
        $input          = array();
        $input['name']  = $name;
        $input['rule']  = $rule;
        $input['label'] = $label;

        array_push($this->_input, $input);
    }

    // --------------------------------------------------------------------

    /**
     * Create input output html
     * 
     * @return html
     */
    private function _createInput()
    {
        $form = '';
        foreach($this->_input as $input)
        {
            if($input['name'] == 'password')
            {
                $form.= '<tr><td class="newColumn">'.$this->form->label($input['label']).'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td class="error">'.$this->form->error($input['name']).$this->form->password($input['name'],''," id='$input[name]'").'<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
            else
            {
                $form.= '<tr><td class="newColumn">'.$this->form->label($input['label']).'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td class="error">'.$this->form->error($input['name']).$this->form->input($input['name'],$this->form->setValue($input['name'])," id='$input[name]'").'<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
        }

        $form.= '<tr><td class="newColumn">'.$this->form->label('SQL Path').'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td class="error">'.$this->form->input('',$this->_db_path,'disabled').'<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';

        $form.= '<tr><td class="newColumn" colspan="2">'.$this->form->textarea('sql',$this->getSQL(),' rows="10" style="width:485px;"').'</tr>';


        return $form;
    }

    // --------------------------------------------------------------------

    /**
     * Run last function
     * 
     * @return function
     */
    public function run()
    {
        $parse = $this->getIni();
        
        if(isset($parse['installed']) AND  $parse['installed'] == 1)
        {
            return false;
        }

        $this->post = new \Post;

        if($this->post->get('submit_step2'))
        {
            new Model('wizard', false);

            $this->wizard = getInstance()->wizard;

            foreach($this->_input as $rule)
            {
                $this->form->setRules($rule['name'], $rule['label'], $rule['rule']);
            }

            if($this->wizard->isValid())
            {
                if( ! $this->_dbAccess())
                {
                    $this->wizard->setMessage('message','Wrong username or password');
                }
                elseif( ! $this->_dbControl())
                {
                    $this->wizard->setMessage('message',$this->_dbName.' database available');
                }
                elseif( ! $this->_createSQL())
                {
                    $this->wizard->setMessage('message','Database not installed');
                }
                elseif( ! $this->_createDbConfig())
                {
                    $this->wizard->setMessage('message','Database config file could not be created.');
                }
                elseif( ! $this->writeIni())
                {
                    $this->wizard->setMessage('message','Setup.ini file could not be written.');
                }
                else
                {
                    $url = new \Url;
                    $url->redirect();
                }
            }
        }
        
        if(count($this->_setExtension) > 0 AND count($this->_input) == 0)
        {
            // step1
            $this->extensionHtml();
        }
        elseif(count($this->_input) > 0)
        {
            // step2
            $this->formHtml();
        }

        exit;
    }

    // --------------------------------------------------------------------

    /**
     * Create db config file
     * 
     * @return [type] [description]
     */
    private function _createDbConfig()
    {
        $db_config = APP . 'config' . DS . 'debug' . DS . 'database.php';
        $data      = $this->_getDbConfig();

        return $this->_fileRewrite($db_config, $data);
    }

    // --------------------------------------------------------------------

    /**
     * Get db config
     * 
     * @return string
     */
    private function _getDbConfig()
    {
        return '<?php
/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Database Variables
|
*/
$database = array(

    \'db\' => new Pdo_Mysql(array(    // or new Mongo_Db;
        \'variable\' => \'db\',
        \'hostname\' => \''.$this->post->get("hostname").'\',
        \'username\' => \''.$this->post->get("username").'\',
        \'password\' => \''.$this->post->get("password").'\',
        \'database\' => \''.$this->_dbName.'\',
        \'driver\'   => \'\',   // optional
        \'prefix\'   => \'\',
        \'dbh_port\' => \'\',
        \'char_set\' => \'utf8\',
        \'dsn\'      => \'\',
        \'options\'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
        )),
    
);

/* End of file database.php */
/* Location: .app/config/debug/database.php */';

    }

    // --------------------------------------------------------------------

    /**
     * Db Access
     * 
     * @return boolean true or false
     */
    private function _dbAccess()
    {
        $hostname = $this->post->get('hostname');
        $username = $this->post->get('username');
        $password = $this->post->get('password');

        try{
            $this->_db = new PDO(
                           "mysql:host=$hostname",
                           $username,
                           $password
                        );
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            preg_match('/Access denied/',$e->getMessage(),$exception);
        }

        if(isset($exception) AND $exception[0] == 'Access denied')
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Db control
     * 
     * @return boolean true or false
     */
    private function _dbControl()
    {
        $dbs = $this->_db->query('SHOW DATABASES');

        while(($db = $dbs->fetchColumn( 0 )) !== false)
        {
            if($db == $this->_dbName)
            {
                return false;
            }
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Extension html step 1
     * 
     * @return html
     */
    protected function extensionHtml()
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';
        $html.= '<h1>'.$this->_title.'</h1>';
        $html.= '<div class="WizardStep active">Step 1</div> <div class="WizardStep">|</div> <div class="WizardStep">Step 2</div>';

        $html.= $this->form->open('/'.getInstance()->uri->getRequestUri(), array('method' => 'POST', 'name' => 'step1', 'id' => 'setup_wizard'));

        $html.= '<table class="modelTable">';
        $html.= '<tr>';
        $html.= '<th>Extension</th>';
        $html.= '<th>Status</th>';
        $html.= '</tr>';
        $html.= $this->_extensionControl();
        $html.='</tbody></table>';
        $html.= $this->form->submit('submit_step1','Next Step','id="submit_step1"');
        $html.= $this->form->submit('submit_step1','Next Step','id="submit_step11" style="display:none" disabled');

        $html.= $this->form->close();

        $html.= $this->writeScript();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;color:#006857;">* Please install above the requirements then click next. "Otherwise application will not  work correctly." </p>';
        $html.= "\n</body>";
        $html.= "\n</html>";

        echo $html;
    }

    // --------------------------------------------------------------------

    /**
     * Form html step 2
     * 
     * @return html
     */
    protected function formHtml()
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';
        $html.= '<h1>'.$this->_title.'</h1>';
        $html.= $this->form->getNotice(); 

        $html.= '<div class="WizardStep">Step 1</div> <div class="WizardStep">|</div> <div class="WizardStep active">Step 2</div>';
        
        ( ! empty($this->wizard) ? $html.= $this->wizard->getMessage() : '' );

        $html.= $this->form->open('/'.getInstance()->uri->getRequestUri(), array('method' => 'POST', 'name' => 'step2', 'id' => 'setup_wizard'));

        $html.= '<table class="modelTable">';
        $html.= '<tr>';
        $html.= '<th colspan="2">Database Configuration</th>';
        $html.= '</tr>';
        $html.= $this->_createInput();
        $html.='</tbody></table>';
        
        $html.= $this->form->submit('back','Back');
        $html.= $this->form->submit('submit_step2','Install');
        $html.= $this->form->close();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;color:#006857;">* Configure your database connection settings then click to install.</p>';
        $html.= "\n</body>";
        $html.= $this->writeScript();
        $html.= "\n</html>";

        echo $html;
    }

    // --------------------------------------------------------------------

    /**
     * Get ini file
     * 
     * @return string ini file
     */
    public function getIniFile()
    {
        return $this->_ini_file = DATA . 'cache' . DS . 'setup.ini';
    }

    // --------------------------------------------------------------------

    /**
     * Path for db file
     * 
     * @return string db_path
     */
    private function _path($file)
    {
        $this->_db_path = str_replace('\/', DS, $file);

        if( ! file_exists($this->_db_path))
        {
            throw new Exception($path." file not found");
        }

        return $this->_db_path;
    }

    // --------------------------------------------------------------------
    

    /**
     * Write javascript output
     * 
     * @return javascript
     */
    public function writeScript()
    {
        return '<script type="text/javascript">
                    var elm = document.getElementById("driverError");
                    if(elm.className == "columnTypeError"){
                        document.getElementById("submit_step1").style.display = "none";
                        document.getElementById("submit_step11").style.display = "block";
                    }
                </script>';
    }

    // --------------------------------------------------------------------

    /**
     * Write css output
     * 
     * @return string css
     */
    public function writeCss()
    {
        global $packages;

        $css_file = PACKAGES .'setup_wizard'. DS .'releases'. DS .$packages['dependencies']['setup_wizard']['version']. DS .'src'. DS .'setup_wizard.css';

        $css = '<style type="text/css">';
        $css.= file_get_contents($css_file);
        $css.= "</style>";

        return $css;
    }

}



/* End of file setup_wizard.php */
/* Location: ./packages/setup_wizard/releases/0.0.1/setup_wizard.php */