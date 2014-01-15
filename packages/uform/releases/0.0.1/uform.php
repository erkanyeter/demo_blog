<?php

/**
 * Build Dynamic Html Forms
 * 
 * @author rabihsyw <rabihsyw@gmail.com>
 * @package       packages
 * @subpackage    uform
 * @category      form builder
 * @example $uform = new Uform();
 *          $uform->create("table", function(){
 *              $this->addForm('/tutorials/hello_uform', array('method' => 'post'));
 *
 *              // adding new Row to the form and then adding two columns inside it.
 *              $this->addRow();
 *              $this->addCol(
 *                  'label' => 'Email',
 *                  'input' => $this->input('email', $this->setValue('email'), " id='email' "),
 *                  'rules' => 'required|xssClean'
 *                 )
 *              );
 *              $this->addCol(array(
 *                  'label' => 'Password',
 *                  'input' => $this->dropdown('doUKnow', array(1 => "Yes" , 2 => "No" , 3 => "I don't know"), " id='duknow' "),
 *                  'rules' => 'required|xssClean'
 *                 )
 *              );
 *
 *              $this->addRow();
 *              $this->addCol(array(
 *                  "label" => "Language",
 *                  array("label" => "Arabic", "input" => $this->radio("lang", 'ar') ),
 *                  array("label" => "Turkish", "input" => $this->radio("lang", 'tr') ),
 *                  array("label" => "English", "input" => $this->radio("lang", 'en') )
 *              ));
 *
 *              $this->addRow();
 *              $this->addCol(array(
 *                  'input' => $this->submit('submit', ' Login ', '', " id='sbmt' ")
 *                 )
 *              );
 *          });
 *
 *          $uform->isValid();
 *          $uform->printForm();
 * @tutorial    input's accepted funcitons are : input => $this->{fun_name}. {fun_name} = input, hidden, radio, checkbox, dropdown, submit, password, textarea
 * 
 */
