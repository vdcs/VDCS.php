<?
class ChannelCommonUpload extends ChannelCommonUploadBase
{
	
	protected function doChannelSets()
	{
		if(1==1){
			$this->up->setVar('channel.thumb.width',toi(appv('var.pic.width')));		//120
			$this->up->setVar('channel.thumb.height',toi(appv('var.pic.height')));		//90
		}
		
		$uuid=$this->up->getVarInt('uuid');
		
		//$this->_isChannelSet=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doParseMessageExtend($status,&$LinkMode)
	{
		if(len($status)<1) return;
		if($status=='succeed'){
			$FormMode='set';
			$FileValue=$this->up->getVar('file.url');
			//debugx($this->up->getVar('inputtype'));
			if(inp('img,pic,spic,pics,affix',$this->up->getVar('inputtype'))>0 || utilFile::isExtPic($this->up->getVar('inputtype'))){
				$LinkMode='no';
				$ThumbValue=$this->up->getVar('thumb.url');
			}
			else{
				$LinkMode='again';
				$FormMode='append';
				$FileExt=$this->up->getVar('file.ext');
				if(inp('html',$this->up->getVar('inputtype'))>0){
					if(utilFile::isExtPic($FileExt)){
						$FileValue='<img border="0" src="'.$this->up->getVar('file.urls').'" res="upload" />';
					}
					else if(inp('swf,flv',$this->up->getVar('inputtype'))>0){
						//$FileValue='[flash=350,250]upload/'.$this->up->getVar('file.url').'[/flash]';
						$resURL=$this->up->getVar('file.urls');
						$resWeight=350;$resHeight=250;
						$FileValue='<object width="'.$resWeight.'" height="'.$resHeight.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">';
						$FileValue.='<param name="movie" value="'.$resURL.'">';
						$FileValue.='<param name="bgcolor" value="#FFFFFF">';
						$FileValue.='<param name="quality" value="high">';
						$FileValue.='<param name="wmode" value="opaque">';
						$FileValue.='<param name="menu" value="false">';
						$FileValue.='<param name="allowscriptAccess" value="sameDomain">';
						$FileValue.='<embed src="'.$resURL.'" width="'.$resWeight.'" height="'.$resHeight.'" bgcolor="#FFFFFF" quality="high" wmode="opaque" menu="false" allowscriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
						$FileValue.='</object>';
					}
					else{
						//$FileValue='<a href="'.urlLink(CommonUpload::getURLBase('download'),'id='.$this->up->getVar('file.id').'').'" _res="upload" _ext="'.$FileExt.'" target="_blank" />附件 '.$this->up->getVar('file.names').'</a>';
						$FileValue='[download='.$FileExt.',id='.$this->up->getVar('file.id').']'.$this->up->getVar('file.names').'[/download]';
					}
				}
				else{
					if(utilFile::isExtPic($FileExt)){
						$FileValue='[img]'.CommonUpload::getURLBase().$this->up->getVar('file.url').'[/img]';
					}
					else if(inp('swf,flv',$this->up->getVar('inputtype'))>0){
						$FileValue='[flash=350,250]'.CommonUpload::getURLBase().$this->up->getVar('file.url').'[/flash]';
					}
					else{
						$FileValue='[download='.$FileExt.',id='.$this->up->getVar('file.id').']'.$this->up->getVar('file.names').'[/download]';
					}
				}
			}
			$this->up->setVar('form.mode',$FormMode);
			$this->up->setVar('form.filevalue',$FileValue);
			$this->up->setVar('form.thumbvalue',$ThumbValue);
		}
	}
	
}
?>