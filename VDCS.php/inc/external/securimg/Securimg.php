<?php
class Securimg
{
	const SI_IMAGE_JPEG = 1;
	const SI_IMAGE_PNG  = 2;
	const SI_IMAGE_GIF  = 3;
	const SI_CAPTCHA_STRING	 = 0;
	const SI_CAPTCHA_MATHEMATIC = 1;
	const SI_NAMEAPCE='securimg';
	
	public $namespace		= self::SI_NAMEAPCE;
	public $image_width		= 215;
	public $image_height		= 80;
	public $image_type		= self::SI_IMAGE_PNG;
	
	public $image_bg_color		= '#ffffff';
	public $text_color		= '#707070';
	public $line_color		= '#707070';
	public $noise_color		= '#707070';
	public $text_scale		= 50;
	public $text_transparency	= 50;
	public $use_transparent_text	= false;
	public $code_length		= 6;
	public $case_sensitive		= false;
	public $charset			= 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz23456789';
	public $expiry_time		= 900;
	public $use_wordlist		= false;
	public $perturbation		= 0.75;
	public $num_lines		= 8;
	public $noise_level		= 0;
	public $image_signature		= '';
	public $signature_color		= '#707070';
	public $captcha_type		= self::SI_CAPTCHA_STRING;
	
	protected $basepath = null;
	protected $im,$tmpimg,$bgimg;
	protected $signature_font,$ttf_file,$wordlist_file,$background_directory,$audio_path;
	protected $_code,$code,$code_display,$captcha_code;
	protected $gdbgcolor,$gdtextcolor,$gdlinecolor,$gdsignaturecolor;
	
	protected $iscale = 5;
	
	/**
	 * Create a new securimg object, pass options to set in the constructor.<br />
	 * This can be used to display a captcha, play an audible captcha, or validate an entry
	 * @param array $options
	 * <code>
	 * $options = array(
	 *	 'text_color' => new SecurimgColor('#013020'),
	 *	 'code_length' => 5,
	 *	 'num_lines' => 5,
	 *	 'noise_level' => 3,
	 *	 'font_file' => securimg::getPath() . '/custom.ttf'
	 * );
	 * 
	 * $img = new securimg($options);
	 * </code>
	 */
	public function __construct($options = array())
	{
		$this->basepath = dirname(__FILE__).'/';
		
		if (is_array($options) && sizeof($options) > 0) {
			foreach($options as $prop => $val) {
				$this->$prop = $val;
			}
		}
		
		$this->image_bg_color  = $this->initColor($this->image_bg_color,  '#ffffff');
		$this->text_color	  = $this->initColor($this->text_color,	  '#616161');
		$this->line_color	  = $this->initColor($this->line_color,	  '#616161');
		$this->noise_color	 = $this->initColor($this->noise_color,	 '#616161');
		$this->signature_color = $this->initColor($this->signature_color, '#616161');
		if ($this->text_scale == null || !is_numeric($this->text_scale)) $this->text_scale = 50;
		if ($this->ttf_file == null) $this->ttf_file = VDCS_RES_PATH . 'fonts/AHGBold.ttf';
		$this->signature_font = $this->ttf_file;
		if ($this->wordlist_file == null) $this->wordlist_file = $this->basepath . 'words.txt';
		if ($this->audio_path == null) $this->audio_path = VDCS_RES_PATH . 'audios/';
		$this->bg_path = $this->basepath . 'backgrounds/';
		if ($this->code_length == null || $this->code_length < 1) $this->code_length = 6;
		if ($this->perturbation == null || !is_numeric($this->perturbation)) $this->perturbation = 0.75;
		if ($this->namespace == null || !is_string($this->namespace)) $this->namespace = self::SI_NAMEAPCE;
	}
	public function __destruct()
	{
		unset($this->im,$this->tmpimg,$this->bgimg);
	}
	
