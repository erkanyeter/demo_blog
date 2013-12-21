<?php

Class Tag_Cloud {

	private $_config    = array();
	private $_tagsArr   = array(); // taglar dönüşümleri yapıldıktan sonra array olarak atanıyor.
	private $_Arr       = array(); // tagların ilk yalın hali array olarak atanıyor.
	private $_colorType = null;

	public $url;  // Url object

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_config = getConfig('tag_cloud'); // .app/config/tag_cloud.php get config
		$this->url     = $this->_config['url'];

        if( ! isset(getInstance()->tag_cloud))
        {
            getInstance()->tag_cloud = $this; // Make available it in the controller $this->tag_cloud->method();
        }

        logMe('debug', "Tag Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * _formatTag
	 * 
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	private function _formatTag($str)
	{
		$closure = $this->_config['formatting']['transformation'];

		if(is_callable($closure)) // Is callable function ?
        {
            $func = Closure::bind($closure, $this, get_class());
            return $func($str);
        }

        throw new Exception('Tag cloud config $tag_cloud[\'formatting\'][\'transformation\'] = function(){} item must be a callable function.');
	}

	// --------------------------------------------------------------------

	/**
	 * _createUrl
	 * 
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	private function _createUrl($string)
	{
		/**
		 * $clearString link oluşturulmak için tagda a-Z ve 0-9 hariç herşey siliniyor ve url_separator birden fazla eklenmemesi için önlem alınıyor.
		 * return preg_replace Birden fazla white spacelar silinip url oluşturuluyor.
		 */
		$clearString = preg_replace('/[^\w ]|'.$this->_config['formatting']['url_separator'].'/', '', strip_tags($string)); 

		return preg_replace('/('.$this->_config['formatting']['url_separator'].'+)|(\s+)/', $this->_config['formatting']['url_separator'], $clearString);
	}

	// --------------------------------------------------------------------
	
	/**
	 * addTag
	 * 
	 * @param [type] $tag       [description]
	 * @param [type] $url       [description]
	 * @param [type] $attribute [description]
	 */
	public function addTag($tag, $url = null, $attribute = null)
	{
		$arr = array(
					'tag'       => $tag,
					'url'       => $url,
					'attribute' => $attribute
					);

		return array_push($this->_Arr, $arr);
	}

	// --------------------------------------------------------------------

	/**
	 * _addTags
	 */
	private function _addTags()
	{
		$i=0;
		$values = array();
		foreach($this->_Arr as $key => $val)
		{
			$tag = $this->_formatTag($val['tag']);
			$val['tag'] = $tag;
			if(empty($val['url']) OR $val['url'] === null OR $val['url'] === false)
			{
				$val['url'] = $this->_createUrl($val['tag']);
			}

			$values[]= $val;

		}

		return $this->_tagsArr = $values; 
	}

	// --------------------------------------------------------------------

	/**
	 * render
	 * 
	 * @param  string  $type    [description]
	 * @param  [type]  $color   [description]
	 * @param  boolean $shuffle [description]
	 * @return [type]           [description]
	 */
	public function render($type = 'array', $shuffle = true)
	{
		$tagArr = $this->_addTags(); // Hazır hale getirip $this->_tagsArr değişkenine atıyoruz. _htmlizeTag() içinde gerekli

		switch($type)
		{
			case 'html':
				return $this->_htmlizeTag($shuffle);
				break;
			case 'array':
				return $tagArr;
				break;
			default:
				return false;
				break;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * _htmlizeTag
	 * 
	 * @param  [type]  $color   [description]
	 * @param  boolean $shuffle [description]
	 * @return [type]           [description]
	 */
	private function _htmlizeTag($shuffle)
	{
		if ($shuffle === true)
		{
			$this->_shuffle(); // Tagları karıştırıyoruz. Random olarak geri dönüş sağlanıyor.
		}
		$i = 0;
		$count = count($this->_tagsArr);
		foreach($this->_tagsArr as $key => $value)
		{
			$styleColor = '';
			$url = $this->_config['seo_segment'] .'/'. $value['url']; // create a new url
			if($this->_colorType != null)
			{
				$colors     = $this->_getColor();
				$styleColor = ' style="color: rgb('.implode(',', $colors).')"';
			}
			if($i == 0)
			{
				$return = $this->url->anchor($url, $value['tag'], $value['attribute'].$styleColor).'&nbsp;';
			}
			else
			{
				$return.= $this->url->anchor($url, $value['tag'], $value['attribute'].$styleColor).'&nbsp;';
			}
			$i++;
		}

		return rtrim($return, '&nbsp;');
	}

	// --------------------------------------------------------------------

	/**
	 * _getColor
	 * 
	 * @return [type] [description]
	 */
	private function _getColor()
	{
		$hash = md5('color' . mt_rand(0,100)); // hexdec için random hash oluşturuluyor.
		switch($this->_colorType)
		{
			case 'dark': // Koyu renkli tonlar
				$return = array(
								hexdec(substr($hash, 0, 2)), // r
								hexdec(substr($hash, 2, 1)), // g
								hexdec(substr($hash, 4, 2))  // b
								);
				break;
			case 'light': // Açık renkli tonlar
				$return = array(
								hexdec(substr($hash, 0, 3)), // r
								hexdec(substr($hash, 2, 2)), // g
								hexdec(substr($hash, 4, 2))  // b
								);
				break;
			case 'mixed': // Açık - Koyu karışık tonlar
				$return = array(
								hexdec(substr($hash, 0, 2)), // r
								hexdec(substr($hash, 2, 2)), // g
								hexdec(substr($hash, 4, 2))  // b
								);
				break;
			default: // default mixed array
				$return = array(
								hexdec(substr($hash, 0, 2)), // r
								hexdec(substr($hash, 2, 2)), // g
								hexdec(substr($hash, 4, 2))  // b
								);
				break;
		}

    	return $return;
	}

	// --------------------------------------------------------------------

	/**
	 * setColor
	 * 
	 * @param [type] $string [description]
	 */
	public function setColor($string)
	{
		return $this->_colorType = $string;
	}

	// --------------------------------------------------------------------

	/**
	 * _shuffle
	 * 
	 * @return [type] [description]
	 */
	private function _shuffle()
	{
		$keys = array_keys($this->_tagsArr);
		
		shuffle($keys);

		if (count($keys) AND is_array($keys))
		{
			$tmpArray       = $this->_tagsArr;
			$this->_tagsArr = array();

			foreach ($keys as $key => $value)
			{
				$this->_tagsArr[$value] = $tmpArray[$value];
			}
		}

		return $this->_tagsArr;
	}

	// --------------------------------------------------------------------

	/**
	 * _convertCharacters
	 * 
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	private function _convertCharacters($string)
	{
		return str_replace(
						array_keys($this->_config['transliteration_table']),
						array_values($this->_config['transliteration_table']),
						$string
						);
	}
}

/* End of file tag_cloud.php */
/* Location: ./packages/tag_cloud/releases/0.0.1/tag_cloud.php */