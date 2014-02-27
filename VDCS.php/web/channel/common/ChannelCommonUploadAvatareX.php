<?
class ChannelCommonUploadAvatareX extends ChannelCommonUpload
{
	use WebServeRefX;
	
	protected function doChannelSet()
	{
		$uuid=$this->up->getVarInt('uuid');
		
		//$this->_isChannelSet=true;
	}
	
	public function doLoad()
	{
		if(!$this->isLoaded()){
			$this->addVar('status',$this->up->getVar('status'));
			$this->addVar('message',$this->up->getVar('message'));
			return;
		}
		//dcsLog('jsonEavatar.uri',$_SERVER['REQUEST_URI']);
		if(!isBool($this->cfgChannel('eavatar'))) return;
		
		$this->up->setResource('input');
		$this->up->setFormat('');
		$file_name=$this->up->getVar('filename');
		$real_name=$file_name;
		$real_ext='jpg';
		$save_quality=100;
		$phototype=query('phototype');
		switch($phototype){
			case 'big':
			case 'small':
				$this->up->setTypes($phototype);
				$file_name.='_'.$phototype;
				$real_name.='_'.$phototype;
				$save_quality=100;
				break;
			case 'camera':
			default:
				//$file_name.='_'.$phototype;
				//$real_name.='_'.$phototype;
				$save_quality=100;
				break;
		}
		$this->up->setVar('filename',$file_name);
		$this->up->setVar('real.names',$real_name.'.'.$real_ext);
		$this->up->setVar('save.quality',$save_quality);
		
		$this->addVar('mode',$this->up->mode);
		$this->addVar('resource',$this->up->resource);
		$this->addVar('format',$this->up->format);
		$this->addVar('channel',$this->up->channel);
		$this->addVar('sorts',$this->up->sorts);
		$this->addVar('types',$this->up->types);
		$this->addVar('uurc',$this->up->getVar('uurc'));
		$this->addVar('uuid',$this->up->getVarInt('uuid'));
		
		//$this->_isLoaded=true;
		
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
	}
	
	
	protected function doOutput()
	{
		//debuga($this->up->_var);
		$_status=0;
		$_message='ready';
		if(!$this->_isLoaded){
			$_message='unload';
		}
		if($this->up->isSucceed()){
			$_status=1;
			$_message='上传成功!';
		}
		$oput=array();
		$oput['photoid']=$this->up->getVar('filename');
		$oput['data']=['urls'=>[$this->up->getVar('file.urls')]];
		$oput['status']=$_status;
		$oput['message']=$_message;
		
		//dcsLog('jsonEavatar.json',json_encode($oput));
		
		$this->serve->putJSON($oput);
	}
	
}
?>