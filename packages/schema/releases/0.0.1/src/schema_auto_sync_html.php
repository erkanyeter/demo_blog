<?php
namespace Schema\Src;

Class Schema_Auto_Sync_Html {

	public $sync; // Auto sync object

	/**
	 * Constructor
	 * 
	 * @param object $syncObject
	 */
	public function __construct($syncObject, $schemaObject)
	{
		$this->syncObject 	= &$syncObject;
		$this->schemaObject = &$schemaObject;

		$this->form = new \Form;
	}

	// --------------------------------------------------------------------

	/**
	 * Write Html Ouput
	 * 
	 * @return string
	 */
	public function writeOutput()
	{
		$html = '<html>';
		$html.= '<head>'.$this->schemaObject->writeCss().'</head>';
		$html.= '<body>';
		$html.= '<h1>Synchronize the <i><u>'.strtolower($this->schemaObject->getTableName()).'</u></i> schema</h1>';

		$html.= $this->form->open('/'.$this->schemaObject->getRequestUri(), array('method' => 'POST', 'name' => 'sync_table', 'id' => 'sync_table'));

		$html.= '<table class="modelTable" width="%100">';
		$html.= '<tr>';
		$html.= '<th>Column Name</th>';
		$html.= '<th>Types</th>';
		$html.= '</tr>';

		foreach($this->syncObject->getSchemaDiffArray() as $key => $val)
		{
			$html.= '<tr>';

			$class = ''; 
			if( ! isset($val['types']) AND count($val) > 1) { $class = 'newColumn'; } // New Column

				if(isset($val['new_types']))
				{
					$html.= '<td class="'.$class.'">'.$key;

					if(isset($val['options'])) // parse options
					{
						$html.= '<div class="columnUpdateWrapper">';
							$html.= '<div class="columnNewRow">';
						    	$add 	= '(+)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $val['options'][1]);
								$remove = '(-)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $val['options'][0]);
								$html.= '<kbd class="columnDelRow"><a href="javascript:void(0);" onclick="removeKey(\''.$this->_stripQuotes($key).'\',\''.$this->_stripQuotes($val['new_types']).'\',\''.$val['options'][0].'\');">'.$remove.'</a></kbd>';
								$html.= '<kbd class="columnAddRow"><a href="javascript:void(0);" onclick="addKey(\''.$this->_stripQuotes($key).'\',\''.$this->_stripQuotes($val['new_types']).'\',\''.$val['options'][1].'\');">'.$add.'</a></kbd>';
							$html.= '</div>';
						$html.= '</div>';
					}

					$html.= '</td>';
				} 
				else 
				{
					$html.= '<td class="'.$class.'">'.$key.'</td>';
				}

				if(isset($val['types']))
				{
					$html.= '<td>'.$val['types'];

					if( isset($val['types']) AND count($val) > 1) // Column & attribute updates
					{
						$html.= '<div class="columnUpdateWrapper">';

							foreach($val as $mapVal)
							{
								if($mapVal != 'types')
								{
									$errorClass = '';
									if(isset($mapVal['update_types']) AND strpos($mapVal['update_types'], '_') !== 0) // error in rule, rule must be start with '_' prefix.
									{
										$errorClass = 'columnTypeError';
									}

									if(isset($mapVal['update_types']) AND $mapVal['update_types'] != '_') // don't show empty keys
									{
										$html.= '<div class="columnNewRow">';
											$html.= '<div style="clear:left;"></div>';
											$html.= '<div class="columnNewType '.$errorClass.'">'.$mapVal['update_types'].'</div>';

											if(isset($mapVal['options'])) // parse options
											{
												$html.= '<div style="float:left;">';

													$add 	= (isset($mapVal['options'][1])) ? '(+)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $mapVal['options'][1]) : '';
													$remove = (isset($mapVal['options'][0])) ? '(-)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $mapVal['options'][0]) : '';

													if (! empty($remove)) 
													{	
														$html.= '<kbd class="columnDelRow"><a href="javascript:void(0);" onclick="removeType(\''.$this->_stripQuotes($key).'\', \''.$this->_stripQuotes($mapVal['update_types']).'\', \''.$mapVal['options'][0].'\');">'.$remove.'</a></kbd>';
													}

													if( ! empty($add))
													{
														$html.= '<kbd class="columnAddRow"><a href="javascript:void(0);" onclick="addType(\''.$this->_stripQuotes($key).'\', \''.$this->_stripQuotes($mapVal['update_types']).'\', \''.$mapVal['options'][1].'\');">'.$add.'</a></kbd>';
													}

												$html.= '</div>';
											}
											
											$html.= '<div style="clear:left;"></div>';
										$html.= '</div>';
									}
								}
							}

						$html.= '</div>';
					}

					$html.= '</td>';
				} 
				elseif(isset($val['new_types']))
				{
					$html.= '<td class="'.$class.'">'.$val['new_types'];
					
					if (isset($val['errors'])) { $html.= $val['errors']; }
					
					$html.= '<div class="columnUpdateWrapper">';

						foreach(explode('|', trim($val['new_types'], '|')) as $type)
						{
							$errorClass = '';
							if(strpos($type, '_') !== 0) // error in rule, rule must be start with '_' prefix.
							{
								$errorClass = 'columnTypeError';
							}

							if($type != '_') // don't show empty keys
							{
								$html.= '<div class="columnNewRow">';

									$html.= '<div style="clear:left;"></div>';
									$html.= '<div class="columnNewType '.$errorClass.'">'.$type.'</div>';

									if(isset($val['options'])) // parse options
									{
										$html.= '<div style="float:left;">';

									    $add 	= '(+)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $val['options'][1]);
										$remove = '(-)'.str_replace(array('db','file'), array('<b>db</b>','<b>file</b>'), $val['options'][0]);

										$html.= '<kbd class="columnDelRow"><a href="javascript:void(0);" onclick="removeType(\''.$this->_stripQuotes($key).'\', \''.$this->_stripQuotes($type).'\', \''.$val['options'][0].'\', \'new\');">'.$remove.'</a></kbd>';
										$html.= '<kbd class="columnAddRow"><a href="javascript:void(0);" onclick="addType(\''.$this->_stripQuotes($key).'\', \''.$this->_stripQuotes($type).'\', \''.$val['options'][1].'\', \'new\');">'.$add.'</a></kbd>';

										$html.= '</div>';
									}
									
								$html.= '<div style="clear:left;"></div>';
								$html.= '</div>';
							}
						}

					$html.= '</div>';
					$html.= '</td>';
				}

			$html.= '</tr>';
		}
		$html.= '</table>';

		$html.= '<input type="hidden" name="lastCurrentPage" id="lastCurrentPage" value="'.urlencode($this->schemaObject->getRequestUri()).'" style="width:500px;">';
		$html.= '<input type="hidden" name="lastSyncCommand" id="lastSyncCommand" value="" style="width:500px;">';
		$html.= '<input type="hidden" name="lastSyncFunc" id="lastSyncFunc" value="" style="width:500px;">'."\n";

		$html.= $this->form->close();
		$html.= $this->schemaObject->writeScript();

		$html.= '<p></p>';
		$html.= '<p class="footer" style="font-size:11px;">* You see this screen because of <kbd>auto sync</kbd> feature enabled in <kbd>development</kbd> mode, you can configure it from your config file. Don\'t forget to close it in <kbd>production</kbd> mode.</p>';
		
		$html.= "\n</body>";
		$html.= "\n</html>";

		return $html;
	}

	// --------------------------------------------------------------------

	/**
	 * Replace double quotes
	 * 
	 * @param  string $str
	 * @return string
	 */
	public function _stripQuotes($str)
	{
		return str_replace('"', '&quot;', addslashes($str));
	}

}