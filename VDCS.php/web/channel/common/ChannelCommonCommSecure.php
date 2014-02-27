<?
class ChannelCommonCommSecure extends ChannelCommonBase
{
	
	public function doLoad()
	{
		$page=query('page');
		if(!$page) $page='comm';
		$this->theme->setChannelDir('_page');
		$this->theme->setPage('blank');
		$this->theme->setModule('');
	}
	
	public function parseSecret()
	{
		/*
		/comm/secure?action=secret&hashcode=4621d373cade4e83
		*/
		
		debugx('timer:'.DCS::timer());

		debugx($this->action.'!');
		debugx('');

		$amount=10;
		debugx('build rand:'.$amount);
		debug('<pre>');
		//debug('<!--');
		for($i=1;$i<=$amount;$i++){
			$randcode=utilCode::getRand(16,3);
			$hashcode=utilCoder::toMD5($randcode);
			debug(str_pad($i,3,'0',STR_PAD_LEFT).'		'.$hashcode.'		'.utilCoder::toMD5Secret($hashcode).NEWLINE);
		}
		debug('</pre>');
		//debug('-->');
		debugx('');
		debugx('');

		$hashcode='';
		$code=query('code');
		if($code){
			debugx('code='.$code);
			$hashcode=utilCoder::toMD5($code);
		}
		if(!$hashcode) $hashcode=queryx('hashcode');
		if($hashcode){
			debugx('hashcode='.$hashcode);
			$secretcode=utilCoder::toMD5Secret($hashcode);
			debugx('secretcode='.$secretcode);
		}

		debugx('');
		debugx('');
	}
	
}
?>