	public static function getPath(){return dirname(__FILE__).'/';}
	
	
	public function setCode($s)
	{
		$this->code=$s;
		$this->code_display = $this->code;
		$this->code= ($this->case_sensitive) ? $this->code : strtolower($this->code);
		$this->code_length=strlen($this->code);
	}
	public function getCode()
	{
		$this->initCode();
		return $this->code;
	}
	public function initCode()
	{
		if(!$this->code) $re=$this->createCode();
	}
	
	
	/**
	 * Check a submitted code against the stored value
	 * @param string $code  The captcha code to check
	 * <code>
	 * $code = $_POST['code'];
	 * $img  = new securimg();
	 * if ($img->check($code) == true) {
	 *	 $captcha_valid = true;
	 * } else {
	 *	 $captcha_valid = false;
	 * }
	 * </code>
	 */
	public function check($code)
	{
		$this->code_entered = $code;
		$this->validate();
		return $this->correct_code;
	}
	
	
	/**
	 * Used to serve a captcha image to the browser
	 * @param string $background_image The path to the background image to use
	 * <code> 
	 * $img = new securimg();
	 * $img->code_length = 6;
	 * $img->num_lines   = 5;
	 * $img->noise_level = 5;
	 * 
	 * $img->show(); // sends the image to browser
	 * exit;
	 * </code>
	 */
	public function show($background_image='',$output=true)
	{
		$this->doImage($background_image,$output);
	}
	
	/**
	 * The main image drawing routing, responsible for constructing the entire image and serving it
	 */
	public function doImage($background_image='',$output=true)
	{
		if($background_image != '') {
			if(!is_readable($background_image)){
				if(ins($background_image,'.')<1) $background_image.='.jpg';
				$background_image=$this->bg_path.$background_image;
			}
			if(is_readable($background_image)) $this->bgimg = $background_image;
		}
		
		if( ($this->use_transparent_text == true || $this->bgimg != '') && function_exists('imagecreatetruecolor')) {
			$imagecreate = 'imagecreatetruecolor';
		} else {
			$imagecreate = 'imagecreate';
		}
		$this->im	= $imagecreate($this->image_width, $this->image_height);
		$this->tmpimg	= $imagecreate($this->image_width * $this->iscale, $this->image_height * $this->iscale);
		$this->allocateColors();
		imagepalettecopy($this->tmpimg, $this->im);
		$this->setBackground();
		$this->initCode();
		if ($this->noise_level > 0) $this->drawNoise();
		$this->drawWord();
		if ($this->perturbation > 0 && is_readable($this->ttf_file)) $this->distortedCopy();
		if ($this->num_lines > 0) $this->drawLines();
		if (trim($this->image_signature) != '') $this->addSignature();
		if($output) $this->output();
	}
	
	/**
	 * Sends the appropriate image and cache headers and outputs image to the browser
	 */
	public function output()
	{
		//ob_end_clean();
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		switch ($this->image_type) {
			case self::SI_IMAGE_JPEG:
				header("Content-Type: image/jpeg");
				imagejpeg($this->im, null, 90);
				break;
			case self::SI_IMAGE_GIF:
				header("Content-Type: image/gif");
				imagegif($this->im);
				break;
			default:
				//header('Content-Length: '.getimagesize($this->im));
				header("Content-Type: image/png");
				//imagepng($this->im,_BASE_PATH_ROOT._BASE_DIR_UPLOAD.'vcode.png');
				//utilIO::outputImage(_BASE_PATH_ROOT._BASE_DIR_UPLOAD.'vcode.png');
				imagepng($this->im);
				break;
		}
		imagedestroy($this->im);
		//exit();
	}
	
	
	/**
	 * Output a wav file of the captcha code to the browser
	 * 
	 * <code>
	 * $img = new securimg();
	 * $img->outputAudioFile(); // outputs a wav file to the browser
	 * exit;
	 * </code>
	 */
	public function outputAudioFile($ext='mp3')
	{
		if(!$ext) $ext = 'mp3'; // force wav - mp3 is insecure
		$audio = $this->getAudibleCode($ext);
		
		header("Content-Disposition: attachment; filename=\"securimg_audio.{$ext}\"");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		if(!$_SERVER['PHP_FCGI_MAX_REQUESTS']) header('Content-type: '.$ext=='mp3'?'audio/mpeg':'audio/x-wav');
		header('Content-Length: ' . strlen($audio));
		echo $audio;
		//exit;
	}
	
	
	
