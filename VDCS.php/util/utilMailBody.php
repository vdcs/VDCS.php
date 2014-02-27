<?
class utilMailBody
{
	const LE				= "\n";
	const CRLF				= "\r\n";
	
	const CharSet				= 'iso-8859-1';		//'utf-8'
	const ContentType			= 'text/plain';
	const Encoding				= '8bit';
	
	
	public function AddrAppend($type, $addr)
	{
		$addr_str = $type . ': ';
		$addresses = array();
		foreach ($addr as $a) {
			$addresses[] = self::AddrFormat($a);
		}
		$addr_str .= implode(', ', $addresses);
		$addr_str .= self::LE;
		
		return $addr_str;
	}
	public function AddrFormat($addr)
	{
		if (empty($addr[1])) {
			return self::SecureHeader($addr[0]);
		} else {
			return self::EncodeHeader(self::SecureHeader($addr[1]), 'phrase') . " <" . self::SecureHeader($addr[0]) . ">";
		}
	}
	public function SecureHeader($str)
	{
		return trim(str_replace(array("\r", "\n"), '', $str));
	}
	
	public function EncodeString($str, $encoding = 'base64')
	{
		$encoded = '';
		switch(strtolower($encoding)) {
			case 'base64':
				$encoded = chunk_split(base64_encode($str), 76, self::LE);
				break;
			case '7bit':
			case '8bit':
				$encoded = self::FixEOL($str);
				//Make sure it ends with a line break
				if (substr($encoded, -(strlen(self::LE))) != self::LE)
				  $encoded .= self::LE;
				break;
			case 'binary':
				$encoded = $str;
				break;
			case 'quoted-printable':
				$encoded = self::EncodeQP($str);
				break;
			default:
				//$this.SetError($this.Lang('encoding') . $encoding);		//?????
				break;
		}
		return $encoded;
	}
	/**
	* Encodes attachment in requested format.
	* Returns an empty string on failure.
	* @param string $path The full path to the file
	* @param string $encoding The encoding to use; one of 'base64', '7bit', '8bit', 'binary', 'quoted-printable'
	* @throws phpmailerException
	* @see EncodeFile()
	* @access protected
	* @return string
	*/
	protected function EncodeFile($path, $encoding = 'base64')
	{
		try {
			if (!is_readable($path)) {
				return false;
			}
			//  if (!function_exists('get_magic_quotes')) {
			//    function get_magic_quotes() {
			//      return false;
			//    }
			//  }
			$magic_quotes = get_magic_quotes_runtime();
			if ($magic_quotes) {
				if (version_compare(PHP_VERSION, '5.3.0', '<')) {
					set_magic_quotes_runtime(0);
				} else {
					ini_set('magic_quotes_runtime', 0); 
				}
			}
			$file_buffer  = file_get_contents($path);
			$file_buffer  = self::EncodeString($file_buffer, $encoding);
			if ($magic_quotes) {
				if (version_compare(PHP_VERSION, '5.3.0', '<')) {
					set_magic_quotes_runtime($magic_quotes);
				} else {
					ini_set('magic_quotes_runtime', $magic_quotes); 
				}
			}
			return $file_buffer;
		} catch (Exception $e) {
			//$this.SetError($e.getMessage());
			return '';
		}
	}
	public function EncodeHeader($str, $position = 'text')
	{
		$x = 0;
		switch (strtolower($position)) {
			case 'phrase':
				if (!preg_match('/[\200-\377]/', $str)) {
					// Can't use addslashes as we don't know what value has magic_quotes_sybase
					$encoded = addcslashes($str, "\0..\37\177\\\"");
					if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str)) {
						return ($encoded);
					} else {
						return ("\"$encoded\"");
					}
				}
				$x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
				break;
			case 'comment':
				$x = preg_match_all('/[()"]/', $str, $matches);
				// Fall-through
			case 'text':
			default:
				$x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
				break;
		}
		
		if ($x == 0) {
			return ($str);
		}
		
		$maxlen = 75 - 7 - strlen(self::CharSet);
		// Try to select the encoding which should produce the shortest output
		if (strlen($str)/3 < $x) {
			$encoding = 'B';
			if (function_exists('mb_strlen') && self::HasMultiBytes($str)) {
				// Use a custom function which correctly encodes and wraps long
				// multibyte strings without breaking lines within a character
				$encoded = self::Base64EncodeWrapMB($str, "\n");
			} else {
				$encoded = base64_encode($str);
				$maxlen -= $maxlen % 4;
				$encoded = trim(chunk_split($encoded, $maxlen, "\n"));
			}
		} else {
			$encoding = 'Q';
			$encoded = self::EncodeQ($str, $position);
			$encoded = self::WrapText($encoded, $maxlen, true);
			$encoded = str_replace('='.self::CRLF, "\n", trim($encoded));
		}
		
		$encoded = preg_replace('/^(.*)$/m', " =?".self::CharSet."?$encoding?\\1?=", $encoded);
		$encoded = trim(str_replace("\n", self::LE, $encoded));
		
		return $encoded;
	}
	public function HasMultiBytes($str)
	{
		if (function_exists('mb_strlen')) {
			return (strlen($str) > mb_strlen($str, self::CharSet));
		} else { // Assume no multibytes (we can't handle without mbstring functions anyway)
			return false;
		}
	}
	public function Base64EncodeWrapMB($str, $lf=null)
	{
		$start = "=?".self::CharSet."?B?";
		$end = "?=";
		$encoded = "";
		if ($lf === null){
			$lf = self::LE;
		}
		
		$mb_length = mb_strlen($str, self::CharSet);
		// Each line must have length <= 75, including $start and $end
		$length = 75 - strlen($start) - strlen($end);
		// Average multi-byte ratio
		$ratio = $mb_length / strlen($str);
		// Base64 has a 4:3 ratio
		$offset = $avgLength = floor($length * $ratio * .75);
		
		for ($i = 0; $i < $mb_length; $i += $offset) {
			$lookBack = 0;
		
			do {
				$offset = $avgLength - $lookBack;
				$chunk = mb_substr($str, $i, $offset, self::CharSet);
				$chunk = base64_encode($chunk);
				$lookBack++;
			}
			while (strlen($chunk) > $length);
		
			$encoded .= $chunk . $lf;
		}
		
		// Chomp the last linefeed
		$encoded = substr($encoded, 0, -strlen($lf));
		return $encoded;
	}
	
	public function WrapText($message, $length, $qp_mode = false)
	{
		$soft_break = ($qp_mode) ? sprintf(" =%s", self::LE) : self::LE;
		// If utf-8 encoding is used, we will need to make sure we don't
		// split multibyte characters when we wrap
		$is_utf8 = (strtolower(self::CharSet) == "utf-8");
		$lelen = strlen(self::LE);
		$crlflen = strlen(self::CRLF);
		
		$message = self::FixEOL($message);
		if (substr($message, -$lelen) == self::LE) {
			$message = substr($message, 0, -$lelen);
		}
		
		$line = explode(self::LE, $message);   // Magic. We know FixEOL uses $LE
		$message = '';
		for ($i = 0 ;$i < count($line); $i++) {
			$line_part = explode(' ', $line[$i]);
			$buf = '';
			for ($e = 0; $e<count($line_part); $e++) {
				$word = $line_part[$e];
				if ($qp_mode and (strlen($word) > $length)) {
					$space_left = $length - strlen($buf) - $crlflen;
					if ($e != 0) {
						if ($space_left > 20) {
							$len = $space_left;
							if ($is_utf8) {
								$len = self::UTF8CharBoundary($word, $len);
							} elseif (substr($word, $len - 1, 1) == "=") {
								$len--;
							} elseif (substr($word, $len - 2, 1) == "=") {
								$len -= 2;
							}
							$part = substr($word, 0, $len);
							$word = substr($word, $len);
							$buf .= ' ' . $part;
							$message .= $buf . sprintf("=%s", self::CRLF);
						} else {
							$message .= $buf . $soft_break;
						}
						$buf = '';
					}
					while (strlen($word) > 0) {
						$len = $length;
						if ($is_utf8) {
							$len = self::UTF8CharBoundary($word, $len);
						} elseif (substr($word, $len - 1, 1) == "=") {
							$len--;
						} elseif (substr($word, $len - 2, 1) == "=") {
							$len -= 2;
						}
						$part = substr($word, 0, $len);
						$word = substr($word, $len);
						
						if (strlen($word) > 0) {
							$message .= $part . sprintf("=%s", self::CRLF);
						} else {
							$buf = $part;
						}
					}
				} else {
					$buf_o = $buf;
					$buf .= ($e == 0) ? $word : (' ' . $word);
					
					if (strlen($buf) > $length and $buf_o != '') {
						$message .= $buf_o . $soft_break;
						$buf = $word;
					}
				}
			}
			$message .= $buf . self::CRLF;
		}
		return $message;
	}
	
	public function FixEOL($str)
	{
		// condense down to \n
		$nstr = str_replace(array("\r\n", "\r"), "\n", $str);
		// Now convert LE as needed
		if (self::LE !== "\n") {
			$nstr = str_replace("\n", self::LE, $nstr);
		}
		return  $nstr;
	}
	public function UTF8CharBoundary($encodedText, $maxLength)
	{
		$foundSplitPos = false;
		$lookBack = 3;
		while (!$foundSplitPos) {
			$lastChunk = substr($encodedText, $maxLength - $lookBack, $lookBack);
			$encodedCharPos = strpos($lastChunk, "=");
			if ($encodedCharPos !== false) {
				// Found start of encoded character byte within $lookBack block.
				// Check the encoded byte value (the 2 chars after the '=')
				$hex = substr($encodedText, $maxLength - $lookBack + $encodedCharPos + 1, 2);
				$dec = hexdec($hex);
				if ($dec < 128) { // Single byte character.
					// If the encoded char was found at pos 0, it will fit
					// otherwise reduce maxLength to start of the encoded char
					$maxLength = ($encodedCharPos == 0) ? $maxLength :
					$maxLength - ($lookBack - $encodedCharPos);
					$foundSplitPos = true;
				} elseif ($dec >= 192) { // First byte of a multi byte character
					// Reduce maxLength to split at start of character
					$maxLength = $maxLength - ($lookBack - $encodedCharPos);
					$foundSplitPos = true;
				} elseif ($dec < 192) { // Middle byte of a multi byte character, look further back
					$lookBack += 3;
				}
			} else {
				// No encoded character found
				$foundSplitPos = true;
			}
		}
		return $maxLength;
	}
	
	public function EncodeQ($str, $position = 'text')
	{
		//There should not be any EOL in the string
		$pattern="";
		$encoded = str_replace(array("\r", "\n"), '', $str);
		switch (strtolower($position)) {
			case 'phrase':
				$pattern = '^A-Za-z0-9!*+\/ -';
				break;
			case 'comment':
				$pattern = '\(\)"';
				//note that we dont break here!
				//for this reason we build the $pattern withoud including delimiters and []
			case 'text':
			default:
				//Replace every high ascii, control =, ? and _ characters
				//We put \075 (=) as first value to make sure it's the first one in being converted, preventing double encode
				$pattern = '\075\000-\011\013\014\016-\037\077\137\177-\377' . $pattern;
				break;
		}
		
		if (preg_match_all("/[{$pattern}]/", $encoded, $matches)) {
			foreach (array_unique($matches[0]) as $char) {
				$encoded = str_replace($char, '=' . sprintf('%02X', ord($char)), $encoded);
			}
		}
		
		//Replace every spaces to _ (more readable than =20)
		return str_replace(' ', '_', $encoded);
	}
	/**
	* Encode string to RFC2045 (6.7) quoted-printable format
	* Uses a PHP5 stream filter to do the encoding about 64x faster than the old version
	* Also results in same content as you started with after decoding
	* @see EncodeQPphp()
	* @access public
	* @param string $string the text to encode
	* @param integer $line_max Number of chars allowed on a line before wrapping
	* @param boolean $space_conv Dummy param for compatibility with existing EncodeQP function
	* @return string
	* @author Marcus Bointon
	*/
	public function EncodeQP($string, $line_max = 76, $space_conv = false)
	{
		if (function_exists('quoted_printable_encode')) { //Use native function if it's available (>= PHP5.3)
			return quoted_printable_encode($string);
		}
		$filters = stream_get_filters();
		if (!in_array('convert.*', $filters)) { //Got convert stream filter?
			return self::EncodeQPphp($string, $line_max, $space_conv); //Fall back to old implementation
		}
		$fp = fopen('php://temp/', 'r+');
		$string = preg_replace('/\r\n?/', self::LE, $string); //Normalise line breaks
		$params = array('line-length' => $line_max, 'line-break-chars' => self::LE);
		$s = stream_filter_append($fp, 'convert.quoted-printable-encode', STREAM_FILTER_READ, $params);
		fputs($fp, $string);
		rewind($fp);
		$out = stream_get_contents($fp);
		stream_filter_remove($s);
		$out = preg_replace('/^\./m', '=2E', $out); //Encode . if it is first char on a line, workaround for bug in Exchange
		fclose($fp);
		return $out;
	}
	public function EncodeQPphp( $input = '', $line_max = 76, $space_conv = false)
	{
		$hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
		$lines = preg_split('/(?:\r\n|\r|\n)/', $input);
		$eol = "\r\n";
		$escape = '=';
		$output = '';
		while( list(, $line) = each($lines) ) {
			$linlen = strlen($line);
			$newline = '';
			for($i = 0; $i < $linlen; $i++) {
				$c = substr( $line, $i, 1 );
				$dec = ord( $c );
				if ( ( $i == 0 ) && ( $dec == 46 ) ) { // convert first point in the line into =2E
					$c = '=2E';
				}
				if ( $dec == 32 ) {
					if ( $i == ( $linlen - 1 ) ) { // convert space at eol only
						$c = '=20';
					} else if ( $space_conv ) {
						$c = '=20';
					}
				} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) { // always encode "\t", which is *not* required
					$h2 = (integer)floor($dec/16);
					$h1 = (integer)floor($dec%16);
					$c = $escape.$hex[$h2].$hex[$h1];
				}
				if ( (strlen($newline) + strlen($c)) >= $line_max ) { // CRLF is not counted
					$output .= $newline.$escape.$eol; //  soft line break; " =\r\n" is okay
					$newline = '';
					// check if newline first character will be point or not
					if ( $dec == 46 ) {
						$c = '=2E';
					}
				}
				$newline .= $c;
			} // end of for
			$output .= $newline.$eol;
		} // end of while
		return $output;
	}
	
	
	
	
	/**
	*  Returns a formatted header line.
	* @access public
	* @param string $name
	* @param string $value
	* @return string
	*/
	public function HeaderLine($name, $value) {
		return $name . ': ' . $value . self::LE;
	}
	
	/**
	* Returns a formatted mail line.
	* @access public
	* @param string $value
	* @return string
	*/
	public function TextLine($value) {
		return $value . self::LE;
	}
	
	/**
	* Returns the end of a message boundary.
	* @access protected
	* @param string $boundary
	* @return string
	*/
	protected function EndBoundary($boundary) {
		return self::LE . '--' . $boundary . '--' . self::LE;
	}
	
	public static function ValidateAddress($address)
	{
		if ((defined('PCRE_VERSION')) && (version_compare(PCRE_VERSION, '8.0') >= 0)) {
			return preg_match('/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)((?>(?>(?>((?>(?>(?>\x0D\x0A)?[	 ])+|(?>[	 ]*\x0D\x0A)?[	 ]+)?)(\((?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*(?2)")(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z0-9-]{64,})(?1)(?>([a-z0-9](?>[a-z0-9-]*[a-z0-9])?)(?>(?1)\.(?!(?1)[a-z0-9-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f0-9]{1,4})(?>:(?6)){7}|(?!(?:.*[a-f0-9][:\]]){7,})((?6)(?>:(?6)){0,5})?::(?7)?))|(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f0-9]:){5,})(?8)?::(?>((?6)(?>:(?6)){0,3}):)?))?(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])(?>\.(?9)){3}))\])(?1)$/isD', $address);
		} elseif (function_exists('filter_var')) { //Introduced in PHP 5.2
			if(filter_var($address, FILTER_VALIDATE_EMAIL) === FALSE) {
				return false;
			} else {
				return true;
			}
		} else {
			return preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $address);
		}
	}
	public static function RFCDate()
	{
		$tz = date('Z');
		$tzs = ($tz < 0) ? '-' : '+';
		$tz = abs($tz);
		$tz = (int)($tz/3600)*100 + ($tz%3600)/60;
		$result = sprintf("%s %s%04d", date('D, j M Y H:i:s'), $tzs, $tz);
		return $result;
	}
	public static function _mime_types($ext = '')
	{
		$mimes = array(
			'xl'    =>  'application/excel',
			'hqx'   =>  'application/mac-binhex40',
			'cpt'   =>  'application/mac-compactpro',
			'bin'   =>  'application/macbinary',
			'doc'   =>  'application/msword',
			'word'  =>  'application/msword',
			'class' =>  'application/octet-stream',
			'dll'   =>  'application/octet-stream',
			'dms'   =>  'application/octet-stream',
			'exe'   =>  'application/octet-stream',
			'lha'   =>  'application/octet-stream',
			'lzh'   =>  'application/octet-stream',
			'psd'   =>  'application/octet-stream',
			'sea'   =>  'application/octet-stream',
			'so'    =>  'application/octet-stream',
			'oda'   =>  'application/oda',
			'pdf'   =>  'application/pdf',
			'ai'    =>  'application/postscript',
			'eps'   =>  'application/postscript',
			'ps'    =>  'application/postscript',
			'smi'   =>  'application/smil',
			'smil'  =>  'application/smil',
			'mif'   =>  'application/vnd.mif',
			'xls'   =>  'application/vnd.ms-excel',
			'ppt'   =>  'application/vnd.ms-powerpoint',
			'wbxml' =>  'application/vnd.wap.wbxml',
			'wmlc'  =>  'application/vnd.wap.wmlc',
			'dcr'   =>  'application/x-director',
			'dir'   =>  'application/x-director',
			'dxr'   =>  'application/x-director',
			'dvi'   =>  'application/x-dvi',
			'gtar'  =>  'application/x-gtar',
			'php3'  =>  'application/x-httpd-php',
			'php4'  =>  'application/x-httpd-php',
			'php'   =>  'application/x-httpd-php',
			'phtml' =>  'application/x-httpd-php',
			'phps'  =>  'application/x-httpd-php-source',
			'js'    =>  'application/x-javascript',
			'swf'   =>  'application/x-shockwave-flash',
			'sit'   =>  'application/x-stuffit',
			'tar'   =>  'application/x-tar',
			'tgz'   =>  'application/x-tar',
			'xht'   =>  'application/xhtml+xml',
			'xhtml' =>  'application/xhtml+xml',
			'zip'   =>  'application/zip',
			'mid'   =>  'audio/midi',
			'midi'  =>  'audio/midi',
			'mp2'   =>  'audio/mpeg',
			'mp3'   =>  'audio/mpeg',
			'mpga'  =>  'audio/mpeg',
			'aif'   =>  'audio/x-aiff',
			'aifc'  =>  'audio/x-aiff',
			'aiff'  =>  'audio/x-aiff',
			'ram'   =>  'audio/x-pn-realaudio',
			'rm'    =>  'audio/x-pn-realaudio',
			'rpm'   =>  'audio/x-pn-realaudio-plugin',
			'ra'    =>  'audio/x-realaudio',
			'wav'   =>  'audio/x-wav',
			'bmp'   =>  'image/bmp',
			'gif'   =>  'image/gif',
			'jpeg'  =>  'image/jpeg',
			'jpe'   =>  'image/jpeg',
			'jpg'   =>  'image/jpeg',
			'png'   =>  'image/png',
			'tiff'  =>  'image/tiff',
			'tif'   =>  'image/tiff',
			'eml'   =>  'message/rfc822',
			'css'   =>  'text/css',
			'html'  =>  'text/html',
			'htm'   =>  'text/html',
			'shtml' =>  'text/html',
			'log'   =>  'text/plain',
			'text'  =>  'text/plain',
			'txt'   =>  'text/plain',
			'rtx'   =>  'text/richtext',
			'rtf'   =>  'text/rtf',
			'xml'   =>  'text/xml',
			'xsl'   =>  'text/xml',
			'mpeg'  =>  'video/mpeg',
			'mpe'   =>  'video/mpeg',
			'mpg'   =>  'video/mpeg',
			'mov'   =>  'video/quicktime',
			'qt'    =>  'video/quicktime',
			'rv'    =>  'video/vnd.rn-realvideo',
			'avi'   =>  'video/x-msvideo',
			'movie' =>  'video/x-sgi-movie'
		);
		return (!isset($mimes[strtolower($ext)])) ? 'application/octet-stream' : $mimes[strtolower($ext)];
	}
}
/*
Date: Thu, 4 Apr 2013 00:36:52 +0800
Return-Path: send_bug@bodmail.cn
To: Finco <fincos@qq.com>
From: send_bug@bodmail.cn
Reply-To: send_bug@bodmail.cn
Subject: =?UTF-8?B?R2FtZSvmsrnnhY7popjor5c=?=
Message-ID: <a7bda0a089e99091e6a91089017175da@php.vdcs.com>
X-Priority: 3
X-Mailer: PHPMailer 5.2.4 (http://code.google.com/a/apache-extras.org/p/phpmailer/)
MIME-Version: 1.0
Content-Transfer-Encoding: 8bit
Content-Type: text/html; charset=UTF-8


我标注了“纯属杜撰”哦！(2013-04-04 00:36:03)
Time: 2013-04-04 00:36:52
*/
