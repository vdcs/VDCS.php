<?
class WebPageView
{
	const VIEW_SYNC_PEAK		= 20;
	const VIEW_SYNC_SYMBOL		= ',';
	
	public $p=null;
	public $_var=array(),$_struct=array();
	public $treeData=null,$treeInfo=null;
	protected $ua;
	public function __construct()
	{
		$this->ua=&$GLOBALS['ua'];
		
		$this->_var['is']=false;
		
		$this->_var['q.id']='id';
		$this->_var['id']=-1;
		$this->_var['dataid']=-1;
		$this->_var['classid']=-1;
		$this->_var['page']=-1;
		
		$this->_var['isuser']=true;
		$this->_var['istotal']=true;
		$this->_var['AuthMode']=1;
		$this->_var['MultiMode']=0;
		$this->_var['PopedomMode']=1;
		$this->_var['isPopedom']=false;
		$this->_var['isPopedomCheck']=true;
		$this->_var['isPopedomChecked']=false;
		
		$this->_var['emoney']=0;
		$this->_var['points']=0;
		$this->_var['isNotePay']=false;
		
		$this->_struct['id']='';
		$this->_struct['topic']='topic';
		$this->_struct['remark']='remark';
		
		$this->_struct['sp_popdom']='sp_popedom';
		$this->_struct['sp_code']='sp_code';
		$this->_struct['sp_keyword']='sp_keyword';
	}
	public function __destruct()
	{
		unset($this->_var,$this->_struct,$this->treeData,$this->p);
	}
	
	
	public function isDat(){return $this->_var['is'];}
	public function isTotal($v=null){if(!is_null($v)) $this->_var['istotal']=$v;return $this->_var['istotal'];}
	
	public function getVar($k){return $this->_var[$k];}
	public function setVar($k,$v){$this->_var[$k]=$v;}
	
	public function getStruct($k){return $this->_struct[$k];}
	public function setStruct($k,$v){$this->_struct[$k]=$v;}
	
