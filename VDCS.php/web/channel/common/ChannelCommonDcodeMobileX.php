<?
class ChannelCommonDcodeMobileX extends ChannelCommonBaseX
{
	
	public function parseApply()
	{
		$module=queryx('module');
		if(!$module){
			$this->setStatus('module');
			return;
		}
		$mobile=queryx('mobile');
		if(!utilCheck::isMobile($mobile)){
			$this->setStatus('mobile');
			return;
		}
		
		if(DcodeSMS::isValidSend($module,'',$mobile)>2){
			$this->setStatus('valid');
			return;
		}
		
		$code=utilCode::getRandNum(6);
		//debugx($code);
		//DcodeSMS::create($module,$code,$mobile);
		
		$message=SendSMS::getMessage($module,$code);
		//$params=array();
		//$params['debug']=true;
		$params=null;
		$issend=SendSMS::send($mobile,$message,$params,$rets);
		//$issend=true;
		if($issend){
			DcodeSMS::create($module,$code,$mobile);
			$this->setSucceed();
		}
		else{
			$this->setStatus('send','动态码发送失败');
		}
	}
	
}
?>