Class Uform
{
    private $output;
    private $fieldsArr;
    private $rowNum = 0;
    private $colNum = 0;

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        if( ! isset(getInstance()->form))   // Load dependency, form package.
        {
            new Form;
        }

        if ( ! isset(getInstance()->uform))
        {
            getInstance()->uform = $this;  // Make available it in the controller.
        }

        logMe('debug', 'Uform Class Initialized');
    }

    // --------------------------------------------------------------------


    /**
     * This function is used to handle some functions.
     * 
     * handle $this->form->input() functions
     */
    public function __call($method, $arguments)
    {
        switch ($method)
        {
            case 'input':
            case 'password':
            case 'hidden':
            case 'submit':
            case 'checkbox':
            case 'radio':
            case 'dropdown':
            case 'multiselect':
            case 'textarea': {
                    if ($method == 'textarea')
                        $this->setColValue('field_name', $arguments[0]['name']);
                    else
                        $this->setColValue('field_name', $arguments[0]);

                    return array('method' => $method, 'arguments' => $arguments);

                    break;
                }
            // case "setValue":
            // {
            //     $this->setColValue("setValue" , $arguments[0]);
            //     return Form::setValue($arguments[0]);
            //     break;
            // }
            default: {
                    if ( ! method_exists($this, $method))
                        throw new Exception("Uform error : Wrong method name $method");
                    break;
                }
        }
    }

    // --------------------------------------------------------------------

    protected function close()
    {
        $args = func_get_args();

        $this->output .= call_user_func_array(array(getInstance()->form, 'close'), $args)."\n";
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
     * @param array : accepted parameters : "label" => '', input => array(), array("input" => array())
     */
    protected function addCol()
    {
        $arg = (func_get_args());
        $arg = $arg[0];

        if ( ! isset($arg['input']))  // for radios & checkboxes "input" isn't set directly in $arg
        {
            foreach ($arg as $o)
            {
                if (isset($o['input']))
                {
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['input'][]     = $o['input'];
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['listLabel'][] = (isset($o['label'])) ? $o['label'] : ' ';
                }
            }
        }
        elseif (isset($arg['input']))
        {
            $this->setColValue('input', $arg['input']);
        }

        $this->setColValue('label', (isset($arg['label'])) ? $arg['label'] : ' ');
        $this->setColValue('rules', (isset($arg['rules'])) ? $arg['rules'] : ' ');

        // increase column index in the columns array
        $this->colNum ++;
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
                    return call_user_func_array(array(getInstance()->form, $input['method']), $input['arguments']);
                }
            }
        }
        return;
    }

    // --------------------------------------------------------------------

    /**
     * [setPosition for label or input : $this->setPosition("label" , "left")]
     * 
     * @param string element name
     * @param string position
     */
    protected function setPosition()
    {
        $arg = func_get_args();

        if (in_array($arg[0], array('label', 'input')) AND in_array($arg[1], array('left', 'top', 'right', 'center')))
        {
            $this->fieldsArr[$this->rowNum]['position'][$arg[0]] = $arg[1];
        }
    }

    // --------------------------------------------------------------------

    /**
     * Create the form, the main function.
     * 
     * @param  [function] $fun [description]
     */
    public function open()
    {
        $args = func_get_args();

        call_user_func_array(Closure::bind($args[2], $this, get_class()), array());

        $this->output .= "\t".call_user_func_array(array(getInstance()->form, 'open'), $args);
    }

    // --------------------------------------------------------------------

    /**
     * Print the form HTML
     * 
     * @return string
     */
    public function printForm($closeTag = '<div style="clear:both;"></div>')
    {
        $out = "\n<div class='uform-div-wrapper'>\n";         // check the printout type "table or div"
        $out.= $this->output;

        if (is_array($this->fieldsArr))         // looping the fields array
        {
            foreach ($this->fieldsArr as $rowId => $v)
            {
                $out .= "\n\t<div class='uform-row'>";  // printing a row "<div>"

                if (is_array($v))
                {
                    $columnsNum = count($v['columns']);     // get column grid class
                    $gridClass = $this->calculateWidth($columnsNum);

                    foreach ($v['columns'] as $colId => $v2)
                    {
                        $label = $error = $columnContent = '';
                        $addon_class = (isset($v['position']['input'])) ? "uform-ipos-" . $v['position']['input'] : "";

                        $out  .= "\n\t\t<div class='uform-column " . array_shift($gridClass) . " $addon_class' >";  // add the column TD
                        $label = "\n\t\t\t".$this->printLabel($rowId, $colId);
                        
                        $columnContent = "\n\t\t\t".$this->printColumnContent($rowId, $colId);  // retrive column content.

                        $error = (isset($v2['error_msg'])) ? $v2['error_msg'] : '';   // retrive validation error msg

                        $out .= $label . $columnContent . $error;

                        $out .= "\n\t\t</div>"; // close the div tags
                    }
                }

                $out .= "\n\t</div>\n";  // close the "uform-row" div
            }
        }

        $out .= "</div>\n";

        return $this->output = $out.$closeTag;
    }

    // --------------------------------------------------------------------

    /**
     * [This function determines which 'css classes' will be assigned to each column]
     * @return array(strings)
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
        // check if the count of columns in the same row is larger than the accepted count.
        if (in_array($columnsNum, array_keys($gridSys)))
        {
            array_walk($gridSys[$columnsNum], function($value, $key) use(&$x) {
                $x[] = 'uform-grid-' . $value;
            });
        }
        else
        {
            for ($i = 0; $i < $columnsNum; $i++)
                $x[] = 'uform-grid-10';
        }
        return $x;
    }

    // --------------------------------------------------------------------

    /**
     * Print the column content
     * @return string Column output
     */
    protected function printColumnContent()
    {
        $arg = func_get_args();
        $rowId = $arg[0];
        $colId = $arg[1];
        $row = $this->fieldsArr[$rowId];
        $col = $this->fieldsArr[$rowId]['columns'][$colId];
        $out = '';

        // it will be an array with "radios, checkboxs";
        if (!isset($col['input']['method']) AND isset($col['label']))
        {
            $i = 0;
            if (isset($col['setValue']))
                $this->setValueFun($rowId, $colId, $col['setValue']);
            foreach ($col['input'] as $col_v)
            {
                $out .= (isset($col['listLabel'][$i])) ? $col['listLabel'][$i] : '';
                $out .= $this->printInput($col_v);
                $i ++;
            }
        }
        else
        {
            if (isset($col['setValue']))
                $this->setValueFun($rowId, $colId, $col['setValue']);
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

        return $return = call_user_func_array(array(getInstance()->form, 'setValue'), $arg);
    }

    // --------------------------------------------------------------------

    protected function setValueFun($rowId, $colId, $value)
    {
        // $col = $this->fieldsArr[$rowId]['columns'][$colId];
        // if (!isset($col['input']['arguments']))
        // {
        //     for ($i = 0 ; $i < count($col['input']); $i++)
        //     {
        //         $o = $col['input'][$i];
        //         if (isset($o['arguments']))
        //         {
        //             $this->fieldsArr[$rowId]['columns'][$colId]['input'][$i]['arguments'][2] = Form::setValue($value);
        //         }
        //     }
        //     // $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['rules'] = (isset($arg['rules'])) ? $arg['rules'] : ' ';
        // }else
        // {
        //     $this->fieldsArr[$rowId]['columns'][$colId]['input']['arguments'][1] = Form::setValue("$value");
        // }
        // return ;
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
        $addon_class = (isset($row['position']['label'])) ? 'uform-label-' . $row['position']['label'] : 'uform-label-top';

        $out  = "<div class='uform-label-wrapper $addon_class'>";
        $out .= (isset($row['columns'][$arg[1]]['label'])) ? getInstance()->form->label($row['columns'][$arg[1]]['label']) : ' ';
        $out .= '</div>';

        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * Run the validation
     * @return boolean [description]
     */
    public function isValid()
    {
        $validator = getComponentInstance('validator');
        $validator->set('_callback_object', $this);

        foreach ($this->fieldsArr as $v) 
        {
            if (is_array($v))
            {
                foreach ($v['columns'] as $column)
                {
                    $rules = $column['rules'];
                    $field_name = $column['field_name'];

                    if (is_array($column))
                    {
                        if (isset($column['rules']) AND $column['rules'] != '')
                        {
                            $label = (isset($column['label'])) ? $column['label'] : '';

                            $validator->setRules($field_name, $label, $column['rules']);
                        }
                    }
                }
            }
        }

        $validation = $validator->run();

        for ($i = 1; $i < count($this->fieldsArr); $i++)
        {
            if (is_array($this->fieldsArr[$i]))
            {
                for ($j = 0; $j < count($this->fieldsArr[$i]['columns']); $j++)
                {
                    $this->fieldsArr[$i]['columns'][$j]['error_msg'] = getInstance()->form->error($this->fieldsArr[$i]['columns'][$j]['field_name'], "<div class='uform_error' >", "</div>");
                }
            }
        }

        return $validation;
    }

}

// END Uform Class

/* End of file uform.php */
/* Location: ./packages/uform/releases/0.0.1/uform.php */