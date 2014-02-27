<?
class ChannelCommonCommentX extends ChannelCommonBaseX
{

	public function doLoad()
	{
		$this->channel=queryx('channel');
		$this->rootid=queryi('rootid');
		if($this->channel && $this->rootid) $this->isparams=true;
	}

	public function parseList()
	{
		$types=querys('types');
		if($types) $sqlQuery='types='.DB::q($types,1);
		$params['query']=$sqlQuery;
		$this->tableList=self::querierList($this->ua,$this->channel,$this->rootid,$this->p,$params);
		$this->setTable($this->tableList);
		$this->addVar('ua.total',$this->p->getTotal());
		$this->addVarPaging();
		$this->setSucceed();
	}

	public function parsePost()
	{
		if(!$this->isparams){
			$this->setStatus('params');
			return;
		}
		/*
		if(!isPost()){
			$this->setStatus('nopost');
			return;
		}
		$this->doFormData();
		if($this->isRaiseError()) return;
		*/
		$summary=post('summary',250);
		if(len($summary)<1) $summary=post('message',250);
		$topic=utilCode::toHTMLTag($summary,0,20);	
		$types=postx('types');
		$rank=postx('rank');
		if(len($summary)<1){
			$this->setStatus('data','评论内容不能为空');
			return;
		}
		$this->treeData->addItem('channel',$this->channel);
		$this->treeData->addItem('rootid',$this->rootid);
		$this->treeData->addItem('types',$types);
		$this->treeData->addItem('topic',$topic);		
		$this->treeData->addItem('summary',$summary);
		$this->treeData->addItem('poll_rank',$rank);
		$_status=self::post($this->ua,$this->treeData);		
		if($_status!=1){
			$this->setStatus('failed','数据保存失败');
			return;
		}
		$this->setSucceed();
	}
	protected function doFormData()
	{
		
	}

	
	/*
	########################################
	########################################
	*/
	const TableName			= 'dbd_comment';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= '';
	
	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		if($id<1) return $treeRS;
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		//$sqlQuery=DB::sqla($sqlTerm,'');
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery);
		$treeRS=DB::queryTree($sql);
		//$treeRS=self::doFilterTree($treeRS);
		return $treeRS;
	}
	public static function doFilterTree($treeRS)
	{
		
	}

	public static function querierList($ua,$channel,$rootid,&$p=null,$params=array())
	{
		$params['query']=DB::sqla($params['query'],'channel='.DB::q($channel,1).' and rootid='.$rootid);
		return self::querier($ua,$p,$params);
	}
	public static function querier($ua,&$p=null,$params=array())
	{
		$params['table']=self::TableName;
		//$params['query']=DB::sqla($params['query'],'uuid='.$ua->id);
		$params['order']='tim desc';
		$params['listnum_def']=5;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterList($tableData);
		return $tableData;
	}
	public function doFilterList(&$tableData)
	{
		UaExtend::appendInfo($tableData);
		$tableData->doAppendFields('message');
		$tableData->begin();
		while($tableData->next()){
			$tableData->setItemValue('message',VDCSCodes::toCodes($tableData->getItemValue('summary'),1));
		}
	}


	public static function post($ua,$tData,&$id=0)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('status',1);
		$_status=self::add($tData,$id);
		return $_status;
	}
	public static function add($tData,&$id=0)
	{
		$_status=0;
		$sql=DB::sqlInsertx(self::TableName,self::TableFields,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec){
			$_status=1;
			$id=DB::insertid();
		}
		return $_status;
	}


	public static function toPollValue(&$poll)
	{
		$re=-1;
		switch($poll){
			case 'good':		$re=5;break;
			case 'bad':		$re=1;break;
			case 'neutral':		$re=0;break;
			default:		$re=-1;$poll='';break;
		}
		return $re;
	}
	
}
?>