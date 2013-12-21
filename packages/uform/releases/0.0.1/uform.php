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
        if (!isset(getInstance()->uform))
        {
            getInstance()->uform = $this;  // Make available it in the controller.
        }

        logMe('debug', "Uform Class Initialized");
    }

    // --------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        switch ($method)
        {
            case "input":
            case "hidden":
            case "submit":
            case "checkbox":
            case "radio":
            case "dropdown":
            case "multiselect":
            case "textarea": {
                    if ($method == 'textarea')
                        $this->setColValue("field_name", $arguments[0]['name']);
                    else
                        $this->setColValue("field_name", $arguments[0]);

                    return array("method" => $method, "arguments" => $arguments);

                    break;
                }
            // case "setValue":
            // {
            //     $this->setColValue("setValue" , $arguments[0]);
            //     return Form::setValue($arguments[0]);
            //     break;
            // }
            default: {
                    if (!method_exists($this, $method))
                        throw new Exception("Uform error : Wrong method name $method");
                    break;
                }
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Open the form tag and set its attributes
     */
    protected function addForm($arg)
    {
        $arg = func_get_args();
        $this->output = forward_static_call_array(array("Form", "open"), $arg);
    }

    // --------------------------------------------------------------------

    protected function formClose()
    {
        $arg = func_get_args();
        $this->output .= forward_static_call_array(array("Form", "close"), $arg);
    }

    // --------------------------------------------------------------------
    
    /**
     * [This function is used to add rows to the form]
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

        if (!isset($arg['input']))
        {
            foreach ($arg as $o)
            {
                if (isset($o['input']))
                {
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['input'][] = $o['input'];
                    $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['listLabel'][] = (isset($o['label'])) ? $o['label'] : ' ';
                }
            }
            // $this->fieldsArr[$this->rowNum]['columns'][$this->colNum]['rules'] = (isset($arg['rules'])) ? $arg['rules'] : ' ';
        }
        elseif (isset($arg['input']))
        {
            $this->setColValue('input', @$arg['input']);
        }

        $this->setColValue('label', (isset($arg['label'])) ? $arg['label'] : ' ');
        $this->setColValue('rules', (isset($arg['rules'])) ? $arg['rules'] : ' ');

        // increase column index in the columns array
        $this->colNum ++;
    }

    // --------------------------------------------------------------------

    protected function setColValue($index, $value)
    { {
            $this->fieldsArr[$this->rowNum]['columns'][$this->colNum][$index] = $value;
        }
    }

    // --------------------------------------------------------------------
    /**
     * Print a a form's input
     */
    protected function printInput($input)
    {
        if (is_array($input))
            switch ($input['method'])
            {
                case "input":
                case "hidden":
                case "submit":
                case "checkbox":
                case "radio":
                case "dropdown":
                case "multiselect": {
                        $out = forward_static_call_array(array("Form", $input['method']), $input['arguments']);
                        return $out;
                    }
                case "textarea": {
                        $out = forward_static_call_array(array("Form", $input['method']), $input['arguments']);
                        return $out;
                        break;
                    }
            }
        return;
    }

    // --------------------------------------------------------------------

    /**
     * [setPosition for label or input : $this->setPosition("label" , "left")]
     * @param string element name
     * @param string position
     */
    protected function setPosition()
    {
        $arg = func_get_args();
        if (in_array($arg[0], array("label", "input")) && in_array($arg[1], array("left", "top", "right", "center")))
        {
            $this->fieldsArr[$this->rowNum]['position'][$arg[0]] = $arg[1];
        }
    }

    // --------------------------------------------------------------------

    /**
     * Creat the form, the main function.
     * @param  [function] $fun [description]
     */
    public function create($fun)
    {
        $arg = (func_get_args());

        call_user_func_array(Closure::bind($fun, $this, get_class()), array());
    }

    // --------------------------------------------------------------------

    /**
     * Print the form HTML
     */
    public function printForm()
    {
        // check the printout type "table or div"
        $out = "<div class='uform-div-wrapper'> ";
        $out .= $this->output;

        // looping the fields array
        if (is_array($this->fieldsArr))
        {
            foreach ($this->fieldsArr as $rowId => $v)
            {
                // printing a row "tr or div"
                $out .= "<div class='uform-row'>";
                if (is_array($v))
                {
                    // get column grid class
                    $columnsNum = count($v['columns']);
                    $gridClass = $this->calculateWidth($columnsNum);
                    foreach ($v['columns'] as $colId => $v2)
                    {
                        $label = $error = $columnContent = '';
                        $addon_class = (isset($v['position']['input'])) ? "uform-ipos-" . $v['position']['input'] : "";
                        // adding the column TD
                        $out .= "<div class='uform-column " . array_shift($gridClass) . " $addon_class' >";

                        $label = $this->printLabel($rowId, $colId);
                        // retrive column content.
                        $columnContent = $this->printColumnContent($rowId, $colId);

                        // retrive validation error msg
                        $error = (isset($v2['error_msg'])) ? $v2['error_msg'] : '';

                        $out .= $label . $columnContent . $error;

                        // closing the td,div tags
                        $out .= "</div>";
                    }
                }
                $out .= "</div>";
            }
        }
        $out .= "</div> ";
        $out .= $this->formClose();

        return $this->output = $out;
    }

    // --------------------------------------------------------------------

    /**
     * [This function determine which classes will be assigned to each column]
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

        // 
        if (in_array($columnsNum, array_keys($gridSys)))
        {
            array_walk($gridSys[$columnsNum], function($value, $key) {
                $this->resArr[] = 'uform-grid-' . $value;
            });
        }
        else
        {
            for ($i = 0; $i < $columnsNum; $i++)
                $this->resArr[] = 'uform-grid-10';
        }
        $x = $this->resArr;
        $this->resArr = array();
        return $x;
    }

    // --------------------------------------------------------------------

    protected function printColumnContent()
    {
        $arg = func_get_args();
        $rowId = $arg[0];
        $colId = $arg[1];
        $row = $this->fieldsArr[$rowId];
        $col = $this->fieldsArr[$rowId]['columns'][$colId];
        $out = '';
        // it will be an array with "radios, checkboxs";
        if (!isset($col['input']['method']) && isset($col['label']))
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

    protected function setValue()
    {
        $arg = (func_get_args());
        return $return = forward_static_call_array(array("Form", "setValue"), $arg);
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

    protected function printLabel()
    {
        $arg = func_get_args();
        $row = $this->fieldsArr[$arg[0]];
        $addon_class = (isset($row['position']['label'])) ? "uform-label-" . $row['position']['label'] : "uform-label-top";
        $out = "<div class='uform-label-wrapper $addon_class'>";
        $out .= (isset($row['columns'][$arg[1]]['label'])) ? Form::label($row['columns'][$arg[1]]['label']) : ' ';
        $out .= "</div>";
        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * [run the validation]
     * @return boolean [description]
     */
    public function isValid()
    {
        $validator = getComponentInstance('validator');
        $validator->set('_callback_object', $this);
        foreach ($this->fieldsArr as $v)
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
        $validation = $validator->run();

        for ($i = 1; $i < count($this->fieldsArr); $i++)
        {
            if (is_array($this->fieldsArr[$i]))
            {
                for ($j = 0; $j < count($this->fieldsArr[$i]['columns']); $j++)
                {
                    $this->fieldsArr[$i]['columns'][$j]['error_msg'] = Form::error($this->fieldsArr[$i]['columns'][$j]['field_name'], "<div class='uform_error' >(*) ", "</div>");
                }
            }
        }
        return $validation;
    }

}

// END Uform Class

/* End of file uform.php */
/* Location: ./packages/uform/releases/0.0.1/uform.php */