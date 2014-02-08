<?php

/**
 * Build Dynamic Html Forms
 * 
 * @author rabihsyw <rabihsyw@gmail.com>
 * @package       packages
 * @subpackage    uform
 * @category      form builder
 * 
 * @tutorial    Allowed funtions list : 
 *              input => $this->$method_name();
 *              input, 
 *              hidden, 
 *              radio, 
 *              checkbox, 
 *              dropdown, 
 *              submit, 
 *              password, 
 *              textarea
 */

Class Form_Builder
{
    private $_identifier;
    private $output;
    private $fieldsArr;
    private $rowNum   = 0;
    private $colNum   = 0;
    private $colNames = array();
    private $buildClosure;
    private $form;
    
    // multiforms
    private static $forms    = array();

    // --------------------------------------------------------------------
    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        $this->form = new Form;

        // set the new instance each time to the controller
        // otherwise must change the params to static
        getInstance()->form_builder = $this;  // Make available it in the controller.

        logMe('debug', 'Form Builder Class Initialized');

        $args = func_get_args();
        
        $this->buildClosure = $args[2];
        unset($args[2]);
        $this->output = "\t".call_user_func_array(array($this->form, 'open'), $args);
    }


    // --------------------------------------------------------------------

    /**
     * This function is used to handle some functions.
     * handle $this->form->input() functions
     * 
     * all the public functions in the class, must holds 'form-identifier as a parameter.'
     */
    public function __call($method, $arguments)
    {
        switch ($method)
        {
            /*
            Handling the external calls for the following functions.
            We override the behaviour of these functions in the constructor,
            while adding them externally in the Controller.
            */
            case 'input':
            case 'password':
            case 'hidden':
            case 'submit':
            case 'checkbox':
            case 'radio':
            case 'dropdown':
            case 'textarea':
            case 'multiselect':
            case 'captcha':
            {
                $colname = $arguments[0];
                $this->setColValue('field_name', $arguments[0]);
                $this->colNames[$this->rowNum][$this->colNum] = $colname; // set column name
                return array("method" => $method, "arguments" => $arguments);
                break;
            }
            case 'printForm':
            {
                $identifier = $arguments[0];
                if(empty(self::$forms[$identifier]))
                {
                    throw new Exception('Form Builder Error : trying to printForm for a not created form.');
                }

                return self::$forms[$identifier]->printForm();
            }
            
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * This function is used to add rows to the form
     */
    protected function addRow()
    {
        $this->rowNum++;
        $this->colNum = 0;
    }

    // --------------------------------------------------------------------

    /**
     * Adding new column to the form
     * 
     * @param $value : label' => '', input => array(), array("input" => array())
     */
    protected function addCol($data)
    {
        if ( ! array_key_exists('input', $data))  // for radios & checkboxes "input" isn't set directly in $arg
        {
            foreach ($data as $item)
            {
                if (isset($item['input']))
                {
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['input'][]     = $item['input'];
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['listLabel'][] = (isset($item['label'])) ? $item['label'] : ' ';
                }
            }
        }
        else // normal set
        {
            $this->setColValue('input', $data['input']);
        }

        if(array_key_exists('label', $data))
        {
            $this->setColValue('label', $data['label']);
        }

        if(array_key_exists('rules', $data))
        {
            $label = (isset($data['label'])) ? $data['label'] : ucfirst(strtolower($data['label']));
            $this->setColValue('rules', $data['rules']);
            // $this->setColValue('rules', $arg['rules']);
        }

        // increase column index in the columns array
        $this->colNum ++;
    }


    // --------------------------------------------------------------------
    /**
     * Get current column name
     * 
     * @return [type] [description]
     */
    protected function getColName()
    {
        return $this->colNames[$this->rowNum][$this->colNum];
    }

    // --------------------------------------------------------------------
    /**
     * Assign values to the columns
     * 
     * @param string $index index name of the array element
     * @param string $value value 
     */
    protected function setColValue($index, $value)
    {
        $this->fieldsArr[$this->rowNum]['columns'][$this->colNum][$index] = $value;
    }

    // --------------------------------------------------------------------
    /**
     * Prinnt 
     * @param  [type] $input [description]
     * @return [type]        [description]
     */

    protected function printInput($input)
    {
        if (is_array($input))
        {
            switch ($input['method'])
            {
                case 'input':
                case 'password':
                case 'hidden':
                case 'submit':
                case 'checkbox':
                case 'radio':
                case 'dropdown':
                case 'textarea':
                case 'multiselect':
                {
                    return call_user_func_array(array($this->form, $input['method']), $input['arguments']);
                    break;
                }
                case 'captcha':
                {
                    return $this->captchaBuild($input['arguments']);
                    break;
                }
            }
        }
        return;
    }

    // --------------------------------------------------------------------
    /**
     * Set position for label or input
     * 
     * $this->setPosition('label' , 'left');
     * $this->setPosition('input' , 'right');
     * $this->setPosition('error' , 'bottom');
     * 
     * @param string element name
     * @param string position
     */
    protected function setPosition($element, $position = 'left')
    {
        if (in_array($element, array('label', 'input', 'error')) AND in_array($position, array('left', 'top', 'right', 'center','bottom')))
        {
            $this->fieldsArr[$this->rowNum]['position'][$element] = $position;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Create the form, the main function.
     * 
     * @param  string  form Identifier
     */
    public function create($identifier)
    {
        $this->_identifier = $identifier;
        
        call_user_func_array(Closure::bind($this->buildClosure, $this, get_class()), array());
        
        self::$forms[$identifier] = clone $this;

        

        // $this->shiftingLocalParams($identifier,'create');

        $this->output    = '';
        $this->fieldsArr = '';
        $this->rowNum    = '';
        $this->colNum    = '';
        $this->colNames  = '';
    }

    // --------------------------------------------------------------------

    /**
     * Print the form HTML
     * 
     * @return string
     */
    protected function printForm()
    {
        // $this->shiftingLocalParams($identifier,'print');

        $out = "\n<div class='form-builder-div-wrapper'>\n";         // check the printout type "table or div"
        $out.= $this->output;

        if (is_array($this->fieldsArr))         // looping the fields array
        {
            foreach ($this->fieldsArr as $rowNum => $v)
            {
                $out .= "\n\t\t<div class='form-builder-row'>";  // printing a row "<div>"

                if (is_array($v))
                {
                    $columnsNum = count($v['columns']);     // get column grid class
                    $gridClass  = $this->calculateWidth($columnsNum);

                    foreach ($v['columns'] as $colNum => $v2)
                    {
                        $label = $error = $columnContent = '';
                        $addon_class = (isset($v['position']['input'])) ? "form-builder-ipos-" . $v['position']['input'] : "";

                        $out  .= "\n\t\t\t<div class='form-builder-column " . array_shift($gridClass) . " $addon_class' >";  // add the column TD
                        $label = "\n\t\t\t\t".$this->printLabel($rowNum, $colNum);
                        
                        $columnContent = "\n\t\t\t\t\t".$this->printColumnContent($rowNum, $colNum);  // retrive column content.



                        $error = $this->form->error($v2['field_name'], "<div class='form-builder-error' >", "</div>");

                        $inputWrapper = "\n\t\t\t\t<div class='form-builder-field-wrapper'>\n";
                        
                        // error position top, bottom
                        if( ! empty($v['position']['error']) )
                        {
                            if($v['position']['error'] == 'bottom')
                            {
                                $inputWrapper .= $columnContent;
                                $inputWrapper .= "<div class='clear' ></div>";
                                $inputWrapper .= $error;
                            }
                            elseif($v['position']['error'] == 'top')
                            {
                                $inputWrapper .= $error;
                                $inputWrapper .= "<div class='clear' ></div>";
                                $inputWrapper .= $columnContent;
                            }
                        }else{
                            $inputWrapper .= $columnContent;
                            $inputWrapper .= "<div class='clear' ></div>";
                            $inputWrapper .= $error;
                        }

                        $inputWrapper .= "\n\t\t\t\t</div>";

                        $out .= $label . $inputWrapper;
                        $out .= "\n\t\t\t</div>"; // close the div tags
                    }
                }

                $out .= "\n\t\t<div class='clear' ></div></div>\n";  // close the "form-builder-row" div
            }
        }

        $out .= "\t".$this->form->close()."\n";
        $out .= "</div>\n";

        return $out.'<div class="clear" ></div>';
    }

    // --------------------------------------------------------------------
    /**
     * This function determines which 'css classes'
     * will be assigned to each column
     * 
     * @return array
     */
    protected function calculateWidth($columnsNum)
    {
        $gridSys = array(
            1 => array(10),
            2 => array(5, 5),
            3 => array(4, 3, 3),
            4 => array(3, 3, 3, 1),
            5 => array(2, 2, 2, 2, 2),
            6 => array(2, 2, 2, 2, 1, 1),
            7 => array(2, 2, 2, 1, 1, 1, 1),
            8 => array(2, 2, 1, 1, 1, 1, 1, 1),
            9 => array(2, 1, 1, 1, 1, 1, 1, 1, 1),
            10 => array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
        );

        $x = array();
        if (in_array($columnsNum, array_keys($gridSys))) // check if the count of columns in-
        {                                                // -the same row is larger than the accepted count.
            array_walk($gridSys[$columnsNum], function($value, $key) use(&$x) {
                $x[] = 'form-builder-grid-' . $value;
            });
        }
        else
        {
            for ($i = 0; $i < $columnsNum; $i++)
            {
                $x[] = 'form-builder-grid-10';
            }
        }

        return $x;
    }

    // --------------------------------------------------------------------
    /**
     * Print the column content
     * 
     * @return string Column output
     */
    protected function printColumnContent($rowNum, $colNum)
    {
        $arg   = func_get_args();
        
        $row   = $this->fieldsArr[$rowNum];
        $col   = $this->fieldsArr[$rowNum]['columns'][$colNum];
        $out   = '';

        if ( ! isset($col['input']['method']) AND isset($col['label']))  // it will be an array with "radios, checkboxs";
        {
            $i = 0;
            
            foreach ($col['input'] as $col_v)
            {
                $out .= (isset($col['listLabel'][$i])) ? $col['listLabel'][$i] : '';
                $out .= $this->printInput($col_v);
                $i ++;
            }
        }
        else
        {
            $out = $this->printInput($col['input']);
        }

        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * Setting a value for 'inputs', Form::setValue()
     */
    protected function setValue()
    {
        $arg = (func_get_args());
        return call_user_func_array(array($this->form, 'setValue'), $arg);
    }

    // --------------------------------------------------------------------
    /**
     * Setting a value for 'radio', Form::setRadio()
     */
    protected function setRadio()
    {
        $arg = (func_get_args());
        return call_user_func_array(array($this->form, 'setRadio'), $arg);
    }

    /**
     * Setting a value for 'radio', Form::setRadio()
     */
    protected function setSelect()
    {
        $arg = (func_get_args());
        return call_user_func_array(array($this->form, 'setSelect'), $arg);
    }

    // --------------------------------------------------------------------
    /**
     * Setting a value for 'checboxs', Form::setCheckbox()
     */
    protected function setCheckbox()
    {
        $arg = (func_get_args());
        return call_user_func_array(array($this->form, 'setCheckbox'), $arg);
    }

    // --------------------------------------------------------------------
    /**
     * Print the label of the column
     * 
     * @return string Column Label
     */
    protected function printLabel()
    {
        $arg = func_get_args();

        $row = $this->fieldsArr[$arg[0]];
        $addon_class = (isset($row['position']['label'])) ? 'form-builder-label-' . $row['position']['label'] : 'form-builder-label-top';

        $out  = "<div class='form-builder-label-wrapper $addon_class'>";
        $out .= (isset($row['columns'][$arg[1]]['label'])) ? $this->form->label($row['columns'][$arg[1]]['label']) : ' ';
        $out .= '</div>';

        return $out;
    }

    // --------------------------------------------------------------------
    protected function setRules()
    {
        foreach($this->fieldsArr as $row )
        {
            foreach($row as $key => $rowVars)
            {
                if($key == 'columns')
                {
                    foreach($rowVars as $colKey => $col )
                    {
                        if( ! empty($col['rules']) )
                        $this->form->setRules($col['field_name'], $col['label'], $col['rules']);
                    }
                }
            }
        }
    }

    // --------------------------------------------------------------------
    /**
     * Run the validation
     * 
     * @return boolean
     */
    public function isValid($identifier)
    {
        if(empty(self::$forms[$identifier]))
        {
            throw new Exception('Form Builder Error : trying to printForm for a not created form.');
        }

        self::$forms[$identifier]->setRules();

        return $this->form->isValid();
    }

    // --------------------------------------------------------------------

    /**
     * Captcha
     * 
     * @return string
     */
    protected function captchaBuild()
    {
        $args = func_get_args();
        $args = $args[0];
        
        $config = getConfig('form_builder');
        
        if( is_callable($config['captcha']) )
        {
            $captcha = call_user_func_array(Closure::bind($config['captcha'], getInstance(), 'Controller'), array());

            if( empty($captcha['hidden_input_template']) OR empty($captcha['image_url']) )
            {
                throw(new Exception('Form builder error : Captcha closure in the config file must return an array containing two index keys (image_hidden_input, image_url).'));
            }

            $out = "\t<div class='form-builder-captcha-wrapper'>\n";

                $out.= "\t".sprintf($captcha['hidden_input_template'], $captcha['image_id'])."\n";
                $out.= "\t".sprintf($captcha['image_template'], $captcha['image_url'])."\n";
                $out.= "\t". call_user_func_array(array($this->form, 'input'), $args)."\n";

            $out.= "\t</div>\n";

            return $out;
        }
        else
        {
            throw new Exception('Form Builder error : Captcha key must be a callable function in form_builder.php config file.');
        }
    }
}

// END Form Builder Class

/* End of file form_builder.php */
/* Location: ./packages/form_builder/releases/0.0.1/form_builder.php */
