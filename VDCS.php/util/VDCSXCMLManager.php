<?
class VDCSXCMLManager extends VDCSXCML
{
	
	public $tableStruct;
	public $channel,$Key,$Name,$File,$Cache,$FileStruct,$FileSource,$PathSource,$PathSourceUp;
	public $treeConfig,$treeFileSource,$treeFileUpdate,$xcmlStruct,$treeStruct;
	public $isLoad;
	
	const FORM_ITEM_TYPE_BARS		= 'bar,multibar.bar,multibar.head,multibar.foot';
	
	public function __construct()
	{
		//parent::__construct();
		$this->tableStruct=newTable();
		$this->treeConfig=newTree();
		$this->treeFileSource=newTree();
		$this->treeFileUpdate=newTree();
		$this->xcmlStruct=newXCML();
		$this->treeStruct=newTree();
		$this->isLoad=false;
	}
	public function __destruct()
	{
		//parent::__destruct();
		
	}
	
	
	########################################
	########################################
	public function getChannel(){return $this->channel;}
	public function setChannel($strer){$this->channel=$strer;}
	
	public function getKey(){return $this->Key;}
	public function setKey($strer){$this->Key=$strer;}
	
	public function getName(){return $this->Name;}
	public function setName($strer){$this->Name=$strer;}
	
	public function getFile(){return $this->File;}
	public function setFile($strer){$this->File=$strer;}
	
	public function getCache(){return $this->Cache;}
	public function setCache($strer){$this->Cache=$strer;}
	
	
	########################################
	########################################
	public function setConfigTree($strTree){$this->treeConfig->setArray($strTree->getArray());}
	public function getConfigVar($strKey){return $this->treeConfig->getItem($strKey);}
	public function setConfigVar($strKey,$strValue){$this->treeConfig->addItem($strKey,$strValue);}
	
	
	########################################
	########################################
	public function getFileStruct(){return $this->FileStruct;}
	public function setFileStruct($strer){$this->FileStruct=$strer;}
	
