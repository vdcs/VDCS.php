<?
trait OauthRefAgent
{
	protected $_KEY,$_SECRET;
	protected $_CALLBACK			= 'http://yourdomain/oauth/agent.php';
	protected $_format='jason';
	
	
	function getSession($key){return DCS::sessionGet('oauth_'.self::AUTHRC.'_'.$key);}
	function setSession($key,$value){return DCS::sessionSet('oauth_'.self::AUTHRC.'_'.$key,$value);}
	
	
	public function setApp($id,$key){$this->_KEY=$id;$this->_SECRET=$key;}
	public function setID($s){$this->_SECRET=$s;}
	public function getID(){return $this->_KEY;}
	public function setKey($s){$this->_SECRET=$s;}
	public function getKey(){return $this->_SECRET;}
	
	public function setCallback($s){$this->_CALLBACK=$s;}
	public function getCallback(){return $this->_CALLBACK;}
	
	public function setFormat($s){$this->_format=$s;}
	public function getFormat(){return $this->_format;}
	
	
}
