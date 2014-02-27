<?
class CommonPaSend extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$issend=SendSMS::send('18101673052','测试信息来了 '.DCS::now(),null,$rets);
		if($issend){
			debugx('SMS send: succeed!');
		}
		else{
			debugx('SMS send: fail('.$rets['status'].','.$rets['message'].')');
		}
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
?>