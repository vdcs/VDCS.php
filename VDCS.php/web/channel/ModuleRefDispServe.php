<?
trait ModuleRefDispServe
{
	use ChannelRefListx;
	
	public function doParseDisp()
	{
		switch($this->action){
			case 'post':
				$this->doParsePost();
				break;
			case 'view':
				$this->doParseView();
				break;
			default:
				$this->doParseList();
				break;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doListInit()
	{
		
	}
	protected function isListParams()
	{
		$re=true;
		return $re;
	}
	protected function getListQuery($query)
	{
		return $query;
	}
	protected function getListURL()
	{
		$re='?';
		if($this->_p_) $re=urlLink($re,'p='.$this->_p_);
		if($this->_p_) $re=urlLink($re,'m='.$this->_p_);
		if($this->_x_) $re=urlLink($re,'x='.$this->_x_);
		if($this->_chn_) $re=urlLink($re,'channel='.$this->_chn_);
		
		if($this->rootid) $re=urlLink($re,'rootid='.$this->rootid);
		if($this->mode) $re=urlLink($re,'mode='.$this->mode);
		return $re;
	}
	protected function doListExtend()
	{
		
	}
	public function doParseList()
	{
		$this->doListInit();
		if(!$this->isListParams()){
			$this->setStatus('params');
			return;
		}
		
		$listnum=queryi('listnum');
		if($listnum>0 && $listnum<50) $this->setNum(0,$listnum);
		
		$this->_var['query.list']=$this->_var['query'];
		$this->modes=$this->_var['modes'][$ctl->mode]?$this->_var['modes'][$ctl->mode]:$this->_var['modes']['default'];
		if($this->modes){
			if($this->modes['value']) $ctl->mode=$this->modes['value'];
			if($this->modes['query']) $this->_var['query.list']=DB::sqlAppend($this->_var['query.list'],$this->modes['query']);
		}
		
		$this->_var['query.list']=$this->getListQuery($this->_var['query.list']);
		
		$this->_var['url']=$this->getListURL();
		$this->addVar('url',$this->_var['url']);
		
		$this->doListExtend();
		//debugx($this->_var['query.list']);
		
		$this->initPaging();
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->TableName);
		$this->p->setDB('id',$this->FieldID);
		$this->p->setDB('field',$this->_var['fields.list']?$this->_var['fields.list']:'*');
		$this->p->setDB('query',$this->_var['query.list']);
		$this->p->setDB('order',$this->_var['order']);
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->TablePX);
		$this->doDataListFilter($this->tableList);
		
		$this->setTable($this->tableList);
		$this->addVarPaging();
	}
	protected function doDataListFilter(&$tableData)
	{
		return;
		$tableData->doAppendFields('time,trans_time');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('time',VDCSTime::toString($tableData->getItemValue('tim')));
			//debugx($tableData->getItemValue('uuid'));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseView()
	{
		$this->id=$ctl->id;
		$sqlquery=DB::sqlAppend($this->_var['query'],$this->FieldID.'='.$this->id);
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlquery,'',1);
		$this->treeView=DB::queryTree($sql);
		if($this->treeView->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		$this->treeView->doFilter($this->TablePX);
		if($this->treeView->isItem('isread') && $this->treeView->getItemInt('isread')<1){
			$treeSet=newTree();
			$treeSet->addItem($this->TablePX.'isread','1');
			$treeSet->addItem($this->TablePX.'tim_read',DCS::timer());
			$sql=DB::sqlUpdate($this->TableName,$treeSet->getFields(),$treeSet,$sqlquery);
			DB::exec($sql);
		}
		$spcode=$this->treeView->isItem('sp_code')?$this->treeView->getItemInt('sp_code'):1;
		$this->treeView->addItem('message.codes',VDCSCodes::toCodes($this->treeView->getItem('message'),$spcode));
		$this->treeView->addItem('remark.codes',VDCSCodes::toCodes($this->treeView->getItem('remark'),$spcode));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParsePost()
	{
		if(!isFormPost()) return;
		$this->setStatus('ready');
		
		$this->doRaiseError();
	}
	
}
?>