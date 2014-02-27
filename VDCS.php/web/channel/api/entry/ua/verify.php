<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parsePassword();
	}

	public function parsePassword()
	{
		$uid=queryi('uid');
		$_password=query('password');
		$this->addVar('uid',$uid);
		$this->addVar('password',$_password);
		if($uid<1 || !$_password){
			$this->setStatus('data');
			return;
		}

		$this->ua->setID($uid);
		//$this->ua->setData('_password',utilCoder::toMD5i($_password));
		$islogin=UuPivotal::isLogin($this->ua,$_password);
		if(!$islogin){
			$this->setStatus('failed');
			return;
		}
		
		$this->setSucceed();
	}
	
}
