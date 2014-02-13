<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

	/**
	 * Add break
	 * 
	 * @param string $padding css padding pixel <div style="height:%dpx;">%s</div>
	 * @param string $str content default is '&nbsp;'
	 */
    function addBr($padding = '5', $str = '&nbsp;')
    {
    	$form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['addbr'], $padding, $str);
    }
    
}