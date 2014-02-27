<?
/*
$mailcfg['mode']=	2;				//0=UNIX sendmail localhost,1=server,2＝SMTP
$mailcfg['server']=	'mail.bodmail.cn';		//SMTP 服务器/host address
$mailcfg['port']=	25;				//SMTP 端口,默认不修改/port,leave default for most occations
$mailcfg['auth']=	1;				//是否需要 AUTH LOGIN 验证,1=是,0=否/require authentification? 1=yes,0=no
$mailcfg['from']= 	'master@domain.com';		//发信人地址 (如果需要验证,必须为本服务器地址)/mail from (if authentification required,do use local email address of ESMTP server)
$mailcfg['user']=	'user@domain.com';		//验证用户名/user for authentification
$mailcfg['password']=	'password';			//验证密码/password for authentification
*/
class utilMail
{
	public $CRLF = "\r\n";
	protected $_data=array();
	protected $ContentType			= 'text/plain';
	protected $Sendmail			= '/usr/sbin/sendmail';
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unset($this->_data);
	}
	
	
	public function getErrorType(){return $this->_data['error_type'];}
	public function getErrorDescription(){return $this->_data['error_description'];}
	
	public function getReturnData(){return $this->_data['return_data'];}
	public function getStatus()
	{
		$re='unknown';
		switch($this->_data['error_type']){
			case 0:		$re='succeed';break;
			case 2:		$re='content';break;
			case 1:		$re='server';break;
			case 5:		$re='connect';break;
			case -1:	$re='init';break;
		}
		return $re;
	}
	
	public function IsHTML($ishtml = true)
	{
		if ($ishtml) {
			$this->ContentType = 'text/html';
		} else {
			$this->ContentType = 'text/plain';
		}
	}
	public function IsMail() {
		$this->_data['mailer'] = 'mail';
	}
	public function IsSendmail()
	{
		if (!stristr(ini_get('sendmail_path'), 'sendmail')) {
		$this->Sendmail = '/var/qmail/bin/sendmail';
		}
		$this->_data['mailer'] = 'sendmail';
	}
	public function IsQmail() {
		if (stristr(ini_get('sendmail_path'), 'qmail')) {
			$this->Sendmail = '/var/qmail/bin/sendmail';
		}
		$this->_data['mailer'] = 'sendmail';
	}

	
	/*
	##############################
	##############################
	*/
	public function init($cfg=null)
	{
		$this->_data['HELO']='VDCS Sendmail';
		
		$this->_data['timeout']=10;
		$this->_data['timelimit']=30;
		
		$this->_data['mailer']='mail';
		
		$this->_data['mode']=-1;
		$this->_data['to.total']=0;
		
		$this->_data['error_type']=-1;
		$this->_data['error_description']='';
		
		$this->_data['return_num']=-1;
		$this->_data['return_data']=array();
		
		$this->setConfig($cfg);
	}
	
	public function setConfig($cfg=null)
	{
		if(!is_array($cfg)) return;
		if(!isset($cfg['user'])) $cfg['user']=$cfg['username'];
		if(!isset($cfg['pass'])) $cfg['pass']=$cfg['password'];
		if(!$cfg['server'] || !$cfg['user'] || !$cfg['pass']) return;	// || !$cfg['from']
		if(isset($cfg['mode'])) $this->setMode($cfg['mode']);
		$this->_data['cfg']=$cfg;
		if($this->_data['cfg']['ip']) $this->_data['cfg']['server']=$this->_data['cfg']['ip'];
		$this->_data['cfg']['port']=intval($this->_data['cfg']['port']);
		if(!$this->_data['cfg']['port']) $this->_data['cfg']['port']=25;
		$this->_data['cfg']['auth']=intval($this->_data['cfg']['auth']);
		if($this->_data['cfg']['auth']!=0 && $this->_data['cfg']['auth']!=1) $this->_data['cfg']['auth']=0;
		if($this->_data['cfg']['from']) $this->_data['from']=$this->_data['cfg']['from'];
		//debuga($cfg);
	}
	
	public function setMode($v)
	{
		$re=-1;
		switch($v){
			case '0':
			case 'direct':		$re=0;break;
			case '1':
			case 'indirect':	$re=1;break;
			case '2':
			case 'smtp':		$re=2;break;
		}
		//debugx('mode: '.$v.' = '.$re);
		$this->_data['mode']=$re;
	}
	public function getMode(){return $this->_data['mode'];}
	
	
	/*
	##############################
	##############################
	*/
	public function setSubject($s){if($s){$this->_data['subject']=$s;}}
	public function getSubject(){return $this->_data['subject'];}
	
	public function setMessage($s){if($s){$this->_data['message']=$s;}}
	public function getMessage(){return $this->_data['message'];}
	
	public function addTo($email,$name='')
	{
		if($email){
			$this->_data['to.total']++;
			$this->_data['to'][$this->_data['to.total']-1]['email']=$email;
			$this->_data['to'][$this->_data['to.total']-1]['name']=$name;
		}
	}
	public function getTo()
	{
		for($i=0;$i<$this->_data['to.total'];$i++){
			$tmp=$this->_data['to'][$i]['name'] ? $this->_data['to'][$i]['name'].' <'.$this->_data['to'][$i]['email'].'>' : $this->_data['to'][$i]['email'];
			$reto=$reto ? $reto.=','.$tmp : $tmp;
		}
		return $reto;
	}
	
	public function setFrom($email,$name='')
	{
		$this->_data['from_email']=$email;
		$this->_data['from_name']=$name;
		$this->_data['cfg']['from']=$email;
		$this->_data['sender']=$email;
	}
	public function getFrom()
	{
		if($this->_data['from_email']){
			$re=$this->_data['from_name'] ? $this->_data['from_name'].' <'.$this->_data['from_email'].'>' : $this->_data['from_email'];
		}
		else{
			$re=$this->_data['from'];
		}
		return $re;
	}
	
	/*
	##############################
	##############################
	*/
	public function doSend()
	{
		$re=false;
		//debuga($this->_data);
		switch($this->_data['mode']){
			case 2:
				$this->_data['mailer']='smtp';
				$re=$this->sendSMTP();
				break;
			case 1:
				$this->_data['mailer']='sendmail';
				$re=$this->sendIndirect();
				break;
			case 0:
				$this->_data['mailer']='sendmail';
				$this->_data['mailer']='smtp';
				$re=$this->sendDirect();
				break;
		}
		return $re;
	}
	
	/*
	##############################
	*/
	public function sendIndirect()	//UNIX sendmail server
	{
		if(!$this->_data['cfg']['server'] || !$this->_data['cfg']['port']){
			$this->_data['error_type']=1;
			$this->_data['error_description']="need SMTP(Server or Port).";
			return false;
		}
		$_from=$this->getFrom();
		$_to=$this->getTo();
		$_subject=$this->getSubject();
		$_message=$this->getMessage();
		if(!$_from || !$_to || !$_subject || !$_message){
			$this->_data['error_type']=2;
			$this->_data['error_description']="need E-Mail Info(From,To,Subject or Message).";
			return false;
		}
		$_headers="From: ".$this->getFrom()."\r\n";
		
		ini_set('SMTP',$this->_data['cfg']['server']);
		ini_set('smtp_port',$this->_data['cfg']['port']);
		ini_set('sendmail_from',$_from);
		
		foreach(explode(',',$_to) as $touser){
			$touser=trim($touser);
			if($touser){
				@mail($touser,$_subject,$_message,$_headers);
			}
		}
		$this->_data['error_type']=0;
		$this->_data['error_description']="UNIX SendMail in Server is succeed!";
		return true;
	}
	
	/*
	##############################
	*/
	public function sendDirect()	//UNIX sendmail localhost
	{
		$_from=$this->getFrom();
		$_to=$this->getTo();
		$_subject=$this->getSubject();
		$_message=$this->getMessage();
		if(!$_from || !$_to || !$_subject || !$_message){
			$this->_data['error_type']=2;
			$this->_data['error_description']="need E-Mail Info(From,To,Subject or Message).";
			return false;
		}
		$_headers="From: ".$this->getFrom()."\r\n";
		
		if(strpos($_to,',')){
			$_headers.="Bcc: $_to\r\n";
			@mail('All User <master@localhost>',$_subject,$_message,$_headers);
		}
		else{
			@mail($_to,$_subject,$_message,$_headers);
		}
		$this->_data['error_type']=0;
		$this->_data['error_description']="UNIX SendMail in LocalHost is succeed!";
		return true;
	}
	
	/*
	##############################
	*/
	public function sendSMTP()
	{
		$this->smtp=new utilMailSMTP();
		$this->smtp->CRLF=$this->CRLF;
		$this->smtp->setData($this->_data);
		
		if(!$this->_data['cfg']['server'] || !$this->_data['cfg']['user'] || !$this->_data['cfg']['pass']){	// || !$this->_data['cfg']['from']
			$this->_data['error_type']=1;
			$this->_data['error_description']='need SMTP(Server,User,Pass or From).';
			return false;
		}
		$_from=$this->getFrom();
		$_to=$this->getTo();
		$_subject=$this->getSubject();
		$_message=$this->getMessage();
		if(!$_from || !$_to || !$_subject || !$_message){
			$this->_data['error_type']=2;
			$this->_data['error_description']='need E-Mail Info(From,To,Subject or Message).';
			return false;
		}
		
		//dcsLog('sendSMTP fsockopen');
		if(!$this->smtp->connect()){
			$this->_data['error_type']=5;
			$this->_data['error_description']='Unable to connect to the SMTP server.';
			return false;
		}
		
		if(!$this->smtp->Auth()){
			$this->_data['error_type']=5;
			$this->_data['error_description']="AUTH not pass - ";
			return false;
		}
		if(!$this->smtp->From()){
			$this->_data['error_type']=5;
			$this->_data['error_description']="MAIL FROM - ";
			return false;
		}
		
		for($i=0;$i<$this->_data['to.total'];$i++){
			$this->smtp->Recipient($this->_data['to'][$i]['email']);
		}
		/*
		if (count($bad_rcpt) > 0 ) { //Create error message for any bad addresses
			$badaddresses = implode(', ', $bad_rcpt);
			throw new phpmailerException($this->Lang('recipients_failed') . $badaddresses);
		}
		*/
		
		$msg_data=$this->getHeader();
		if(!$this->smtp->Data()){
			$this->_data['error_type']=5;
			$this->_data['error_description']="DATA - ";
			return false;
		}
		
		if($this->SMTPKeepAlive == true){
			$this->smtp->Reset();
		}
		else{
			$this->smtp->Quit();
			$this->smtp->Close();
		}
		
		$this->_data['error_type']=0;
		$this->_data['error_description']="SMTP interface SendMail is succeed!";
		return true;
	}
	
	
	
	public static function getHeader()
	{
		$result = '';
		
		// Set the boundaries
		$uniq_id = md5(uniqid(time()));
		$this->boundary[1] = 'b1_' . $uniq_id;
		$this->boundary[2] = 'b2_' . $uniq_id;
		$this->boundary[3] = 'b3_' . $uniq_id;
		
		if ($this->_data['MessageDate'] == '') {
			$result .= self::HeaderLine('Date', utilMailBody::RFCDate());
		} else {
			$result .= self::HeaderLine('Date', $this->_data['MessageDate']);
		}
		
		if ($this->_data['ReturnPath']) {
			$result .= self::HeaderLine('Return-Path', trim($this->_data['ReturnPath']));
		} elseif (!$this->_data['sender']) {
			$result .= self::HeaderLine('Return-Path', trim($this->_data['from_email']));
		} else {
			$result .= self::HeaderLine('Return-Path', trim($this->_data['sender']));
		}
		
		// To be created automatically by mail()
		if($this->_data['mailer'] != 'mail') {
			if ($this->SingleTo === true) {
				foreach($this->to as $t) {
					$this->SingleToArray[] = $this->AddrFormat($t);
				}
			} else {
				if(count($this->to) > 0) {
					$result .= $this->AddrAppend('To', $this->to);
				} elseif (count($this->cc) == 0) {
					$result .= self::HeaderLine('To', 'undisclosed-recipients:;');
				}
			}
		}
		
		$from = array();
		$from[0][0] = trim($this->From);
		$from[0][1] = $this->FromName;
		$result .= $this->AddrAppend('From', $from);
		
		// sendmail and mail() extract Cc from the header before sending
		if(count($this->cc) > 0) {
			$result .= $this->AddrAppend('Cc', $this->cc);
		}
		
		// sendmail and mail() extract Bcc from the header before sending
		if((($this->_data['mailer'] == 'sendmail') || ($this->_data['mailer'] == 'mail')) && (count($this->bcc) > 0)) {
			$result .= $this->AddrAppend('Bcc', $this->bcc);
		}
		
		if(count($this->ReplyTo) > 0) {
			$result .= $this->AddrAppend('Reply-To', $this->ReplyTo);
		}
		
		// mail() sets the subject itself
		if($this->_data['mailer'] != 'mail') {
			$result .= self::HeaderLine('Subject', $this->EncodeHeader($this->SecureHeader($this->Subject)));
		}
		
		if($this->MessageID != '') {
			$result .= self::HeaderLine('Message-ID', $this->MessageID);
		} else {
			$result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
		}
		$result .= self::HeaderLine('X-Priority', $this->Priority);
		if ($this->XMailer == '') {
			$result .= self::HeaderLine('X-Mailer', 'PHPMailer '.$this->Version.' (http://code.google.com/a/apache-extras.org/p/phpmailer/)');
		} else {
			$myXmailer = trim($this->XMailer);
			if ($myXmailer) {
				$result .= self::HeaderLine('X-Mailer', $myXmailer);
			}
		}
		
		if($this->ConfirmReadingTo != '') {
			$result .= self::HeaderLine('Disposition-Notification-To', '<' . trim($this->ConfirmReadingTo) . '>');
		}
		
		// Add custom headers
		for($index = 0; $index < count($this->CustomHeader); $index++) {
			$result .= self::HeaderLine(trim($this->CustomHeader[$index][0]), $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
		}
		if (!$this->sign_key_file) {
			$result .= self::HeaderLine('MIME-Version', '1.0');
			$result .= $this->GetMailMIME();
		}
		
		return $result;
	}
	
	public static function HeaderLine($name, $value)
	{
		return $name . ': ' . $value . self::$LE;
	}
	
}
/*
// To send HTML mail,the Content-type header must be set
$headers ='MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'To: Mary <mary@example.com>,Kelly <kelly@example.com>' . "\r\n";
$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
*/
