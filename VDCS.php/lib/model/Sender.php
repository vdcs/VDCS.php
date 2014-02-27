<?
class Sender
{
	
	public static function sendMail($email,$subject,$message,&$opt=null)
	{
		if($opt){
			if(!$opt['vars']) $opt['vars']=array();
			if(!$opt['vars']['time.now']) $opt['vars']['time.now']=DCS::now();
			if(!$opt['vars']['time.today']) $opt['vars']['time.today']=DCS::today();
			$treeTpl=VDCSDTML::getConfigTree('vdcs.config/data/sendmail.tpl');
			$treeTpl->doAppendTree(VDCSDTML::getConfigTree('common.config/data/sendmail.tpl'));
			if(!isset($opt['format'])) $opt['format']=$treeTpl->getItem($opt['tpl'].'.format');
			if($subject=='tpl'){
				//debugx($opt['tpl'].'.subject');
				$subject=$treeTpl->getItem($opt['tpl'].'.subject');
				$subject=utilRegex::toReplaceVar($subject,utilArray::toTree($opt['vars']));
			}
			if($message=='tpl'){
				$message=$treeTpl->getItem($opt['tpl'].'.content');
				$message=utilRegex::toReplaceVar($message,utilArray::toTree($opt['vars']));
			}
		}
		//debugx($subject);
		//debugx($message);
		//$message='';
		return SendMail::post($email,$subject,$message,$opt);
	}
	
}
