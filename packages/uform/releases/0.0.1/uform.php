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
    private $colNames = array();

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
            case 'textarea':
            case 'multiselect':{
                $colname = $arguments[0];
                $this->setColValue('field_name', $arguments[0]);
                $this->colNames[$this->rowNum][$this->colNum] = $colname; // set column name

                return call_user_func_array(array(getInstance()->form, $method), $arguments);
            }
            /*
            case 'textarea':
                    $this->setColValue('field_name', $arguments[0]['name']);
                    return array('method' => $method, 'arguments' => $arguments);
                    break;
            */
            // case "setValue":
            // {
            //     $this->setColValue("setValue" , $arguments[0]);
            //     return Form::setValue($arguments[0]);
            //     break;
            // }                
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
        else
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

            getInstance()->form->setRules($this->getColName(), $label, $data['rules']);

            // $this->setColValue('rules', $arg['rules']);
        }

        // increase column index in the columns array
        $this->colNum ++;

        // print_r($this->colNames);
    }

    // --------------------------------------------------------------------

    /**
     * Get current column name
     * 
     * @return [type] [description]
     */
    public function getColName()
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
    /*
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
*/
    // --------------------------------------------------------------------

    /**
     * Set position for label or input
     * 
     * $this->setPosition('label' , 'left');
     * $this->setPosition('input' , 'right');
     * 
     * @param string element name
     * @param string position
     */
    protected function setPosition($element, $position = 'left')
    {
        if (in_array($element, array('label', 'input')) AND in_array($position, array('left', 'top', 'right', 'center')))
        {
            $this->fieldsArr[$this->rowNum]['position'][$element] = $position;
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
            foreach ($this->fieldsArr as $rowNum => $v)
            {
                $out .= "\n\t<div class='uform-row'>";  // printing a row "<div>"

                if (is_array($v))
                {
                    $columnsNum = count($v['columns']);     // get column grid class
                    $gridClass  = $this->calculateWidth($columnsNum);

                    foreach ($v['columns'] as $colNum => $v2)
                    {
                        $label = $error = $columnContent = '';
                        $addon_class = (isset($v['position']['input'])) ? "uform-ipos-" . $v['position']['input'] : "";

                        $out  .= "\n\t\t<div class='uform-column " . array_shift($gridClass) . " $addon_class' >";  // add the column TD
                        $label = "\n\t\t\t".$this->printLabel($rowNum, $colNum);
                        
                        // $columnContent = "\n\t\t\t".$this->printColumnContent($rowNum, $colId);  // retrive column content.

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
        if (in_array($columnsNum, array_keys($gridSys))) // check if the count of columns in 
        {                                                // the same row is larger than the accepted count.
            array_walk($gridSys[$columnsNum], function($value, $key) use(&$x) {
                $x[] = 'uform-grid-' . $value;
            });
        }
        else
        {
            for ($i = 0; $i < $columnsNum; $i++)
            {
                $x[] = 'uform-grid-10';
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

        print_r($this->fieldsArr); exit;

        var_dump($col); 

        /*
        if ( ! isset($col['input']['method']) AND isset($col['label']))  // it will be an array with "radios, checkboxs";
        {
            $i = 0;
            
            if (isset($col['setValue']))
            {
                $this->setValueFun($rowNum, $colNum, $col['setValue']);
            }

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
            {
                $this->setValueFun($rowNum, $colNum, $col['setValue']);
            }

            $out = $this->printInput($col['input']);
        }
        */
        return $out;
    }

    // --------------------------------------------------------------------

    /**
     * Setting a value for 'inputs', Form::setValue()
     */
    public function setValue()
    {
        $arg = (func_get_args());

        return call_user_func_array(array(getInstance()->form, 'setValue'), $arg);
    }

    // --------------------------------------------------------------------

    public function setValueFun($rowId, $colId, $value)
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

    public function setRules()
    {
        $args = func_get_args();

        call_user_func_array(array(getInstance()->form, 'setRules'), $args);
    }

    // --------------------------------------------------------------------

    /**
     * Run the validation
     * 
     * @return boolean
     */
    public function isValid()
    {
        getInstance()->form->isValid();
        /*
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
                    $this->fieldsArr[$i]['columns'][$j]['error_msg'] = getInstance()->form->error($this->fieldsArr[$i]['columns'][$j]['field_name'], "<div class='uform-error' >", "</div>");
                }
            }
        }

        return $validation;
    */
    }

    public function render()
    {
        for ($i = 1; $i < count($this->fieldsArr); $i++)
        {
            if (is_array($this->fieldsArr[$i]))
            {
                for ($j = 0; $j < count($this->fieldsArr[$i]['columns']); $j++)
                {
                    $this->fieldsArr[$i]['columns'][$j]['error_msg'] = getInstance()->form->error($this->fieldsArr[$i]['columns'][$j]['field_name'], "<div class='uform-error' >", "</div>");
                }
            }
        }

        // print_r($this->fieldsArr);
        // echo getInstance()->form->error('user_email');
    }

}

// END Uform Class

/* End of file uform.php */
/* Location: ./packages/uform/releases/0.0.1/uform.php */