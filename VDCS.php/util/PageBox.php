<?
class PageBox
{
	protected $_Mode='',$_ItemKey='',$_TemplateKey='';
	protected $_Output='';
	protected $_TablePX='',$_TableFieldID='',$_treeTableFieldsValue=null,$_TableFieldsValue='';
	public $treeVar=null,$treeDat=null,$tableData=null,$_treeConfig=null;
	
	public function __construct()
	{
		$this->treeVar=new utilTree();
		$this->treeDat=new utilTree();
		$this->tableData=new utilTable();
		$this->_treeConfig=new utilTree();
	}
	public function __destruct()
	{
		unset($this->_Output);
		unset($this->treeVar,$this->treeDat,$this->tableData,$this->tableList,$this->_treeConfig);
	}
	
	
	/*
	'########################################
	'########################################
	Public Sub initTable()
	    If Not commons.isClass(dictTable) Then Set dictTable = commons.newDict()
	End Sub
	Public Sub addTable(ByVal name_ As String, ByVal table_ As utilTable)
	    dictTable.Add "table=" & name_, table_.getArray
	End Sub
	Public Function getTable(ByVal name_ As String) As utilTable
	    Set getTable = New utilTable
	    If dictTable.Exists("table=" & name_) Then
	        getTable.setArray (dictTable.Item("table=" & name_))
	    End If
	End Function
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function setMode($s) { $this->_Mode=$s; }
	public function getMode() { return $this->_Mode; }
	
	public function getOutput() { return $this->_Output; }
	public function setOutput($s) { $this->_Output=$s; }
	
	public function setDataTable($strTable) { $this->tableData=$strTable; }
	
	public function setConfig($k,$v) { $this->_treeConfig->addItem($k,$v); }
	
	public function getVar($k) { return $this->treeVar->getItem($k); }
	public function addVar($k,$v) { $this->treeVar->addItem($k,$v); }
	public function setVarTree($strTree) { $this->treeVar->setArray($strTree->getArray()); }
	
	public function getDat($k) { return $this->treeDat->getItem($k); }
	public function addDat($k,$v) { $this->treeDat->addItem($k,$v); }
	public function setDatTree($strTree) { $this->treeDat->setArray($strTree->getArray()); }
	
	public function setListKey($s) { $this->_ListKey=$s; }
	public function getListKey() { $re=$this->_ListKey?$this->_ListKey:'list'; return $re; }
	
	
	/*
	########################################
	########################################
	*/
	public function setTablePX($s) { $this->_TablePX=$s; $this->addVar('table.px',$s); $this->addVar('table.px',$s); }
	public function getTablePX() { return $this->_TablePX; }
	
	public function setTableFieldID($s) { $this->_TableFieldID=$s; }
	public function getTableFieldID() { return $this->_TableFieldID; }
	
