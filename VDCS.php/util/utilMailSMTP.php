<?
class utilMailSMTP
{
	public $CRLF = "\r\n";
	protected $_data=array();
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unset($this->_data);
	}
	
	
	public function setData($data)
	{
		$this->_data=$data;
		$this->_data['bad_rcpt']=array();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function connected()
	{
		if(!empty($this->conn)) {
			$sock_status = socket_get_status($this->conn);
			if($sock_status["eof"]) {
				// the socket is valid but we are not connected
				$this->Close();
				return false;
			}
			return true; // everything looks good
		}
		return false;
	}
	
	public function connect($tval=30)
	{
		$this->host=$this->_data['cfg']['server'];
		if($this->_data['cfg']['user'] && $this->_data['cfg']['pass']) $this->_data['isauth']=true;
		if(!$this->conn=fsockopen($this->_data['cfg']['server'],$this->_data['cfg']['port'],$errno,$errstr,$tval)){
			return false;
		}
		//stream_set_blocking($this->conn,true);
		
		// SMTP server can take longer to respond, give longer timeout for first read
		// Windows does not have support for this timeout function
		if(substr(PHP_OS, 0, 3) != "WIN") {
			$max = ini_get('max_execution_time');
			if ($max != 0 && $tval > $max) { // don't bother if unlimited
				@set_time_limit($tval);
			}
			stream_set_timeout($this->conn, $tval, 0);
		}
		
		$announce = $this->getLines();
		dcsLog('SMTP connect announce: '.$announce);
		
		return true;
	}
	
	public function Auth()
	{
		if(!$this->_data['isauth']) return true;
		$username=$this->_data['cfg']['user'];
		$password=$this->_data['cfg']['pass'];
		$authtype='LOGIN';
		switch ($authtype) {
			case 'PLAIN':
				// Start authentication
				$rply=$this->sends("AUTH PLAIN");
				$code = substr($rply,0,3);
				if($code != 334) {
					$this->error =
					array("error" => "AUTH not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
					return false;
				}
				
				// Send encoded username and password
				$rply=$this->sends(base64_encode("\0".$username."\0".$password));
				$code = substr($rply,0,3);
				if($code != 235) {
					$this->error =
					array("error" => "Authentication not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
					return false;
				}
				break;
			case 'LOGIN':
				// Start authentication
				$rply=$this->sends("AUTH LOGIN");
				$code = substr($rply,0,3);
				if($code != 334) {
					$this->error =
					array("error" => "AUTH not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
					return false;
				}
				
				// Send encoded username
				$rply=$this->sends(base64_encode($username));
				$code = substr($rply,0,3);
				if($code != 334) {
					$this->error =
					array("error" => "Username not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
					return false;
				}
				
				// Send encoded password
				$rply=$this->sends(base64_encode($password));
				$code = substr($rply,0,3);
				if($code != 235) {
					$this->error =
					array("error" => "Password not accepted from server",
					  "smtp_code" => $code,
					  "smtp_msg" => substr($rply,4));
					return false;
				}
				break;
			case 'NTLM':
				/*
				* ntlm_sasl_client.php
				** Bundled with Permission
				**
				** How to telnet in windows: http://technet.microsoft.com/en-us/library/aa995718%28EXCHG.65%29.aspx
				** PROTOCOL Documentation http://curl.haxx.se/rfc/ntlm.html#ntlmSmtpAuthentication
				*/
				//include_once('ntlm_sasl_client.php');
				$temp = new stdClass();
				$ntlm_client = new ntlm_sasl_client_class;
				if(! $ntlm_client->Initialize($temp)){//let's test if every function its available
					$this->error = array("error" => $temp->error);
					return false;
				}
				$msg1 = $ntlm_client->TypeMsg1($realm, $workstation);//msg1
				
				$rply=$this->sends("AUTH NTLM " . base64_encode($msg1));
				$code = substr($rply,0,3);
				if($code != 334) {
					$this->error =
					array("error" => "AUTH not accepted from server",
					      "smtp_code" => $code,
					      "smtp_msg" => substr($rply,4));
					return false;
				}
				
				$challange = substr($rply,3);//though 0 based, there is a white space after the 3 digit number....//msg2
				$challange = base64_decode($challange);
				$ntlm_res = $ntlm_client->NTLMResponse(substr($challange,24,8),$password);
				$msg3 = $ntlm_client->TypeMsg3($ntlm_res,$username,$realm,$workstation);//msg3
				// Send encoded username
				$rply=$this->sends(base64_encode($msg3));
				$code = substr($rply,0,3);
				if($code != 235) {
					$this->error =
					array("error" => "Could not authenticate",
					      "smtp_code" => $code,
					      "smtp_msg" => substr($rply,4));
					return false;
				}
				break;
		}
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function From($from='')
	{
		if(!$this->connected()) return false;
		if(!$from) $from=$this->_data['cfg']['from'];		//preg_replace("/.*\<(.+?)\>.*/","\\1",$_from)
		$useVerp = ($this->do_verp ? " XVERP" : "");
		$rply=$this->sends("MAIL FROM:<" . $from . ">" . $useVerp);
		$code = substr($rply,0,3);
		if($code != 250) {
			$this->error =
				array("error" => "MAIL not accepted from server",
				"smtp_code" => $code,
				"smtp_msg" => substr($rply,4));
			return false;
		}
		return true;
	}
	
	public function Recipient($to,$bad=false)
	{
		$this->error = null; // so no confusion is caused
		if(!$this->connected()) {
			$this->error = array(
			"error" => "Called Recipient() without being connected");
			return false;
		}
		$rply=$this->sends("RCPT TO:<" . $to . ">");
		$code = substr($rply,0,3);
		if($code != 250 && $code != 251) {
			$this->_data['bad_rcpt'][]=$to;
			$this->error =array("error" => "RCPT not accepted from server",
						"smtp_code" => $code,
						"smtp_msg" => substr($rply,4));
			return false;
		}
		return true;
	}
	
	public function Data($msg_data)
	{
		$this->error = null; // so no confusion is caused
		
		if(!$this->connected()) {
			$this->error = array(
			"error" => "Called Data() without being connected");
			return false;
		}
		
		$rply=$this->sends("DATA");
		$code = substr($rply,0,3);
		if($code != 354) {
			$this->error =
			array("error" => "DATA command not accepted from server",
			"smtp_code" => $code,
			"smtp_msg" => substr($rply,4));
			return false;
		}
		
		$msg_data = str_replace("\r\n","\n",$msg_data);
		$msg_data = str_replace("\r","\n",$msg_data);
		$lines = explode("\n",$msg_data);
		
		$field = substr($lines[0],0,strpos($lines[0],":"));
		$in_headers = false;
		if(!empty($field) && !strstr($field," ")) {
			$in_headers = true;
		}
		
		$max_line_length = 998; // used below; set here for ease in change
		
		while(list(,$line) = @each($lines)) {
			$lines_out = null;
			if($line == "" && $in_headers) {
				$in_headers = false;
			}
			// ok we need to break this line up into several smaller lines
			while(strlen($line) > $max_line_length) {
				$pos = strrpos(substr($line,0,$max_line_length)," ");
				
				// Patch to fix DOS attack
				if(!$pos) {
					$pos = $max_line_length - 1;
					$lines_out[] = substr($line,0,$pos);
					$line = substr($line,$pos);
				} else {
					$lines_out[] = substr($line,0,$pos);
					$line = substr($line,$pos + 1);
				}
		
				if($in_headers) {
					$line = "\t" . $line;
				}
			}
			$lines_out[] = $line;
			
			// send the lines to the server
			while(list(,$line_out) = @each($lines_out)) {
				if(strlen($line_out) > 0){
					if(substr($line_out, 0, 1) == ".") {
						$line_out = "." . $line_out;
					}
				}
				$rply=$this->send($line_out);
			}
		}
		
		// message data has been sent
		$rply=$this->sends($this->CRLF);
		$code = substr($rply,0,3);
		if($code != 250) {
			$this->error =
			array("error" => "DATA not accepted from server",
			"smtp_code" => $code,
			"smtp_msg" => substr($rply,4));
			return false;
		}
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function Hello()
	{
		if(!$this->connected()) return false;
		if(!$this->sendHello("EHLO")) {
			if(!$this->sendHello("HELO")) {
				return false;
			}
		}
		return true;
	}
	public function sendHello($hello)
	{
		$rply=$this->sends($hello." ".$this->host);
		$code = substr($rply,0,3);
		if($code != 250) {
			$this->error =array(	"error" => $hello . " not accepted from server",
						"smtp_code" => $code,
						"smtp_msg" => substr($rply,4));
			return false;
		}
		$this->_data['helo_rply'] = $rply;
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function Reset()
	{
		$this->error = null; // so no confusion is caused
		if(!$this->connected()) {
			$this->error = array(
			"error" => "Called Reset() without being connected");
			return false;
		}
		$rply=$this->sends("RSET");
		$code = substr($rply,0,3);
		if($code != 250) {
			$this->error =
			array("error" => "RSET failed",
			"smtp_code" => $code,
			"smtp_msg" => substr($rply,4));
			return false;
		}
		return true;
	}
	
	public function Quit($close_on_error = true)
	{
		$this->error = null; // so there is no confusion
		if(!$this->connected()) {
			$this->error = array(
			"error" => "Called Quit() without being connected");
			return false;
		}
		
		$byemsg=$this->sends("QUIT");
		$code = substr($byemsg,0,3);
		$rval = true;
		$e = null;
		if($code != 221) {
			// use e as a tmp var cause Close will overwrite $this->error
			$e = array("error" => "SMTP server rejected quit command",
			 "smtp_code" => $code,
			 "smtp_rply" => substr($byemsg,4));
			$rval = false;
		}
		if(empty($e) || $close_on_error) {
			$this->Close();
		}
		return $rval;
	}
	
	public function Close()
	{
		$this->error = null; // so there is no confusion
		$this->helo_rply = null;
		if(!empty($this->conn)) {
			// close the connection and cleanup
			fclose($this->conn);
			$this->conn = 0;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function send($message)
	{
		fputs($this->conn, $message . $this->CRLF);
	}
	public function sends($message)
	{
		fputs($this->conn, $message . $this->CRLF);
		return getLines();
	}
	
	public function getLines()
	{
		$data = "";
		$endtime = 0;
		/* If for some reason the fp is bad, don't inf loop */
		if (!is_resource($this->conn)) {
			return $data;
		}
		stream_set_timeout($this->conn, $this->_data['timeout']);
		if ($this->_data['timelimit'] > 0) {
			$endtime = time() + $this->_data['timelimit'];
		}
		while(is_resource($this->conn) && !feof($this->conn)) {
			$str = @fgets($this->conn,515);
			$data .= $str;
			// if 4th character is a space, we are done reading, break the loop
			if(substr($str,3,1) == " ") { break; }
			// Timed-out? Log and break
			$info = stream_get_meta_data($this->conn);
			if ($info['timed_out']) {
				break;
			}
			// Now check if reads took too long
			if ($endtime) {
				if (time() > $endtime) {
					break;
				}
			}
		}
		return $data;
	}
	
}