	/**
	 * Allocate the colors to be used for the image
	 */
	protected function allocateColors()
	{
		// allocate bg color first for imagecreate
		$this->gdbgcolor = imagecolorallocate($this->im,$this->image_bg_color->r,$this->image_bg_color->g,$this->image_bg_color->b);
		$alpha = intval($this->text_transparency / 100 * 127);
		if ($this->use_transparent_text == true) {
			$this->gdtextcolor = imagecolorallocatealpha($this->im,$this->text_color->r,$this->text_color->g,$this->text_color->b, $alpha);
			$this->gdlinecolor = imagecolorallocatealpha($this->im,$this->line_color->r,$this->line_color->g,$this->line_color->b,$alpha);
			$this->gdnoisecolor = imagecolorallocatealpha($this->im,$this->noise_color->r,$this->noise_color->g,$this->noise_color->b,$alpha);
		}
		else {
			$this->gdtextcolor = imagecolorallocate($this->im,$this->text_color->r,$this->text_color->g,$this->text_color->b);
			$this->gdlinecolor = imagecolorallocate($this->im,$this->line_color->r,$this->line_color->g,$this->line_color->b);
			$this->gdnoisecolor = imagecolorallocate($this->im,$this->noise_color->r, $this->noise_color->g,$this->noise_color->b);
		}
		$this->gdsignaturecolor = imagecolorallocate($this->im,$this->signature_color->r, $this->signature_color->g,$this->signature_color->b);

	}
	
	/**
	 * The the background color, or background image to be used
	 */
	protected function setBackground()
	{
		// set background color of image by drawing a rectangle since imagecreatetruecolor doesn't set a bg color
		imagefilledrectangle($this->im, 0, 0,$this->image_width, $this->image_height,$this->gdbgcolor);
		imagefilledrectangle($this->tmpimg, 0, 0,$this->image_width * $this->iscale, $this->image_height * $this->iscale,$this->gdbgcolor);
		if ($this->bgimg == '') {
			if ($this->background_directory != null &&  is_dir($this->background_directory) && is_readable($this->background_directory)){
				$img = $this->getBackgroundFromDirectory();
				if ($img != false) {
					$this->bgimg = $img;
				}
			}
		}
		if ($this->bgimg == '') return;
		$dat = @getimagesize($this->bgimg);
		if($dat == false) return;
		switch($dat[2]) {
			case 1:  $newim = @imagecreatefromgif($this->bgimg); break;
			case 2:  $newim = @imagecreatefromjpeg($this->bgimg); break;
			case 3:  $newim = @imagecreatefrompng($this->bgimg); break;
			default: return;
		}
		if(!$newim) return;
		imagecopyresized($this->im, $newim, 0, 0, 0, 0,$this->image_width, $this->image_height, imagesx($newim), imagesy($newim));
	}
	
	/**
	 * Scan the directory for a background image to use
	 */
	protected function getBackgroundFromDirectory()
	{
		$images = array();
		if ( ($dh = opendir($this->background_directory)) !== false) {
			while (($file = readdir($dh)) !== false) {
				if (preg_match('/(jpg|gif|png)$/i', $file)) $images[] = $file;
			}
			closedir($dh);
			if (sizeof($images) > 0) {
				return rtrim($this->background_directory, '/') . '/' . $images[rand(0, sizeof($images)-1)];
			}
		}
		return false;
	}
	
