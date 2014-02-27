<?
class ChannelCommonParserX extends ChannelCommonBaseX
{
	
	public function doParse()
	{
		switch($this->action){
			case 'video':
				$this->doParseVideo();
				break;
		}
	}
	
	public function doParseVideo()
	{
		$url=query('url');
		if($url) $info=VideoURLParser::parser($url);
		if(!$url || !$info){
			$this->setStatus('error');
			return;
		}
		//debuga($info);
		
		$this->addVar('url',$url);
		foreach($info as $key=>$value){
			$this->addVar('info.'.$key,$value);
		}
		
		$this->setStatus('succeed');
	}
	
}
?>