	public function toSQLComplex($sql)
	{
		$sql=rd($sql,'id',$this->_var['id']);
		$sql=rd($sql,'table.name',$this->_struct['table.name']);
		$sql=rd($sql,'table.px',$this->_struct['table.px']);
		$sql=rd($sql,'tpx',$this->_struct['table.px']);
		return $sql;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->isInit)return;$this->isInit=true;
		if($this->_var['id']<0) $this->_var['id']=queryi($this->_var['q.id']);
		$this->id=$this->_var['id'];
	}
	
	public function doLoad()
	{
		global $cfg;
		$this->doInit();
		if(!$this->_struct['table.px']) $this->_struct['table.px']=$cfg->chn->getSQLStruct('view.px');
		if(!$this->_struct['table.px']) $this->_struct['table.px']=$cfg->chn->getSQLStruct('table.px');
		if(!$this->_struct['id']) $this->_struct['id']=$cfg->chn->getSQLStruct('table.field.id');
		
		if(!$this->_struct['sql'] && $this->_struct['table.name']){
			$this->_struct['sql']=DB::sqlSelect($this->_struct['table.name'],'','*',$this->_struct['query'],'',1);
		}
		$_var['sql']=$this->_struct['sql'];
		if(!$_var['sql']) $_var['sql']=$cfg->chn->getSQLStruct('view.sql');
		$_var['sql']=$this->toSQLComplex($_var['sql']);
		$this->treeData=DB::queryTree($_var['sql']);
		if($this->treeData->getCount()<1) return;
		//debugTree($this->treeData);
		
		if(len($this->_struct['table.px'])>0){
			$this->treeData->doFilter($this->_struct['table.px']);
		}
		
		// id
		if(!$this->treeData->isItem('id')) $this->treeData->addItem('id',$this->id);
		
		if(!$this->treeData->isItem('iscomment')){
			$iscomment=1;
			$this->treeData->addItem('iscomment',$iscomment);
		}
		
		$this->_var['is']=true;
		//debugTree($this->treeData);
	}
	
	public function doParse()
	{
		global $cfg;
		if(!$this->_var['is']) return;
		
		//content
		$_var['info.sql']=$this->_struct['info.sql'];
		if(!$_var['info.sql']) $_var['info.sql']=$cfg->chn->getSQLStruct('view.sql.info');
		$_var['info.sql']=$this->toSQLComplex($_var['info.sql']);
		if(len($_var['info.sql'])>0){
			$this->treeInfo=DB::queryTree($_var['info.sql']);
			if($this->treeInfo->getCount()>0){
				if(len($this->_struct['table.px'])>0){
					$this->treeInfo->doFilter($this->_struct['table.px']);
				}
				$this->treeData->doAppend($this->treeInfo);
			}
		}
		
		$this->_var['classid']=$this->treeData->getItemInt('classid');
		if($this->_var['PopedomMode']>0){
			$this->_var['Popedom']=$this->treeData->getItem($this->_struct['sp_popdom']);
			/*
			if(len($this->_var['Popedom'])<1){
				debug $cfg->clas->getValue($this->_var['classid'],'sp_popedom');
			}
			*/
			if(len($this->_var['Popedom'])>0) $this->_var['isPopedom']=true;
			$this->_var['emoney']=$this->treeData->getItemNum('sp_emoney');
			$this->_var['points']=$this->treeData->getItemInt('sp_points');
			if($this->_var['emoney']>0 || $this->_var['points']>0) $this->_var['isNotePay']=true;
			
			if($this->_var['AuthMode']==1 && ($this->_var['isNotePay'] || $this->_var['isPopedom'])){
				//$this->ua->setAuth(1);
				//$this->ua->setAuthMode(2);
				//$this->ua->doInitAgain();
			}
		}
		
		if($this->_var['isuser']){
			$this->doParseUa();
		}
		
		if($this->_var['istotal']){
			$vcodes=DCS::sessionGet('update.totals');
			$vcode=$cfg->getChannel().$this->_var['id'];
			if(!utilStrings::isExtentValue($vcodes,$vcode,self::VIEW_SYNC_SYMBOL)){
				$_var['sql.update']=$this->_struct['sql.update'];
				if(!$_var['sql.update']) $_var['sql.update']=$cfg->chn->getSQLStruct('view.update');
				$_var['sql.update']=$this->toSQLComplex($_var['sql.update']);
				//debugx($_var['sql.update']);
				DB::execBatch($_var['sql.update']);
				$vcodes=utilStrings::toExtentAppend($vcodes,$vcode,self::VIEW_SYNC_SYMBOL,self::VIEW_SYNC_PEAK);
				DCS::sessionSet('update.totals',$vcodes);
			}
		}
	}
	
	public function doParsePic($field='')
	{
		if(len($field)<1) $field='pic';
		$this->treeData->setItem($field,CommonTheme::toUploadURL($this->treeData->getItem($field)));
	}
	
	public function doParseUa($field='uuid',$fieldc='corpid')
	{
		UaExtend::appendTreeInfo($this->treeData,['relateid'=>$field]);
		/*
		$this->corpid=$this->treeData->getItemInt($fieldc);
		if($this->corpid>0){
			$_uaname=&Ua::instance('company')->getInfoName($this->corpid);
			$this->treeData->addItem('corpname',$_uaname);
		}
		$this->treeData->addItem('uaname',$_uaname);
		*/
	}
	
	/*
	public function getRelateQuery()
	{
		Dim oSearch As New libSearch
		oSearch.init=dcs.init
		oSearch.doInit
		//oSearch=newSearch()
		oSearch.setKeyword ($this->treeData->getItem(var_structKeyword))
		oSearch.setField (var_structKeyword)	  //var_structTopic
		oSearch.setFields (var_structKeyword.'=6,'.var_structTopic.'=6')
		oSearch.doParse
		getRelateQuery=oSearch.toAppendQuery(var_structID.'<>'.id,'')
		oSearch=Nothing
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function addData($strKey,$strValue){ $this->treeData->addItem($strKey,$strValue); }
	public function setData($strKey,$strValue){ $this->treeData->addItem($strKey,$strValue); }
	public function doDataAppendTree($strTree){ $this->treeData->doAppendTree($strTree); }
	
	public function getData($strKey){ return $this->treeData->getItem($strKey); }
	public function getDataInt($strKey){ return $this->treeData->getItemInt($strKey); }
	public function getDataNum($strKey){ return $this->treeData->getItemNum($strKey); }
	public function getDatas($strKey){ return utilCode::toHTML($this->treeData->getItem($strKey)); }
	
	public function getDataFlag($k)
	{
		global $cfg;
		$re='';
		switch($k){
			case 'classname':	$re=$cfg->clas->getName($this->_var['classid']);break;
			case 'class.nav':	$re=$cfg->clas->getNav($this->_var['classid']);break;
			case 'specialname':	$re=$cfg->spec->getName($this->_var['specialid']);break;
			case 'special.nav':	$re=$cfg->spec->getNav($this->treeData->getItem('specialid'));break;
		}
		return $re;
	}
	
	public function getDataRemarks(){ return VDCSCodes::toCodes($this->treeData->getItem('remark'),$this->treeData->getItemInt($this->_struct['sp_code'])); }
	public function getDataRemarkes()
	{
		$prePage=0;
		$this->doPopedomCheck();
		$re=$this->_var['PopedomMessage'];
		if(len($re)<1){
			$re=$this->getDataRemarks();
			$prePage=$this->treeData->getItemInt('prepage');
		}
		if($prePage>0){
			if($prePage=1){
				if(ins($re,'[pagecute]')>0) $re=r($re,'[pagecute]','[paging]');
				$reAry=toSplit($re,'[paging]');
				$pageNum=count($reAry)+1;
			}
			else{
				$lenc=len($re);
				$pageNum=(int)($lenc/$prePage);
				if($lenc>$prePage*$pageNum) $pageNum=$pageNum+1;
				//$reAry($pageNum-1)
				for($i=0;$i<$pageNum;$i++){
					$tmpLen=$lenc;
					if($i>0) $tmpLen=$lenc-$prePage;
					$re=right($re,$tmpLen);
					$reAry[$i]=left($re,$prePage);
				}
			}
			
			global $cfg;
			$this->_var['page']=queryi('page');
			if($this->_var['page']>$pageNum || $this->_var['page']<1) $this->_var['page']=1;
			
			$tmpURL=rd($cfg->url('view.page'),'id',$this->_var['id']);
			//debug tmpURL
			//####################
			$this->p=new libPaging();
			$this->p->loadTemplate('view');
			$this->p->getConfig('url',$tmpURL);
			$this->p->setListNum(1);
			$this->p->setPage($this->_var['page']);
			$this->p->setTotal($pageNum);
			$this->p->doParse();
			$pageBase=$this->p->getPageBase();
			if($pageNum>1) $this->_var['page']=$this->p->toString();
			//####################
			$re=$reAry[$pageBase];
		}
		return $re;
	}
	
	public function getDataContent($k,$code=-1)
	{
		if($code<0) $code=$this->getCode();
		return VDCSCodes::toCodes($this->treeData->getItem($k),$code);
	}
	
	public function getCode(){ return $this->treeData->getItemInt($this->_struct['sp_code']); }
	
	
	/*
	########################################
	########################################
	*/
	public function setAuthMode($s){ $this->_var['AuthMode']=$s; }
	public function getAuthMode(){ return $this->_var['AuthMode']; }
	
	public function setMultiMode($s){ $this->_var['MultiMode']=$s; }
	public function getMultiMode(){ return $this->_var['MultiMode']; }
	
	public function setPopedomMode($s){ $this->_var['PopedomMode']=$s; }
	public function getPopedomMode(){ return $this->_var['PopedomMode']; }
	
	public function isPopedomCheck(){ return $this->_var['isPopedomCheck']; }
	
	public function doPopedomCheck()
	{
		if(!$this->_var['isPopedomChecked']){
			$re='';
			if($this->_var['PopedomMode']>0){
				/*
				initUsere();
				global $usere;
				if(len($this->_struct['sp_popdom'])>0) $re=$usere->getPopedomMessage($this->treeData->getItem($this->_struct['sp_popdom']));
				$tmpTree=newTree();
				$_id=$this->_var['dataid'];
				if($_id<1) $_id=$this->_var['id'];
				//内容权限
				if(len($re)<1){
					if($this->_var['isNotePay']){
						//tmpTree=New utilTree
						$tmpTree->addItem('channel',$cfg->getChannel());
						$tmpTree->addItem('handle','view');
						$tmpTree->addItem('id',$_id);
						$tmpTree->addItem('sp_emoney',$this->_var['emoney']);
						$tmpTree->addItem('sp_points',$this->_var['points']);
						$re=$usere->getNotePayMessage($tmpTree,newTree());
					}
				}
				*/
				/*
				//分类权限
				if(len($re)<1){
					$tmpClassid=$this->getData('classid');
					$tmpCPower=$cfg->clas->getChannelValue($cfg->getChannel(),$tmpClassid,$this->_struct['sp_popdom'])
					re=$usere->getPopedomMessage(tmpCPower)
					if(Len(re)<1){
						$this->_var['isNotePay']=false
						tmpCEmoney=toNum($cfg->cls.getChannelValue($cfg->getChannel(),tmpClassid,'sp_emoney'))
						tmpCpoints=toInt($cfg->cls.getChannelValue($cfg->getChannel(),tmpClassid,'sp_points'))
						if(CDbl(tmpCEmoney)>0 || CLng(tmpCpoints)>0){ $this->_var['isNotePay']=true
						if($this->_var['isNotePay']){
							//tmpTree=New utilTree
							$tmpTree->addItem('channel',$cfg->getChannel().'.class.'.tmpClassid);
							$tmpTree->addItem('handle','list');
							$tmpTree->addItem('id',$_id);
							$tmpTree->addItem('sp_emoney',tmpCEmoney);
							$tmpTree->addItem('sp_points',tmpCpoints);
							re=$usere->getNotePayMessage(tmpTree,New utilTree)
						}
					}
				}
				*/
				if(len($re)>0) $this->_var['isPopedomCheck']=false;
			}
			$this->_var['PopedomMessage']=$re;
			$this->_var['isPopedomChecked']=true;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCache($re,$vo='')
	{
		$re=CommonTheme::toCacheFilterVar($re,'{@id}',$vo.'.id');
		//####################
		$_pattern='<view:'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		//debugAry($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$this->toCacheFlagValue($_matches[1][$m],$_matches[3][$m],$_matches[5][$m],$vo);
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'='.$rFlagValue.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_pattern='{\$view:'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$this->toCacheFlagValue($_matches[1][$m],$_matches[3][$m],$_matches[5][$m],$vo);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='{\$\$view:'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$this->toCacheFlagValue($_matches[1][$m],$_matches[3][$m],$_matches[5][$m],$vo);
			$re=r($re,$_matches[0][$m],'\'\.'.$rFlagValue.'\.\'');
		}
		//####################
		return $re;
	}
	public function toCacheFlagValue($rFlag,$rFlagOption,$rFlagOption2,$vo='view.')
	{
		if(len($prefix)>0 && right($prefix,1)!='.') $prefix.='.';
		if(right($prefix,5)!='view.') $prefix.='view.';
		$vo=CommonTheme::toVarCache($vo,1);
		$rFlag=toLower($rFlag);
		switch($rFlag){
			case '_summary':	$re='utilCode::toHtmlExplain('.$vo.'getData(\''.$rFlag.'\'))';break;
			case 'remark':
			case 'remarkes':
			case '__remark':	$re=$vo.'getDataRemarkes()';break;
			case 'remarks':		$re=$vo.'getDataRemarks()';break;
			case '_remark':		$re=$vo.'getData(\'remark\')';break;
			case 'class.nav':
			case 'special.nav':
			case 'specialname':
			case 'classname':	$re=$vo.'getDataFlag(\''.$rFlag.'\')';break;
			default:		$re=$vo.'getDatas(\''.$rFlag.'\')';break;
		}
		if(len($rFlagOption)>0) $re='utilCode::toEncodeFilterValue('.$re.',\''.$rFlagOption.'\',\''.$rFlagOption2.'\')';
		return $re;
	}
	
	public function toFlagValue($rFlag,$rFlagOption,$rFlagOption2)
	{
		$rFlag=toLower($rFlag);
		switch($rFlag){
			case 'summary':		$re=utilCode::toHtmlExplain($this->treeData->getItem($rFlag));break;
			case 'remarkes':	$re=$this->getDataRemarkes();break;
			case 'remarks':		$re=$this->getDataRemarks();break;
			case 'class.nav':
			case 'special.nav':
			case 'classname':	$re=$this->getDataFlag($rFlag);break;
			default:		$re=$this->getDatas($rFlag);break;
		}
		if(len($rFlagOption)>0) $re=utilCode::toEncodeFilterValue($re,$rFlagOption,$rFlagOption2);
		return $re;
	}
	public function toDTML($re)
	{
		//$this->doPopedomCheck();
		//####################
		$_pattern='<label:view\(\"'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'\"\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$this->toFlagValue($_matches[1][$m],$_matches[3][$m],$_matches[5][$m]);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<label:view.'.PATTERN_FLAG_VAR.'>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			switch(toLower($_matches[1][$m])){
				case 'id':	$rFlagValue=$id;break;
				case 'page':	$rFlagValue=$this->_var['page'];break;
				case 'paging':	$rFlagValue=$this->_var['page'];break;
				default:	$rFlagValue='';break;
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		return $re;
	}
}
?>