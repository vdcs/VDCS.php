<?
trait ModuleRefDisp
{
	use ChannelRefListx;
	
	public function doLoadMudle()
	{
		$this->TableName	= 'db_company_gbook';
		$this->TablePX		= 'm_';
		$this->FieldID		= 'm_id';
		
		$this->TableFields['base:add']='
				uaid,rootid,dataid,uurc,uuid,
				m_names,m_location,m_marks,m_prop1,m_prop2,m_prop3,m_prop4,m_prop5,
				m_nickname,m_befrom,m_im,m_im1,m_im2,m_im3,m_im4,m_im5,
				m_realname,m_email,m_mobile,m_call,
				m_company,m_url,m_address,m_postcode,m_phone,m_fax,
				m_title,m_topic,m_icon,m_summary,m_message,m_remark,
				sp_code,sp_ip,sp_agent,
				m_status,m_tim
				';
		
		$this->_var['query']='uaid='.$this->ua->id;
		$this->_var['order']=$this->TablePX.'tim desc';
		$this->_var['modes']=[
			'default'=>[
				'value'=>'show',
				'names'=>'',
				'query'=>$this->TablePX.'status=1',
			],
			'new'=>[
				'value'=>'',
				'names'=>'',
				'query'=>$this->TablePX.'isread<1',
			],
			'read'=>[
				'value'=>'',
				'names'=>'',
				'query'=>$this->TablePX.'isread>0',
			],
			'trashbox'=>[
				'value'=>'',
				'names'=>'',
				'query'=>$this->TablePX.'status=5',
			],
		];
	}
	
	public function doParseDisp()
	{
		switch($this->action){
			case 'view':
				$this->theme->setModule('view');
				$this->doParseView();
				break;
			default:
				$this->theme->setModule('list');
				$this->doParseList();
				break;
		}
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseList()
	{
		global $ctl;
		$modeitem=$this->_var['modes'][$ctl->mode]?$this->_var['modes'][$ctl->mode]:$this->_var['modes']['default'];
		if($modeitem){
			if($modeitem['value']) $ctl->mode=$modeitem['value'];
			if($modeitem['query']) $this->_var['query']=DB::sqlAppend($this->_var['query'],$modeitem['query']);
		}
		
		$this->_var['url']='?mode='.$ctl->mode;
		
		$this->initPaging();
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->TableName);
		$this->p->setDB('id',$this->FieldID);
		$this->p->setDB('field',$this->_var['fields.list']?$this->_var['fields.list']:'*');
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order',$this->_var['order']);
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->TablePX);
		$this->doDataListFilter($this->tableList);
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
		global $ctl;
		$this->id=$ctl->id;
		$sqlquery=DB::sqlAppend($this->_var['query'],$this->FieldID.'='.$this->id);
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlquery,'',1);
		$this->treeView=DB::queryTree($sql);
		if($this->treeView->getCount()<1){
			go('?');
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
	public function doThemeCacheDisp()
	{
		$this->doThemeCacheList('list');
		
	}
	
}
?>