	public function getFileStructTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->treeFileSource->getArray());
		return $reTree;
	}
	public function setFileStructTree($strTree){$this->treeFileSource->setArray($strTree->getArray());}
	
	public function getFileSource(){return $this->FileSource;}
	public function setFileSource($strer){$this->FileSource=$strer;}
	
	public function getFileSourceTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->treeFileSource->getArray());
		return $reTree;
	}
	public function isFileSourceItem($strKey){return $this->treeFileSource->isItem($strKey);}
	public function getFileSourceItem($strKey){return $this->treeFileSource->getItem($strKey);}
	
	public function getFileUpdateTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->treeFileUpdate->getArray());
		return $reTree;
	}
	public function setFileUpdateTree($strTree){$this->treeFileUpdate->setArray($strTree->getArray());}
	public function setFileUpdateItem($strKey,$strValue){$this->treeFileUpdate->setItem($strKey,$strValue);}
	
	public function getSourcePlace($key,$value)
	{
		return VDCSXCML::getTreePlace($this->treeFileSource,$key,$value);
	}
	
	public function isLoad(){return $this->isLoad;}
	
	
	########################################
	########################################
	public function doLoad()
	{
		if($this->channel){
			if($this->treeConfig->getCount()<1){
				//$this->treeConfig=getChannelTree($this->channel,'configure',2,False)
				$this->treeConfig=CommonChannelExtend::getTree($this->channel,'configure',false);
			}
			if(!$this->FileSource) $this->FileSource='common.channel/'.$this->channel.'/{$file}';
			if(!$this->FileStruct) $this->FileStruct='common.channel/'.$this->channel.'/config/struct.configure';
		}
		if($this->FileStruct){
			$this->xcmlStruct->loadXML(VDCSDTML::getConfigContent($this->FileStruct));
			$this->xcmlStruct->doParse();
			//debugx($this->xcmlStruct->getErrorType());
			if($this->xcmlStruct->isObj()){
				$this->tableStruct->setFields('key,name,file');
				$tmpTrue=false;
				$this->xcmlStruct->doParseItem();
				$this->xcmlStruct->doItemBegin();
				for($i=1;$i<=$this->xcmlStruct->getItemCount();$i++){
					$tmpKey=$this->xcmlStruct->getItem('__key');
					$tmpName=$this->xcmlStruct->getItem('__name');
					$tmpFile=$this->xcmlStruct->getItem('__file');
					$tmpCache=$this->xcmlStruct->getItem('__cache');
					$tmpTree=newTree();
					$tmpTree->addItem('key',$tmpKey);
					$tmpTree->addItem('name',$tmpName);
					$tmpTree->addItem('file',$tmpFile);
					$this->tableStruct->addItem($tmpTree);
					if(toLower($tmpKey)==toLower($this->Key)){
						$tmpTrue=true;
						$this->Name=$tmpName;
						$this->File=$tmpFile;
						$this->Cache=$tmpCache;
						$this->treeStruct=$this->xcmlStruct->getItemTree();
					}
					$this->xcmlStruct->doItemMove();
				}
				if(!$tmpTrue){
					$this->xcmlStruct->doItemBegin();
					//debugx($this->xcmlStruct->getItemNow());
					$this->Key=$this->xcmlStruct->getItem('__key');
					$this->Name=$this->xcmlStruct->getItem('__name');
					$this->File=$this->xcmlStruct->getItem('__file');
					$this->Cache=$this->xcmlStruct->getItem('__cache');
					$this->treeStruct=$this->xcmlStruct->getItemTree();
				}
				//debugTree($this->treeStruct);
				//debugx($this->Key.'-'.$this->Name.'-'.$this->File);
				if(len($this->Key)>0 && len($this->File)>0){
					if(!$this->FileSource){
						$this->FileSource=$this->File;
					}
					else{
						$this->FileSource=rd($this->FileSource,'file',$this->File);
					}
				}
			}
		}
		//debugx($this->FileSource&'-');
		if($this->FileSource){
			$this->PathSource=appFilePath($this->FileSource);
			$this->PathSourceUp=appFilePath($this->FileSource,false);
			//debugx($this->PathSource);
			//debugx($this->PathSourceUp);
			//$this->treeFileSource=getFile2Tree($this->PathSource);
			$this->treeFileSource=VDCSXCMLExtend::toTree(getFileContent($this->PathSource));
			//debugTree($this->treeFileSource);
			$this->treeFileUpdate->setArray($this->treeFileSource->getArray());
		}
		
		if($this->treeConfig->getCount()>0 && $this->xcmlStruct->isObj() && $this->treeStruct->getCount()>0 && $this->treeFileSource->getCount()>0){
			$this->isLoad=True;
		}
	}
	
	
	########################################
	########################################
	public function doFileUpdate()
	{
		//VDCSXCML::doXCMLUpdate($this->PathSourceUp,$this->treeFileUpdate);
		VDCSXCMLExtend::doFileUpdate($this->PathSourceUp,$this->treeFileUpdate);
		/*
		if(len($this->Cache)>0){
		   dim tmpAry,aa
		   tmpAry=split(toReplace($this->Cache,'{@channel}',ctl.subchannel),',')
		   for aa=0 to ubound(tmpAry)
			   if(len(tmpAry(aa))>0){ dcs.server.delCache(tmpAry(aa))
		   next
		}
		*/
	}
	
	
	########################################
	########################################
	public function getFormXCML($treeStruct=null,$treeSource=null,$isFrame=1)
	{
		$tmpTableForm=VDCSXCML::newFormTable();
		//##########
		if(!isTree($treeStruct)){
			$treeStruct=newTree();
			$treeStruct->setArray($this->treeStruct->getArray());
		}
		if(!isTree($treeSource)){
			$treeSource=newTree();
			$treeSource->setArray($this->treeFileSource->getArray());
		}
		//##########
		//debugTree($treeSource);
		$treeStruct->doBegin();
		for($i=1;$i<=$treeStruct->getLength();$i++){
			$tmpKey=$treeStruct->getItemKey();
			if(left($tmpKey,2)!='__'){
				$tmpInputName=$tmpKey;
				//debugx($tmpInputName);
				$tmpCationName=$treeStruct->getItemValue();
				$tmpCationName=r($tmpCationName,TABS,'');
				$tmpPropertys='';
				if(ins($tmpCationName,STRUCT_PREPERTY_SYMBOL)>0){
					$tmpPropertys=toSubstr($tmpCationName,ins($tmpCationName,STRUCT_PREPERTY_SYMBOL) + len(STRUCT_PREPERTY_SYMBOL));
					$tmpCationName=toSubstr($tmpCationName,1,ins($tmpCationName,STRUCT_PREPERTY_SYMBOL) - 1);
				}
				//debugx($tmpCationName.' = '.$tmpPropertys);
				$tmpTree=utilString::toTree($tmpPropertys,STRUCT_SEPARATOR_SYMBOL,STRUCT_EVALUATE_SYMBOL);
				//debugTree($tmpTree);
				$tmpValue='';$tmpProperty='';
				$tmpType=$tmpTree->getItem('type');
				if(len($tmpType)<1){
					$tmpType='input.'.$tmpInputName;
				}
				else{
					if(inp(self::FORM_ITEM_TYPE_BARS,$tmpType)<1) $tmpType.='.'.$tmpInputName;
				}
				//debugx($tmpInputName);
				$tmpValue=$treeSource->getItem($tmpInputName);
				$tmpValue=$this->toValueEncode($tmpValue);
				//$tmpValue=$this->toFlagEncode($tmpValue);
				$tmpTree->doBegin();
				for($x=1;$x<=$tmpTree->getLength();$x++){
					if(left($tmpTree->getItemKey(),9)=='property.'){
						$tmpProperty.=';'.toSubstr($tmpTree->getItemKey(),10).'='.$tmpTree->getItemValue();
					}
					$tmpTree->doMove();
				}
				if(ins(';'.$tmpProperty,';type=')<1) $tmpProperty.=';type=string';
				if($tmpProperty) $tmpProperty=toSubstr($tmpProperty,2);
				$tmpTreeForm=newTree();
				$tmpTreeForm->addItem('type',$tmpType);
				$tmpTreeForm->addItem('property',$tmpProperty);
				$tmpTreeForm->addItem('style',$tmpTree->getItem('style'));
				$tmpTreeForm->addItem('caption',$tmpCationName);
				$tmpTreeForm->addItem('att',$tmpTree->getItem('att'));
				$tmpTreeForm->addItem('value',$tmpValue);
				$tmpTreeForm->addItem('explain',$tmpTree->getItem('explain'));
				$tmpTableForm->addItem($tmpTreeForm);
			}
			$treeStruct->doMove();
		}
		//debugTable($tmpTableForm);
		return VDCSXCMLExtend::getTable2FormXCML($tmpTableForm,$isFrame);
	}
	
	
	public function toValueEncode($re)
	{
		$re=r($re,'<','&lt;');
		$re=r($re,'>','&gt;');
		$$re=r($re,'@','&#'.ord('@').';');
		$re=r($re,'$','&#'.ord('$').';');
		return $re;
	}
	
	
	/*
	toFlagEncode=dcs.coder.toFlagEncode(s)
	public function toFlagEncode($re)
	{
		$re=toFlagSwap($re,'{@([^{\@}]*)}','{#@'.PATTERN_SYMBOL_SWAP.'}',1)
		$re=toFlagSwap($re,'{\$([^{\$}]*)}','{#'.PATTERN_SYMBOL_SWAP.'}',1)
		$re=toFlagSwap($re,'{\$=([^{\$=}]*)\$}','{#='.PATTERN_SYMBOL_SWAP.'#}',1)
		$re=toFlagSwap($re,'<app:([^<>]*)>','{#app:'.PATTERN_SYMBOL_SWAP.'#}',1)
		return $re;
	}
	
	public function toFlagDecode($re)
	{
		$re=toFlagSwap($re,'{\#@([^{\#@}]*)}','{@'.PATTERN_SYMBOL_SWAP.'}',1)
		$re=toFlagSwap($re,'{\#([^{\#}]*)}','{$'.PATTERN_SYMBOL_SWAP.'}',1)
		$re=toFlagSwap($re,'{\#=([^{\#=}]*)\#}','{$='.PATTERN_SYMBOL_SWAP.'$}',1)
		$re=toFlagSwap($re,'{\#app:([^<>]*)\#}','<app:'.PATTERN_SYMBOL_SWAP.'>',1)
		return $re;
	}
	*/
}