	/**
	 * Generates the code or math problem and saves the value to the session
	 */
	protected function createCode()
	{
		$this->code = false;
		switch($this->captcha_type) {
			case self::SI_CAPTCHA_MATHEMATIC:{
				$signs = array('+', '-', 'x');
				$left  = rand(1, 10);
				$right = rand(1, 5);
				$sign  = $signs[rand(0, 2)];
				
				switch($sign) {
					case 'x': $c = $left * $right; break;
					case '-': $c = $left - $right; break;
					default:  $c = $left + $right; break;
				}
				
				$this->code		 = $c;
				$this->code_display = "$left $sign $right";
				break;
			}
			default:{
				if ($this->use_wordlist && is_readable($this->wordlist_file)) {
					$this->code	= $this->readCodeFromFile();
				}
				if ($this->code == false) {
					$this->code	= $this->generateCode($this->code_length);
				}
				$this->code_display = $this->code;
				$this->code		= ($this->case_sensitive) ? $this->code : strtolower($this->code);
			} // default
		}
		return $this->code;
	}
	
	/**
	 * Draws the captcha code on the image
	 */
	protected function drawWord()
	{
		$width2  = $this->image_width * $this->iscale;
		$height2 = $this->image_height * $this->iscale;
		if (!is_readable($this->ttf_file)) {
			imagestring($this->im, $this->text_scale/10, 10, ($this->image_height / 2) - 5, 'Failed to load TTF font file!', $this->gdtextcolor);
		}
		else {
			$tscale=$this->text_scale/100;
			if ($this->perturbation > 0) {
				$font_size = $height2 * $tscale;
				$bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
				$tx = $bb[4] - $bb[0];
				$ty = $bb[5] - $bb[1];
				$x  = floor($width2 / 2 - $tx / 2 - $bb[0]);
				$y  = round($height2 / 2 - $ty / 2 - $bb[1]);
				imagettftext($this->tmpimg, $font_size, 0, $x, $y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
			}
			else {
				$font_size = $this->image_height * $tscale;
				$bb = imageftbbox($font_size, 0, $this->ttf_file, $this->code_display);
				$tx = $bb[4] - $bb[0];
				$ty = $bb[5] - $bb[1];
				$x  = floor($this->image_width / 2 - $tx / 2 - $bb[0]);
				$y  = round($this->image_height / 2 - $ty / 2 - $bb[1]);
				imagettftext($this->im, $font_size, 0, $x, $y, $this->gdtextcolor, $this->ttf_file, $this->code_display);
			}
		}
		// DEBUG
		//$this->im = $this->tmpimg;
		//$this->output();
	}
	
	/**
	 * Copies the captcha image to the final image with distortion applied
	 */
	protected function distortedCopy()
	{
		$numpoles = 3; // distortion factor
		// make array of poles AKA attractor points
		for ($i = 0; $i < $numpoles; ++ $i) {
			$px[$i]  = rand($this->image_width  * 0.2, $this->image_width  * 0.8);
			$py[$i]  = rand($this->image_height * 0.2, $this->image_height * 0.8);
			$rad[$i] = rand($this->image_height * 0.2, $this->image_height * 0.8);
			$tmp	 = ((- $this->frand()) * 0.15) - .15;
			$amp[$i] = $this->perturbation * $tmp;
		}
		$bgCol = imagecolorat($this->tmpimg, 0, 0);
		$width2 = $this->iscale * $this->image_width;
		$height2 = $this->iscale * $this->image_height;
		imagepalettecopy($this->im, $this->tmpimg); // copy palette to final image so text colors come across
		// loop over $img pixels, take pixels from $tmpimg with distortion field
		for ($ix = 0; $ix < $this->image_width; ++ $ix) {
			for ($iy = 0; $iy < $this->image_height; ++ $iy) {
				$x = $ix;
				$y = $iy;
				for ($i = 0; $i < $numpoles; ++ $i) {
					$dx = $ix - $px[$i];
					$dy = $iy - $py[$i];
					if ($dx == 0 && $dy == 0) {
						continue;
					}
					$r = sqrt($dx * $dx + $dy * $dy);
					if ($r > $rad[$i]) {
						continue;
					}
					$rscale = $amp[$i] * sin(3.14 * $r / $rad[$i]);
					$x += $dx * $rscale;
					$y += $dy * $rscale;
				}
				$c = $bgCol;
				$x *= $this->iscale;
				$y *= $this->iscale;
				if ($x >= 0 && $x < $width2 && $y >= 0 && $y < $height2) {
					$c = imagecolorat($this->tmpimg, $x, $y);
				}
				if ($c != $bgCol) { // only copy pixels of letters to preserve any background image
					imagesetpixel($this->im, $ix, $iy, $c);
				}
			}
		}
	}
	
	/**
	 * Draws distorted lines on the image
	 */
	protected function drawLines()
	{
		for ($line = 0; $line < $this->num_lines; ++ $line) {
			$x = $this->image_width * (1 + $line) / ($this->num_lines + 1);
			$x += (0.5 - $this->frand()) * $this->image_width / $this->num_lines;
			$y = rand($this->image_height * 0.1, $this->image_height * 0.9);
			
			$theta = ($this->frand() - 0.5) * M_PI * 0.7;
			$w = $this->image_width;
			$len = rand($w * 0.4, $w * 0.7);
			$lwid = rand(0, 2);
			
			$k = $this->frand() * 0.6 + 0.2;
			$k = $k * $k * 0.5;
			$phi = $this->frand() * 6.28;
			$step = 0.5;
			$dx = $step * cos($theta);
			$dy = $step * sin($theta);
			$n = $len / $step;
			$amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
			$x0 = $x - 0.5 * $len * cos($theta);
			$y0 = $y - 0.5 * $len * sin($theta);
			
			$ldx = round(- $dy * $lwid);
			$ldy = round($dx * $lwid);
			
			for ($i = 0; $i < $n; ++ $i) {
				$x = $x0 + $i * $dx + $amp * $dy * sin($k * $i * $step + $phi);
				$y = $y0 + $i * $dy - $amp * $dx * sin($k * $i * $step + $phi);
				imagefilledrectangle($this->im, $x, $y, $x + $lwid, $y + $lwid, $this->gdlinecolor);
			}
		}
	}
	
	/**
	 * Draws random noise on the image
	 */
	protected function drawNoise()
	{
		if ($this->noise_level > 10) {
			$noise_level = 10;
		} else {
			$noise_level = $this->noise_level;
		}
		$t0 = microtime(true);
		$noise_level *= 125; // an arbitrary number that works well on a 1-10 scale
		$points = $this->image_width * $this->image_height * $this->iscale;
		$height = $this->image_height * $this->iscale;
		$width  = $this->image_width * $this->iscale;
		for ($i = 0; $i < $noise_level; ++$i) {
			$x = rand(10, $width);
			$y = rand(10, $height);
			$size = rand(7, 10);
			if ($x - $size <= 0 && $y - $size <= 0) continue; // dont cover 0,0 since it is used by imagedistortedcopy
			imagefilledarc($this->tmpimg, $x, $y, $size, $size, 0, 360, $this->gdnoisecolor, IMG_ARC_PIE);
		}
		$t1 = microtime(true);
		$t = $t1 - $t0;
		/*
		// DEBUG
		imagestring($this->tmpimg, 5, 25, 30, "$t", $this->gdnoisecolor);
		header('content-type: image/png');
		imagepng($this->tmpimg);
		exit;
		*/
	}
	
	/**
	* Print signature text on image
	*/
	protected function addSignature()
	{
		if ($this->use_gd_font) {
			imagestring($this->im, 5, $this->image_width - (strlen($this->image_signature) * 10), $this->image_height - 20, $this->image_signature, $this->gdsignaturecolor);
		}
		else {
			 
			$bbox = imagettfbbox(10, 0, $this->signature_font, $this->image_signature);
			$textlen = $bbox[2] - $bbox[0];
			$x = $this->image_width - $textlen - 5;
			$y = $this->image_height - 3;
			imagettftext($this->im, 10, 0, $x, $y, $this->gdsignaturecolor, $this->signature_font, $this->image_signature);
		}
	}
	
	/**
	 * Gets the code and returns the binary audio file for the stored captcha code
	 * @param string $format WAV only
	 */
	protected function getAudibleCode($format = 'mp3')
	{
		// override any format other than wav for now
		// this is due to security issues with MP3 files
		if(!$format) $format  = 'mp3';
		$letters = array();
		$code	= $this->getCode();
		for($i = 0; $i < strlen($code); ++$i) {
			$letters[] = $code{$i};
		}
		if ($format == 'mp3') {
			return $this->generateMP3($letters);
		} else {
			return $this->generateWAV($letters);
		}
	}

	/**
	 * Gets a captcha code from a wordlist
	 */
	protected function readCodeFromFile()
	{
		$fp = @fopen($this->wordlist_file, 'rb');
		if (!$fp) return false;
		$fsize = filesize($this->wordlist_file);
		if ($fsize < 128) return false; // too small of a list to be effective
		fseek($fp, rand(0, $fsize - 64), SEEK_SET); // seek to a random position of file from 0 to filesize-64
		$data = fread($fp, 64); // read a chunk from our random position
		fclose($fp);
		$data = preg_replace("/\r?\n/", "\n", $data);
		$start = @strpos($data, "\n", rand(0, 56)) + 1; // random start position
		$end   = @strpos($data, "\n", $start);		  // find end of word
		if ($start === false) {
			return false;
		} else if ($end === false) {
			$end = strlen($data);
		}
		return strtolower(substr($data, $start, $end - $start)); // return a line of the file
	}
	
	/**
	 * Generates a random captcha code from the set character set
	 */
	protected function generateCode()
	{
		$code = '';
		for($i = 1, $cslen = strlen($this->charset); $i <= $this->code_length; ++$i) {
			$code .= $this->charset{rand(0, $cslen - 1)};
		}
		//return 'testing';  // debug, set the code to given string
		return $code;
	}
	
	/**
	 * Checks the entered code against the value stored in the session or sqlite database, handles case sensitivity
	 * Also clears the stored codes if the code was entered correctly to prevent re-use
	 */
	protected function validate()
	{
		$code = $this->getCode();
		// returns stored code, or an empty string if no stored code was found
		// checks the session and sqlite database if enabled
		if ($this->case_sensitive == false && preg_match('/[A-Z]/', $code)) {
			// case sensitive was set from securimg_show.php but not in class
			// the code saved in the session has capitals so set case sensitive to true
			$this->case_sensitive = true;
		}
		$code_entered = trim( (($this->case_sensitive) ? $this->code_entered  : strtolower($this->code_entered)));
		$this->correct_code = false;
		if ($code != '') {
			if ($code == $code_entered) {
				$this->correct_code = true;
			}
		}
	}
	
	/**
	 * Checks to see if the captcha code has expired and cannot be used
	 * @param unknown_type $creation_time
	 */
	protected function isCodeExpired($creation_time)
	{
		$expired = true;
		if (!is_numeric($this->expiry_time) || $this->expiry_time < 1) {
			$expired = false;
		} else if (time() - $creation_time < $this->expiry_time) {
			$expired = false;
		}
		return $expired;
	}
	
	/**
	 * 
	 * Generate an MP3 audio file of the captcha image
	 * 
	 * @deprecated 3.0
	 */
	protected function generateMP3($letters)
	{
		$data_len    = 0;
		$files       = array();
		$out_data    = '';
		foreach ($letters as $letter) {
			$filename = $this->audio_path .'w'.PATH_SEPARATORS. strtoupper($letter) . '.mp3';
			$fp   = fopen($filename, 'rb');
			$data = fread($fp, filesize($filename)); // read file in
			$this->scrambleAudioData($data, 'mp3');
			$out_data .= $data;
			fclose($fp);
		}
		return $out_data;
	}
	
	/**
	 * Generate a wav file given the $letters in the code
	 * @todo Add ability to merge 2 sound files together to have random background sounds
	 * @param array $letters
	 * @return string The binary contents of the wav file
	 */
	protected function generateWAV($letters)
	{
		$data_len		= 0;
		$files			= array();
		$out_data		= '';
		$out_channels		= 0;
		$out_samplert		= 0;
		$out_bpersample		= 0;
		$numSamples		= 0;
		$removeChunks		= array('LIST', 'DISP', 'NOTE');
		
		for ($i = 0; $i < sizeof($letters); ++$i) {
			$letter   = $letters[$i];
			$filename = $this->audio_path .'w'.PATH_SEPARATORS. strtoupper($letter) . '.wav';
			$file	 = array();
			$data	 = @file_get_contents($filename);
			
			if ($data === false) {
				// echo "Failed to read $filename";
				return $this->audioError();
			}

			$header = substr($data, 0, 36);
			$info   = unpack('NChunkID/VChunkSize/NFormat/NSubChunk1ID/'
					.'VSubChunk1Size/vAudioFormat/vNumChannels/'
					.'VSampleRate/VByteRate/vBlockAlign/vBitsPerSample',
					$header);
			
			$dataPos	= strpos($data, 'data');
			$out_channels   = $info['NumChannels'];
			$out_samplert   = $info['SampleRate'];
			$out_bpersample = $info['BitsPerSample'];
			
			if ($dataPos === false) {
				// wav file with no data?
				// echo "Failed to find DATA segment in $filename";
				return $this->audioError();
			}
			
			if ($info['AudioFormat'] != 1) {
				// only work with PCM audio
				// echo "$filename was not PCM audio, only PCM is supported";
				return $this->audioError();
			}
			
			if ($info['SubChunk1Size'] != 16 && $info['SubChunk1Size'] != 18) {
				// probably unsupported extension
				// echo "Bad SubChunk1Size in $filename - Size was {$info['SubChunk1Size']}";
				return $this->audioError();
			}
			
			if ($info['SubChunk1Size'] > 16) {
				$header .= substr($data, 36, $info['SubChunk1Size'] - 16);
			}
			
			if ($i == 0) {
				// create the final file's header, size will be adjusted later
				$out_data = $header . 'data';
			}
			
			$removed = 0;
			foreach($removeChunks as $chunk) {
				$chunkPos = strpos($data, $chunk);
				if ($chunkPos !== false) {
					$listSize = unpack('VSize', substr($data, $chunkPos + 4, 4));
					
					$data = substr($data, 0, $chunkPos) .
							substr($data, $chunkPos + 8 + $listSize['Size']);
							
					$removed += $listSize['Size'] + 8;
				}
			}
			
			$dataSize	= unpack('VSubchunk2Size', substr($data, $dataPos + 4, 4));
			$dataSize['Subchunk2Size'] -= $removed;
			$out_data   .= substr($data, $dataPos + 8, $dataSize['Subchunk2Size'] * ($out_bpersample / 8));
			$numSamples += $dataSize['Subchunk2Size'];
		}

		$filesize  = strlen($out_data);
		$chunkSize = $filesize - 8;
		$dataCSize = $numSamples;
		
		$out_data = substr_replace($out_data, pack('V', $chunkSize), 4, 4);
		$out_data = substr_replace($out_data, pack('V', $numSamples), 40 + ($info['SubChunk1Size'] - 16), 4);

		$this->scrambleAudioData($out_data, 'wav');
		return $out_data;
	}
	
	/**
	 * Randomizes the audio data to add noise and prevent binary recognition
	 * @param string $data  The binary audio file data
	 * @param string $format The format of the sound file (wav only)
	 */
	protected function scrambleAudioData(&$data, $format='mp3')
	{
		if ($format == 'wav') {
			$start = strpos($data, 'data') + 4; // look for "data" indicator
			if ($start === false) $start = 44;  // if not found assume 44 byte header
		} else { // mp3
			$start = 4; // 4 byte (32 bit) frame header
		}
		
		$start  += rand(1, 64); // randomize starting offset
		$datalen = strlen($data) - $start-256; // leave last 256 bytes unchanged
		
		for ($i = $start; $i < $datalen; $i += 64) {
			$ch = ord($data{$i});
			if ($ch < 9 || $ch > 119) continue;
			$data{$i} = chr($ch + rand(-8, 8));
		}
		/*
		$step	= 1;
		for ($i = $start; $i < $datalen; $i += $step) {
			$ch = ord($data{$i});
			if ($ch == 0 || $ch == 255) continue;
			
			if ($ch < 16 || $ch > 239) {
				$ch += rand(-6, 6);
			} else {
				$ch += rand(-12, 12);
			}
			
			if ($ch < 0) $ch = 0; else if ($ch > 255) $ch = 255;

			$data{$i} = chr($ch);
			
			$step = rand(1,4);
		}
		*/
		return $data;
	}
	
	/**
	 * Return a wav file saying there was an error generating file
	 * 
	 * @return string The binary audio contents
	 */
	protected function audioError()
	{
		return @file_get_contents($this->audio_path.'error.wav');
	}
	
	protected function frand() {return 0.0001 * rand(0,9999);}
	
	/**
	 * Convert an html color code to a SecurimgColor
	 * @param string $color
	 * @param SecurimgColor $default The defalt color to use if $color is invalid
	 */
	protected function initColor($color, $default)
	{
		if ($color == null) {
			return new SecurimgColor($default);
		} else if (is_string($color)) {
			try {
				return new SecurimgColor($color);
			} catch(Exception $e) {
				return new SecurimgColor($default);
			}
		} else if (is_array($color) && sizeof($color) == 3) {
			return new SecurimgColor($color[0], $color[1], $color[2]);
		} else {
			return new SecurimgColor($default);
		}
	}
}


/**
 * Color object for securimg CAPTCHA
 *
 * @version 3.0
 * @since 2.0
 * @package securimg
 * @subpackage classes
 *
 */
class SecurimgColor
{
	public $r;
	public $g;
	public $b;

