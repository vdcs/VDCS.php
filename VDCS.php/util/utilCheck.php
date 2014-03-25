<?
//define('_BASE_CHAR_NUM',			'0123456789');
//define('_BASE_CHAR_LOWERCASE',		'abcdefghijklmnopqrstuvwxyz');
//define('_BASE_CHAR_MAJUSCLE',			'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

class utilCheck
{
	Const SYMBOL_SECUREUnallowed		= ' 	?$%#*@&=\'"<>()[]{}~^/\,;!|';
	Const SYMBOL_Password			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789._-';
	Const SYMBOL_KeywordUnallowed		= '?$%#*@&=\'"<>()[]{}~^/\,;!|';
	Const SYMBOL_Account			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	Const SYMBOL_AccountnoStart		= '._-';
	Const SYMBOL_AccountnoEnd		= '._-';
	Const SYMBOL_NameUnallowed		= ' 	?$%#*@&=\'"<>()[]{}~^/\,;!|';
	Const SYMBOL_NamenoStart		= '._-';
	Const SYMBOL_NamenoEnd			= '._-';
	Const SYMBOL_EmailName			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789._-';
	Const SYMBOL_Filename			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789._-';
	Const SYMBOL_FilenameenoStart		= '._-';
	Const SYMBOL_FilenameenoEnd		= '._-';
	Const SYMBOL_EmailNamenoStart		= '._-';
	Const SYMBOL_EmailNamenoEnd		= '._-';
	Const SYMBOL_Variable			= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789._-';
	Const SYMBOL_VariablenoStart		= '._-0123456789';
	Const SYMBOL_VariablenoEnd		= '._-';
	
	
	/*
	##############################
	##############################
	*/
	public static function isSecure($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_SECUREUnallowed,$re[$i])>-1) return false;
		}
		//if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
		return true;
	}
	
	public static function isVariable($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_NameUnallowed,$re[$i])>-1) return false;
		}
		if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
		return true;
	}
	
	public static function isValue($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_NameUnallowed,$re[$i])>-1) return false;
		}
		if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
		return true;
	}
	
	public static function isPassword($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_Password,substr($re,$i,1))===false) return false;
		}
		return true;
	}
	
	public static function isKeyword($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_KeywordUnallowed,$re[$i])>-1) return false;
		}
		return true;
	}
	
	
	/*
	##############################
	##############################
	*/
	public static function isAccount($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_Account,substr($re,$i,1))===false) return false;
		}
		if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
		return true;
	}
	public static function isUsername($re){return self::isAccount($re);}
	
	public static function isName($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_NameUnallowed,$re[$i])>-1) return false;
		}
		if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
		return true;
	}
	
	
	/*
	##############################
	##############################
	*/
	public static function isEmailName($re)
	{
		if(!isset($re{0})) return false;
		for($i=0;$i<strlen($re);$i++){
			if(strpos(self::SYMBOL_EmailName,substr($re,$i,1))===false) return false;
		}
		if(strpos('_',substr($re,0,1))>-1 || strpos('-',substr($re,0,1))>-1) return false;
		return true;
	}
	
	public static function isEmail($re)
	{
		//filter_var($emailAddress, FILTER_VALIDATE_EMAIL)
		if(!isset($re{0})) return false;
		$tmpstr=strstr($re,'@');
		if(!$tmpstr) return false;
		$tmpstr=strstr($re,'.');
		if(!$tmpstr) return false;
		return true;
	}
	/*
	filter_var('sgamgee@example.com', FILTER_VALIDATE_EMAIL); // Returns "sgamgee@example.com". This is a valid email address.
	filter_var('sauron@mordor', FILTER_VALIDATE_EMAIL); // Returns boolean false! This is *not* a valid email address.
	*/
	
	/*
	##############################
	##############################
	*/
	public static function isMobile($re)
	{
		if(!isset($re{0})) return false;
		if(is_numeric($re) && strlen($re)==11) return true;
		return false;
	}
	
	public static function isIDCard($re)
	{
		if(!isset($re{0})) return false;
		if(strlen($re)==15 || strlen($re)==18) return true;
		return false;
	}
	
	
	/*
	##############################
	##############################
	*/
	public static function isDate($ymd,$sep='-')
	{
		if(!empty($ymd)){
			list($year,$month,$day)=explode($sep,$ymd,3);
			return checkdate($month,$day,$year);
		}
		else{return false;}
	}
	
	
	/*
	##############################
	##############################
	*/
	public static function _is_ascii($str)
	{
		return (preg_match('/[^\x00-\x7F]/S', $str) == 0);
	}
	
	public static function isUTF8($re)
	{
		return preg_match('%^(?:
			[\x09\x0A\x0D\x20-\x7E]            # ASCII
			| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
			|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
			|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
			|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
			)*$%xs',$re);
	}
	
	
	/*
	##############################
	##############################
	*/
	static $regex = array(
		'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
		'phone' => '/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/',
		'mobile' => '/^((\(\d{2,3}\))|(\d{3}\-))?(13|15)\d{9}$/',
		'zip' => '/^[1-9]\d{5}$/',
		'qq' => '/^[1-9]\d{4,12}$/',
		'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
		'number' => '/\d+$/',
		'integer' => '/^[-\+]?\d+$/',
		'double' => '/^[-\+]?\d+(\.\d+)?$/',
		'currency' => '/^\d+(\.\d+)?$/',
		'english' => '/^[A-Za-z]+$/',
		'require'=> '/.+/',	//匹配任意字符，除了空和断行符
	);
	
	public static function check($type,$value) 
	{
		$pattern=self::getRegex($type);
		return preg_match($pattern,trim($value));
	}
	
	public static function getRegex($name) 
	{
		if(isset(self::$regex[strtolower($name)])){
			return self::$regex[strtolower($name)];
		}else{
			return $name;
		}
	}
	
	/**
	* Validate IP Address
	*
	* @access	public
	* @param	string
	* @param	string	ipv4 or ipv6
	* @return	bool
	*/
	public function valid_ip($ip, $which = '')
	{
		$which = strtolower($which);

		// First check if filter_var is available
		if (is_callable('filter_var'))
		{
			switch ($which) {
				case 'ipv4':
					$flag = FILTER_FLAG_IPV4;
					break;
				case 'ipv6':
					$flag = FILTER_FLAG_IPV6;
					break;
				default:
					$flag = '';
					break;
			}

			return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
		}

		if ($which !== 'ipv6' && $which !== 'ipv4')
		{
			if (strpos($ip, ':') !== FALSE)
			{
				$which = 'ipv6';
			}
			elseif (strpos($ip, '.') !== FALSE)
			{
				$which = 'ipv4';
			}
			else
			{
				return FALSE;
			}
		}

		$func = '_valid_'.$which;
		return $this->$func($ip);
	}

	// --------------------------------------------------------------------

	/**
	* Validate IPv4 Address
	*
	* Updated version suggested by Geert De Deckere
	*
	* @access	protected
	* @param	string
	* @return	bool
	*/
	protected function _valid_ipv4($ip)
	{
		$ip_segments = explode('.', $ip);

		// Always 4 segments needed
		if (count($ip_segments) !== 4)
		{
			return FALSE;
		}
		// IP can not start with 0
		if ($ip_segments[0][0] == '0')
		{
			return FALSE;
		}

		// Check each segment
		foreach ($ip_segments as $segment)
		{
			// IP segments must be digits and can not be
			// longer than 3 digits or greater then 255
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	* Validate IPv6 Address
	*
	* @access	protected
	* @param	string
	* @return	bool
	*/
	protected function _valid_ipv6($str)
	{
		// 8 groups, separated by :
		// 0-ffff per group
		// one set of consecutive 0 groups can be collapsed to ::

		$groups = 8;
		$collapsed = FALSE;

		$chunks = array_filter(
			preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
		);

		// Rule out easy nonsense
		if (current($chunks) == ':' OR end($chunks) == ':')
		{
			return FALSE;
		}

		// PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
		if (strpos(end($chunks), '.') !== FALSE)
		{
			$ipv4 = array_pop($chunks);

			if ( ! $this->_valid_ipv4($ipv4))
			{
				return FALSE;
			}

			$groups--;
		}

		while ($seg = array_pop($chunks))
		{
			if ($seg[0] == ':')
			{
				if (--$groups == 0)
				{
					return FALSE;	// too many groups
				}

				if (strlen($seg) > 2)
				{
					return FALSE;	// long separator
				}

				if ($seg == '::')
				{
					if ($collapsed)
					{
						return FALSE;	// multiple collapsed
					}

					$collapsed = TRUE;
				}
			}
			elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4)
			{
				return FALSE; // invalid segment
			}
		}

		return $collapsed OR $groups == 1;
	}
	
}
?>