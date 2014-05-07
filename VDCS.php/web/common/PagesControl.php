<?
class PagesControl
{
	public $channel='',$module='',$subchannel='',$action='',$sort='',$type='',$mode='',$taxis='';
	public $id=0,$dataid=0;
	public $treeVar=null,$treeDat=null,$treeData=null,$treeDTML=null;
	
	public $e,$p=null,$pages=null,$ui=null;
	
	
	public function __construct()
	{
		$this->action=query('action');
		
		$this->treeVar=new utilTree();
		$this->treeDat=new utilTree();
		$this->treeData=new utilTree();
		$this->treeDTML=new utilTree();
		
		$this->e=new utilError();
	}
	
	public function __destruct()
	{
		unset($this->treeVar,$this->treeDat,$this->treeData,$this->treeDTML);
		unset($this->e,$this->p,$this->pages,$this->ui);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function addVar($k,$v){$this->treeVar->addItem($k,$v);}
	public function addDat($k,$v){$this->treeDat->addItem($k,$v);}
	public function addData($k,$v){$this->treeData->addItem($k,$v);}
	public function addDTML($k,$v){$this->treeDTML->addItem($k,$v);}
	
	public function getVar($k){return $this->treeVar->getItem($k);}
	public function getDat($k){return $this->treeDat->getItem($k);}
	public function getData($k){return $this->treeData->getItem($k);}
	public function getDTML($k){return $this->treeDTML->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function loadParam()
	{
		$this->channel=query('channel');
		$this->module=query('module');
		$this->sort=query('sort');
		$this->type=query('type');
		$this->mode=query('mode');
		$this->taxis=query('taxis');
		$this->id=queryi('id');
		/*
		$this->subchannel=query('subchannel');
		$this->dataid=queryi('dataid');
		$this->classid=queryi('classid');
		*/
	}
	
	public function setAction($s){$this->action=$s;}
	public function setMode($s){$this->mode=$s;}
	public function setTaxis($s){$this->taxis=$s;}
	
	
	/*
	########################################
	########################################
	*/
	public function initPages()
	{
		if(!$this->pages) $this->pages=new PagesForm();
	}
	public function loadPages()
	{
		$this->initPages();
		$this->pages->setPathTemplate(appDirPath('common.config/control/'));
		//$this->pages->setPathForm(server.mappath(DIR_ROOT&DIR_ACCOUNT_COMMON_FORM));
		//$this->pages->setFileForm(server.mappath(DIR_ROOT&FILE_ACCOUNT_CHANNEL_FORM));
		//$this->pages->setFileForms(server.mappath(DIR_ROOT&FILE_ACCOUNT_CHANNEL_FORMS));
		$this->pages->doFormInit();
		//$this->pages->setFormAction(action);
		//if(isChannel()){
		//	$this->pages->setFormChannel(getChannel());
		//	$this->pages->setFormTitle(getChannelTitle(module));
		//}
	}
	
	public function doPagesParse($o)
	{
		if($o){
			if(!isTree($o)) $o=new utilTree();
			$o->doAppendTree($this->pages->getFormDatasTree(),"");
		}
		else{
			$this->treeData->doAppendTree($this->pages->getFormDatasTree(), "");
		}
		$this->e=$this->pages->getFormError();
	}
	
	public function doPagesFormParse()
	{
		$this->treeDTML->addItem('_pages.debug',$this->pages->getDebug());
		$this->treeDTML->addItem('_pages.form',$this->pages->getFormParse());
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initUI(){if(!$this->ui) $this->ui=new PageUI();}
	public function loadUI($t=1)
	{
		$this->initUI();
		if($t==1){
			$this->ui->setPath(appDirPath('common.config/control/'));
			$this->ui->setFileInterface(appFilePath('vdcs/config/control/interface'),appFilePath('common.config/control/interface'));
			$this->ui->setFileLangs(appFilePath('vdcs/control/language'),appFilePath('common.config/control/language'));
		}
		$this->ui->doInit();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initPaging(){if($this->p==null) $this->p=new libPaging();}
	public function loadPaging()
	{
		$this->initPaging();
		//$this->p->setTemplatePath('common.config/control/paging/');
		$this->p->setPageNum(5);
		$this->p->setListNum(10);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doRaiseError($s='',$url=''){$this->doRaiseMessage($s);}
	public function doRaiseMessage($s='',$url='')
	{
		$e=new utilError();
		if(is_object($s)) $e=$s;
		else if(!$s) $e=$this->e;
		else $e->addItems($s);
		//$this->treeDTML->addItem('_message',PageScript::JSMake($e->toJS(),$url));
		$this->treeDTML->addItem('_message.string',$e->toJSString());
		$this->treeDTML->addItem('_message.url',$url);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTML($re)
	{
		$re=$this->toDTMLDatas($re);
		//$re=$this->toDTMLTables($re);
		$re=VDCSDTML::toParseDCS($re);
		$re=$this->toDTMLPages($re);
		$re=$this->toDTMLUI($re);
		$re=$this->toDTMLPaging($re);
		return $re;
	}
	
	public function toDTMLDatas($re)
	{
		$re=utilRegex::toReplaceRegex($re,$this->treeVar,VDCSDTML::PATTERN_DTML_VAR);
		$re=utilRegex::toReplaceRegex($re,$this->treeDat,VDCSDTML::PATTERN_DTML_DAT);
		$re=utilRegex::toReplaceRegex($re,$this->treeData,VDCSDTML::PATTERN_DTML_DATA);
		$re=utilRegex::toReplaceRegex($re,$this->treeDTML,VDCSDTML::PATTERN_DTML_DTML);
		return $re;
	}
	
	public function toDTMLPages($re)
	{
		$tmpPattern="/<control:form\.element\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>/ies";
		$n=preg_match_all($tmpPattern,$strer,$__matches);
		for($i=0;$i<$n;$i++){
			$tmpParam[0]=$__matches[0][$i];
			$tmpParam[1]='form.element';
			$tmpParam[2]=$__matches[1][$i];
			$tmpParam[3]=$__matches[3][$i];
			$tmpParam[4]=$__matches[5][$i];
			$tmpParam[5]=$__matches[7][$i];
			if($tmpParam[3]=='__value'){
				$tmpParam[3]='';
				$_n=preg_match_all("/(.[^\.\s]*)\.(.[^\$\s]*)(\$\$\$(.[^\$\s]*))?/ies",$tmpParam[2],$___matches);
				if($_n && strlen($___matches[2][0])>0) $tmpParam[3]=$this->treeData->getItem($___matches[2][0]);
			}
			$re=r($re,$tmpParam[0],VDCSDTML::getFuncValue($tmpParam));
		}
		return $re;
	}
	
	public function toDTMLUI($re,$keys='')
	{
		if($this->ui!=null){
			if(len($keys)<1) $keys='[\w-\.][^\"]*';
			else $keys=r($keys,',','|');
			$_pattern='<control\:ui\.('.$keys.')\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>';
			$_matches=utilRegex::toMatches($re,$_pattern);
			for($m=0;$m<count($_matches[0]);$m++){
				$tmpParam[0]=$_matches[0][$m];
				$tmpParam[1]=$_matches[1][$m];
				$tmpParam[2]=$_matches[2][$m];
				$tmpParam[3]=$_matches[4][$m];
				$tmpParam[4]=$_matches[6][$m];
				$tmpValue='';
				//debugArray($tmpParam);
				switch($tmpParam[1]){
					case 'frame':	$tmpValue=$this->ui->getFrame($tmpParam[2]); break;
					case 'table':	$tmpValue=$this->ui->getTable($tmpParam[2],$tmpParam[3],$tmpParam[4]); break;
					case 'form':	$tmpValue=$this->ui->getForm($tmpParam[2],$tmpParam[3]); break;
					case 'space':	$tmpValue=$this->ui->getSpace($tmpParam[2]); break;
					case 'value':	$tmpValue=$this->ui->getValue($tmpParam[2]); break;
					case 'lang':
					case 'langs':	$tmpValue=$this->ui->getLangs($tmpParam[2]); break;
					case 'error':	$tmpValue=$this->ui->getError($tmpParam[2],$tmpParam[3]); break;
					case 'message':	$tmpValue=$this->ui->getMessage($tmpParam[2],$tmpParam[3],$tmpParam[4]); break;
				}
				$re=r($re,$tmpParam[0],$tmpValue);
			}
			//####################
			unsetr($_matches);
			//####################
		}
		return $re;
	}
	
	public function toDTMLPaging($re){if($this->p!=null) $re=$this->p->toDTML($re); return $re;}
	
}
?>