	/**
	 * Create a new SecurimgColor object.<br />
	 * Constructor expects 1 or 3 arguments.<br />
	 * When passing a single argument, specify the color using HTML hex format,<br />
	 * when passing 3 arguments, specify each RGB component (from 0-255) individually.<br />
	 * $color = new SecurimgColor('#0080FF') or <br />
	 * $color = new SecurimgColor(0, 128, 255)
	 * 
	 * @param string $color
	 * @throws Exception
	 */
	public function __construct($color = '#ffffff')
	{
		$args = func_get_args();
		if (sizeof($args) == 0) {
			$this->r = 255;
			$this->g = 255;
			$this->b = 255;
		} else if (sizeof($args) == 1) {
			// set based on html code
			if (substr($color, 0, 1) == '#') {
				$color = substr($color, 1);
			}
			if (strlen($color) != 3 && strlen($color) != 6) {
				throw new InvalidArgumentException('Invalid HTML color code passed to SecurimgColor');
			}
			$this->constructHTML($color);
		} else if (sizeof($args) == 3) {
			$this->constructRGB($args[0], $args[1], $args[2]);
		} else {
			throw new InvalidArgumentException('SecurimgColor constructor expects 0, 1 or 3 arguments; ' . sizeof($args) . ' given');
		}
	}
	
	/**
	 * Construct from an rgb triplet
	 * @param int $red The red component, 0-255
	 * @param int $green The green component, 0-255
	 * @param int $blue The blue component, 0-255
	 */
	protected function constructRGB($red, $green, $blue)
	{
		if ($red < 0)	 $red   = 0;
		if ($red > 255)   $red   = 255;
		if ($green < 0)   $green = 0;
		if ($green > 255) $green = 255;
		if ($blue < 0)	$blue  = 0;
		if ($blue > 255)  $blue  = 255;
		
		$this->r = $red;
		$this->g = $green;
		$this->b = $blue;
	}
	
	/**
	 * Construct from an html hex color code
	 * @param string $color
	 */
	protected function constructHTML($color)
	{
		if (strlen($color) == 3) {
			$red   = str_repeat(substr($color, 0, 1), 2);
			$green = str_repeat(substr($color, 1, 1), 2);
			$blue  = str_repeat(substr($color, 2, 1), 2);
		} else {
			$red   = substr($color, 0, 2);
			$green = substr($color, 2, 2);
			$blue  = substr($color, 4, 2); 
		}
		
		$this->r = hexdec($red);
		$this->g = hexdec($green);
		$this->b = hexdec($blue);
	}
}
