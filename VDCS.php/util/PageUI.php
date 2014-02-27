<?
class PageUI
{
	protected $_path=array(),$_FileInterface='',$_FileLangs='';
	protected $_treeInterface=null,$_treeLangs=null;
	protected $_formname='frm_post';
	
	public function __construct()
	{
		$this->_path['base']='';
		$this->_path['interface']['base']='';
		$this->_path['langs']['base']='';
	}
	public function __destruct() { }
	
	
	public function setFormName($s) { $this->_formname=$s; }
	public function setActionURL($s) { $this->_actionurl=$s; }
	
	
	/*
	########################################
	########################################
	*/
	public function setPath($s) { $this->_path['base']=$s; }
	public function addPath($type,$file,$path){if($path)$this->_path[$type][$file]=$path;}
	public function setFileInterface($s,$f1='',$f2='') { $this->addPath('interface','base',$s); $this->addPath('interface','f1',$f1);$this->addPath('interface','f2',$f2);}
	public function setFileLangs($s,$f1='',$f2='') { $this->addPath('langs','base',$s); $this->addPath('langs','f1',$f1);$this->addPath('langs','f2',$f2);}
	
	public function doInit()
	{
		if($this->_treeInterface==null){
			$fbase=appPaths('common.config/control/interface');
			$this->_treeInterface=getXCML2Tree(VDCSDTML::getConfigContent($fbase),'key','value');
			if(!$this->_path['interface']['base']) $this->_path['interface']['base']=$this->_path['base'].'interface'.EXT_CONFIG;
			foreach($this->_path['interface'] as $file=>$path){
				$this->doTreeAppend($this->_treeInterface,$path,'key','value');
			}
			//debugTree($this->_treeInterface);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getValue($key,$items='')
	{
		$re=$this->_treeInterface->getItem('page.'.$key).NEWLINE;
		//$re=toReplaceVars($re,$items);
		return $re;
	}
	
	public function getHeader() { return $this->getValue('header'); }
	public function getFooter() { return $this->getValue('footer'); }
	
	public function getSpace($key) { return $this->getValue('space'.($key?'.'.$key:'')); }
	
	public function getFrame($key) { return $this->getValue('frame.'.$key); }
	
	public function getTable($key,$strer1='',$strer2='')
	{
		switch($key){
			case 'head':
				$re=$this->getValue('table.head');
				break;
			case 'end':
				$re=$this->getValue('table.end');
				break;
			default :
				if(left($key,3)=='bar'){
					$tmptitle=$strer1;
					$tmpcols=1;
					$tmpclass='bar';
					if(ins($key,'.')>0) $tmpclass=toSubstr($key,ins($key,'.')+1);
					if(is_numeric($strer2)) $tmpcols=$strer2;
					$re=$this->getValue('table.bar');
					$re=rd($re,'class',$tmpclass);
					$re=rd($re,'cols',$tmpcols);
					$re=rd($re,'title',$tmptitle);
				}
				break;
		}
		return $re;
	}
	
	public function getError($msg,$url)
	{
		return $msg;
		//return PageScript::JSMake($msg,$url);
	}
	
	public function getBlock($title_,$content_,$block_='',$classa='')
	{
		if(len($block_)<1) $block_='block';
		if($block_=='s') $block_='blocks';
		$re=$this->getValue($block_);
		$re=rd($re,'classa',' '.$classa);
		$re=rd($re,'title',$title_);
		$re=rd($re,'content',$content_);
		$re=rd($re,'message',$content_);
		return $re;
	}
	
	public function getMessage($title_,$content_,$url_='')
	{
		if(len($url_)>0){
			$re=$this->getValue('message.url');
			if(len($re)<3) $re=$this->getValue('message_url');
		}
		if(len($re)<3) $re=$this->getValue('message');
		$re=rd($re,'title',$title_);
		$re=rd($re,'content',$content_);
		$re=rd($re,'message',$content_);
		$re=rd($re,'url',$url_);
		return $re;
	}
	
	
	public function getForm($key,$stritems='')
	{
		switch($key){
			case 'head':
				$browsePath=DCS::browsePath(true);
				$tmpname=$stritems ? $stritems : $this->_formname;
				$tmpaction=$this->_actionurl?$this->_actionurl:$browsePath;
				$re=NEWLINE.'<form name="'.$tmpname.'" action="'.$tmpaction.'" method="post">';
				$re.=NEWLINE.'<input type="hidden" name="_chk" value="yes" />';
				$re.=NEWLINE.'<input type="hidden" name="_backurl" value="'.utilCode::toFormValue($browsePath).'" />';
				break;
			case 'end':
				$re=NEWLINE.'</form>';
				break;
			case 'select.data':
				if(len($stritems)<1) $stritems=1;
				if($stritems=='0' || $stritems==0) { $re='<input type="checkbox" class="normal" disabled />'; }
				else{ $re='<input type="checkbox" class="normal" name="_select_data" onclick="javascript:dcs.formx.doSelectAll();" />'; }
				break;
			case 'select.id':
				if(len($stritems)<1) $stritems='0';
				if($stritems=='0' || $stritems==0) { $re='<input type="checkbox" class="normal" disabled />'; }
				else{ $re='<input type="checkbox" class="normal" name="_select_id[]" value="'.$stritems.'" />'; }
				break;
			case 'select.buttons':
				$re='<div class="form-handle">'.$this->getForm('select.button',$stritems).'</div>';
				break;
			case 'select.button':
				$re=NEWLINE.'<select name="_select_handle">';		//<div class=''form-handle''>'
				$stritems=r($stritems,',',';');
				$tmpAry=toSplit($stritems,';');
				for($a=0;$a<count($tmpAry);$a++){
					utilString::lists($tmpAry[$a],$tmpvalue,$tmptitle,'=');
					//list($tmpvalue,$tmptitle)=split('=',$tmpAry[$a],2);
					/*
					if(ins($tmpAry[$a],'=')>0){
						utilString::lists($tmpAry[$a],$tmpvalue,$tmptitle,'=');
						//list($tmpvalue,$tmptitle)=split('=',$tmpAry[$a],2);
						//$tmpvalue=toSubstr($tmpAry[$a],1,ins($tmpAry[$a],':')-1);
						//$tmptitle=toSubstr($tmpAry[$a],ins($tmpAry[$a],':')+1);
					}
					else{
						$tmpvalue=$tmpAry[$a];
						$tmptitle='';
					}
					*/
					if(!$tmptitle){
						$tmptitle=$this->getLangs('handle.option.'.$tmpvalue);
						if(!$tmptitle) $tmptitle=$tmpvalue;
					}
					$re.=NEWLINE.'<option value="'.$tmpvalue.'">'.$tmptitle.'</option>';
				}
				$re.=NEWLINE.'</select> ';
				$re.=NEWLINE.'<input type="button" class="btn handle" name="_sbt" value="'.$this->getLangs('handle.title').'" onclick="javascript:dcs.formx.doSelectClick();" />';
				$re.=NEWLINE.'<script type="text/javascript">$d.load(function(){dcs.formx.doSelectParse()});</script>';
				break;
			case 'button':
				if(ins($stritems,'$$$')>0){
					utilString::lists($stritems,$stritems,$tmpURL,'$$$');
					//list($stritems,$tmpURL)=split('$$$',$stritems,2);
					//$tmpURL=toSubstr($stritems,ins($stritems,'$$$')+3);
					//$stritems=toSubstr($stritems,0,ins($stritems,'$$$')-1);
				}
				if($tmpURL){ $re='<input type="button" class="btn button" value="'.$stritems.'" onclick="javascript:\$p.go(\''.$tmpURL.'\');" />'; }
				else{ $re='<input type="button" class="btn button" value="'.$stritems.'" />'; }
				break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadLangs()
	{
		if($this->_treeLangs==null){
			$path=appPaths('common.config/control/language');
			$this->_treeLangs=VDCSDTML::getConfigTree($path);
			if(!$this->_path['langs']['base']) $this->_path['langs']['base']=$this->_path['base'].'langs'.EXT_CONFIG;
			//debuga($this->_path['langs']);
			foreach($this->_path['langs'] as $file=>$path){
				$this->doTreeAppend($this->_treeLangs,$path);
			}
			//debugTree($this->_treeLangs);
		}
	}
	public function getLangs($strKey)
	{
		if(ins($strKey,'=')>0){
			return toSubstr($strKey,ins($strKey,'=')+1);
		}
		$this->loadLangs();
		if(ins($strKey,':')>0){
			utilString::lists($strKey,$tmpNode,$strKey,':');
			//list($tmpNode,$strKey)=split(':',$strKey,2);
			//$tmpNode=toSubstr($strKey,1,ins($strKey,':')-1);
			//$strKey=toSubstr($strKey,ins($strKey,':')+1);
		}
		if(!$tmpNode) $tmpNode='common';
		//debugx('=='.$tmpNode.'.'.$strKey);
		return $this->_treeLangs->getItem($tmpNode.'.'.$strKey);
	}
	public function getTitles($strKey,$svar='')
	{
		$re=$this->getLangs('titles.'.$strKey);
		//debugx('--title.'.$strKey);
		//debugx($strKey.','.$re.','.$svar);
		if(ins($svar,'=')<1) $svar='title='.$svar;
		//debugx($strKey.'--'.$svar);
		$re=utilRegex::toReplaceVar($re,utilString::toTree($svar,';','='));
		return $re;
	}
	
	public function toReplaceLangs($strer)
	{
		$this->loadLangs();
		return utilRegex::toReplaceVarPrefix($strer,$this->_treeLangs->getFilter('common.'), 'langs.');
	}
	
	public function toOptions($items)
	{
		$rea=array();
		$items=r($items,',',';');
		$tmpAry=toSplit($items,';');
		for($a=0;$a<count($tmpAry);$a++){
			utilString::lists($tmpAry[$a],$tmpvalue,$tmptitle,'=');
			if(!$tmptitle){
				$tmptitle=$this->getLangs('handle.option.'.$tmpvalue);
				if(!$tmptitle) $tmptitle=$tmpvalue;
			}
			array_push($rea,$tmpvalue.'='.$tmptitle);
		}
		return implode(';',$rea);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doTreeAppend(&$tData,$path,$k='',$v='')
	{
		if(isFile($path)) $tData->doAppendTree(VDCSDTML::getConfigTree($path,$k,$v));
		//debugTree($tData);
	}
}
?>
