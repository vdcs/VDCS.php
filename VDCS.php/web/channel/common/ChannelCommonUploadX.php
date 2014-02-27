<?
class ChannelCommonUploadX extends ChannelCommonUpload
{
	use WebServeRefXML;
	
	
	public function doLoad()
	{
		//debugx('uri = '.$_SERVER['REQUEST_URI']);
		//ResTest::logAction('uploadx');
		if(!$this->isLoaded()){
			$this->addVar('status',$this->up->getVar('status'));
			$this->addVar('message',$this->up->getVar('message'));
			return;
		}
		$this->addVar('mode',$this->up->mode);
		$this->addVar('format',$this->up->format);
		$this->addVar('channel',$this->up->channel);
		$this->addVar('sorts',$this->up->sorts);
		$this->addVar('types',$this->up->types);
		$this->addVar('uurc',$this->up->getVar('uurc'));
		$this->addVar('uuid',$this->up->getVarInt('uuid'));
		
		//ResTest::logAction('uploadx');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParsePos()
	{
		if(!$this->isChecked()){
			$this->addVar('status',$this->up->getVar('status'));
			$this->addVar('message',$this->up->getVar('message'));
			return;
		}
		//$this->addVar('allowext',$this->up->getVar('allowext'));
		$this->addVar('savedir',$this->up->getVar('savedir'));
		$this->addVar('filename',$this->up->getVar('filename'));
		$this->addVar('filetype',$this->up->getVar('filetype'));
		$this->addVar('maxsize',$this->up->getVar('maxsize'));
		$this->addVar('linkmode',$this->up->getVar('linkmode'));
		$this->addVar('formname',$this->up->getVar('formname'));
		$this->addVar('fileinput',$this->up->getVar('fileinput'));
		$this->addVar('thumbinput',$this->up->getVar('thumbinput'));
		$this->addVar('valuemode',$this->up->getVar('valuemode'));
		$this->addVar('inputtype',$this->up->getVar('inputtype'));
		$this->addVar('total.max',$this->up->getVar('total.max'));
		if($this->up->isSucceed()){
			$this->addVar('file.ext',$this->up->getVar('file.ext'));
			$this->addVar('file.size',$this->up->getVar('file.size'));
			$this->addVar('file.sizes',utilCode::toFilesize($this->up->getVar('file.size')));
			$this->addVar('file.urls',$this->up->getVar('file.urls'));
			$this->addVar('file.name',$this->up->getVar('file.name'));
			$this->addVar('file.names',$this->up->getVar('file.names'));
			
			$this->addVar('file.id',$this->up->getVar('file.id'));
			$this->addVar('thumb.id',$this->up->getVar('thumb.id'));
			$this->setStatus('succeed');
		}
		else{
			$this->setStatus($this->up->getVar('status'));
		}
		$this->addVar('message',$this->up->getVar('message'));
	}
	
}
?>