	public function setTableFieldsValue($s) { $this->_TableFieldsValue=$s; }
	public function getTableFieldsValue() { return $this->_TableFieldsValue; }
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		$this->_treeTableFieldsValue=utilString::toTree($this->_TableFieldsValue,';','=');
		//debugTree($this->_treeTableFieldsValue);
		$this->tableList=newTable();
		$this->tableList->setArray($this->tableData->getArray());
		$this->doDataFilter($this->tableList);
		$this->tableList->doFilter($this->_TablePX);
		//id={@table.px}id;topic={@table.px}topic;time={@table.px}tim
	}
	
	public function doDataFilter(&$tableData)
	{
		$tableData->doAppendFields('_id,_topic,_tim,_time,times,selectid');
		$this->_treeTableFieldsValue->doBegin();
		for($f=0;$f<$this->_treeTableFieldsValue->getCount();$f++){
			$tableData->doAppendFields('_'.$this->_treeTableFieldsValue->getItemKey());
			$this->_treeTableFieldsValue->doMove();
		}
		$tableData->doBegin();
		while($tableData->isNext()){
			$this->_treeTableFieldsValue->doBegin();
			for($f=0;$f<$this->_treeTableFieldsValue->getCount();$f++){
				$tmpKey=$this->_treeTableFieldsValue->getItemKey();
				$tmpField=$this->_treeTableFieldsValue->getItemValue();
				$isadded=true;
				//else //if(inp('topic,title',$tmpKey)>0) $tmpValue=$tableData->getItemValue($tmpField);
				if(inp('time',$tmpKey)>0){
					$tmpValue=$tableData->getItemValueInt($tmpField);
					$tableData->setItemValue('_tim',$tmpValue);
					$tableData->setItemValue('_time',VDCSTime::toString($tmpValue));
					$tableData->setItemValue('times',utilCode::toTimes($tmpValue));
					$isadded=false;
				}
				else $tmpValue=$tableData->getItemValue($tmpField);
				//$tableData->setItemValue($tmpKey,$tmpValue);
				//debugx($tmpKey.'='.$tmpValue);
				if($isadded) $tableData->setItemValue('_'.$tmpKey,$tmpValue);
				$this->_treeTableFieldsValue->doMove();
			}
			//if(!$tableData->isField('id')) $tableData->setItemValue('id',$tableData->getItemValue($this->_TableFieldID));
			if(!$tableData->isField('_id')) $tableData->setItemValue('_id',$tableData->getItemValue($this->_TableFieldID));
			$tableData->setItemValue('selectid',$tableData->getItemValue($this->_TableFieldID));
		}
	}
	
	
	public function toListItem($treeItem)
	{
		$this->_treeTableFieldsValue->doBegin();
		for($f=0;$f<$this->_treeTableFieldsValue->getCount();$f++){
			$tmpKey=$this->_treeTableFieldsValue->getItemKey();
			$tmpField=$this->_treeTableFieldsValue->getItemValue();
			$isadded=true;
			if(inp('topic,title',$tmpKey)>0) $tmpValue=utilCode::toHTML($treeItem->getItem($tmpField));
			else if(inp('time',$tmpKey)>0){
				$tmpValue=$treeItem->getItemInt($tmpField);
				if(!$treeItem->isItem('_tim')) $treeItem->addItem('_tim',$tmpValue);
				if(!$treeItem->isItem('_time')) $treeItem->addItem('_time',VDCSTime::toString($tmpValue));
				if(!$treeItem->isItem('times')) $treeItem->addItem('times',utilCode::toTimes($tmpValue));
				$isadded=false;
			}
			else $tmpValue=$treeItem->getItem($tmpField);
			$treeItem->addItem($tmpKey,$tmpValue);
			if($isadded) $treeItem->addItem('_'.$tmpKey,$tmpValue);
			$this->_treeTableFieldsValue->doMove();
		}
		if(!$treeItem->isItem('id')) $treeItem->addItem('id',$treeItem->getItem($this->_TableFieldID));
		if(!$treeItem->isItem('_id')) $treeItem->addItem('_id',$treeItem->getItem($this->_TableFieldID));
		if(!$treeItem->isItem('selectid')) $treeItem->addItem('selectid',$treeItem->getItem($this->_TableFieldID));
		return $treeItem;
	}
	
	public function toTemplatFilter($re)
	{
		
		return $re;
	}
	
	
	public function toDTML($re)
	{
		$re=utilRegex::toReplaceRegex($re,$this->treeDat,VDCSDTML::PATTERN_DTML_DAT);
		//####################
		$n=preg_match_all('/<loop:item>'.PATTERN_FLAG_CONTENT.'<\/loop:item>/ies',$this->_Output,$_matches);		//,PREG_PATTERN_ORDER|PREG_OFFSET_CAPTURE
		for($i=0;$i<$n;$i++){
			$this->_Output=r($this->_Output,$_matches[0][$i],$this->toParseListData($_matches[1][$i]));
		}
		//####################
		unsetr($_matches);
		//####################
		$this->tableData->doBegin();
		while($this->tableData->isNext()){
			$re.=VDCSDTML::toReplaceEncodeFilter($strer,$this->toListItem($this->tableData->getItemTree()),VDCSDTML::PATTERN_DTML_ITEMS);
		}
		return $re;
	}
	
	public function toDTMLCache($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='box';
		$_oprefix=$oprefix;
		$opre=CommonTheme::toVarCache($oprefix);$oprefix=CommonTheme::toVarCache($oprefix,1);
		//####################
		//$re=utilRegex::toReplaceRegex($re,$this->treeVar,'<box:'.PATTERN_FLAG_LABEL.'>');
		$_matches=utilRegex::toMatches($re,'<box:'.PATTERN_FLAG_LABEL.'>');
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=CommonTheme::HTMLMarkHeads.$oprefix.'getVar('.CommonTheme::toVarParams($_matches[1][$m]).')'.CommonTheme::HTMLMarkFoot;
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$listKey=$this->getListKey();
		/*
		$_matches=utilRegex::toMatches($re,CommonTheme::toPatternLoop($listKey));
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$_matches[0][$m];
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		*/
		//####################
		$re=CommonTheme::toCacheFilterLoop($re,$listKey,$_oprefix.'.tableData',array(
			'templat.filter' => array(CommonTheme::toVarArray($_oprefix),'toTemplatFilter'),
			'item.func' => $oprefix.'toListItem'
		));
		/*
		$listKey=$this->getListKey();	//'list'
		$_pattern='<loop:'.$listKey.'>'.PATTERN_FLAG_CONTENT.'<\/loop>';
		//debugx(($_pattern);
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($i=0;$i<count($_matches[0]);$i++){
			$rFlagValue=$_matches[1][$i];
			
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		*/
		//####################
		unset($_matches);
		//####################
		return $re;
		
	}
}
