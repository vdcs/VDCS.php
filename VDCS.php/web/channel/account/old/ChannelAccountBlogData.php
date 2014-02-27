<?
class ChannelAccountBlogData extends ChannelAccountBlogBase
{
	public $s,$p;
	protected $_pagenum=5;
	protected $_listnum=10;
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function setNum($pagenum,$listnum)
	{
	    if($pagenum>0) $this->_pagenum=$pagenum;
	    if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	public function doLoad()
	{
		global $cfg;
		$this->MKEY='data';
		$this->theme->setPageEnd('.'.$this->MKEY);
		
		$this->DataFieldID='d_id';
		
		$cfg->setTitle('sub',$this->DataNames);
		$this->_var['url']=$this->cURL('m','m='.$this->_m_);
		
		$this->loadVcode($this->channelKey.'data');
		$this->loadShield();
	}
	
	public function doParse()
	{
		global $cfg;
		switch($this->action){
			case 'add':
				$cfg->setTitle('sub','发布'.$this->DataNames);
				$this->theme->setModule('form');
				$this->doParseAdd();
				break;
			case 'edit':
				$cfg->setTitle('sub','编辑'.$this->DataNames);
				$this->theme->setModule('form');
				$this->doParseEdit();
				break;
			case 'del':
				$this->doParseDel();
				break;
			default:
				$cfg->setTitle('sub',$this->DataNames.'列表');
				$this->theme->setModule('list');
				$this->doParseList();
				break;
		}
	}
	
	public function doParseAdd()
	{
		global $cfg,$ctl;
		if($this->chn->cfgi('put')!=1){
			$this->doMessage('this','info:提示信息','暂停'.$this->DataNames.'发布服务！感谢您的理解和支持。',$url);
			return;
		}
		if(!isFormPost()) return;
		
		$ctl->treeData->addItem('sortid',posti('sortid'));
		
		$ctl->treeData->addItem('d_topic',post('d_topic',200));
		$ctl->treeData->addItem('d_summary',post('d_summary',200));
		$ctl->treeData->addItem('d_remark',post('d_remark',50000));
		if(len($ctl->treeData->getItem('d_topic'))<1 || len($ctl->treeData->getItem('d_remark'))<1) $ctl->e->addItem(''.$this->DataNames.' 标题和内容 不能为空!');
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'d_topic,d_summary,d_remark');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$ctl->treeData->addItem('keyid',$this->keyid);
			$ctl->treeData->addItem('classid',0);
			$ctl->treeData->addItem('orderid',0);
			$ctl->treeData->addItem('sp_code',1);
			$ctl->treeData->addItem('d_status',$this->chn->cfgi('put.status'));
			$ctl->treeData->addItem('d_tim',DCS::timer());
			$ctl->treeData->addItem('d_tim_up',0);
			
			$this->DataTableFieldsAdd='keyid,classid,sortid,orderid,d_topic,d_summary,d_remark,sp_code,d_status,d_tim,d_tim_up';
			$sql=DB::sqlInsert($this->DataTableName,$this->DataTableFieldsAdd,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			$this->doMessage('this','succeed:操作成功','您已成功发布了一篇'.$this->DataNames.'！',$this->_var['url']);
		}
	}
	
	public function loadData()
	{
		global $cfg,$ctl;
		if($ctl->id<1) $ctl->id=posti('id');
		$this->sqlQuery=DB::sqla($this->_var['sqlquery.key'],$this->DataFieldID.'='.$ctl->id);
		$sql=DB::sqlSelect($this->DataTableName,'','*',$this->sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()>0){
			$this->isData=true;
		}
		if(!$this->isData){
			$this->doMessage('this','info:提示信息','错误的数据提交！',$this->_var['url']);
		}
	}
	public function doParseEdit()
	{
		$this->loadData();if(!$this->isData) return;
		global $cfg,$ctl;
		$ctl->treeData->doAppendTree($this->treeRS);
		if(!isFormPost()) return;
		
		$ctl->treeData->addItem('sortid',posti('sortid'));
		
		$ctl->treeData->addItem('d_topic',post('d_topic',200));
		$ctl->treeData->addItem('d_summary',post('d_summary',200));
		$ctl->treeData->addItem('d_remark',post('d_remark',50000));
		if(len($ctl->treeData->getItem('d_topic'))<1 || len($ctl->treeData->getItem('d_remark'))<1) $ctl->e->addItem(''.$this->DataNames.' 标题和内容 不能为空!');
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'d_topic,d_summary,d_remark');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$ctl->treeData->addItem('sp_code',1);
			$ctl->treeData->addItem('d_status',$this->chn->cfgi('put.status'));
			$ctl->treeData->addItem('d_tim_up',DCS::timer());
			
			$this->DataTableFieldsEdit='sortid,d_topic,d_summary,d_remark,sp_code,d_status,d_tim_up';
			$sql=DB::sqlUpdate($this->DataTableName,$this->DataTableFieldsEdit,$ctl->treeData,$this->sqlQuery);
			//debugx($sql);
			//DB::exec($sql);
			
			$this->doMessage('this','succeed:操作成功','您已成功编辑了一篇'.$this->DataNames.'！',$this->_var['url']);
		}
	}
	
	public function doParseDel()
	{
		$this->loadData();if(!$this->isData) return;
		global $cfg,$ctl;
		
		$ctl->treeData->addItem('d_status',5);
		$ctl->treeData->addItem('d_tim1',DCS::timer());
		
		// 频道数据 删除
		$this->DataTableFieldsDel='d_status,d_tim1';
		$sql=DB::sqlUpdate($this->DataTableName,$this->DataTableFieldsDel,$ctl->treeData,$this->sqlQuery);
		//debugx($sql);
		$sql=DB::sqlDelete($this->DataTableName,$this->sqlQuery);
		//debugx($sql);
		DB::exec($sql);
		
		$this->doMessage('this','succeed:操作成功','您已成功删除了一篇'.$this->DataNames.'！',$this->_var['url']);
	}
	public function doParseRestore()
	{
		$this->loadData();if(!$this->isData) return;
		global $cfg,$ctl;
		
		//if(!isFormPost()) return;
		
		$ctl->treeData->addItem('d_status',$this->chn->cfgi('put.status'));
		$ctl->treeData->addItem('d_tim1',DCS::timer());
		
		// 频道数据 恢复
		$this->DataTableFieldsRestore='c_status,c_tim1';
		$sql=DB::sqlUpdate($this->DataTableName,$this->DataTableFieldsRestore,$ctl->treeData,$this->sqlQuery);
		//debugx($sql);
		DB::exec($sql);
		
		$this->doMessage('this','succeed:操作成功','您已成功恢复了一篇'.$this->DataNames.'！',$url);
	
	}
	
	public function doParseOrder()
	{
		global $cfg,$ctl;
		$isUpdate=false;
		$sql=DB::sqlSelect($this->DataTableName,'','*',$this->_var['sqlquery.key'],'orderid,sortid');
		$tableCat=DB::queryTable($sql);
		$tableCat->doItemBegin();
		for($t=0;$i<$tableCat->getRow();$i++){
			$sortid=$tableCat->getItemValue('sortid');
			$orderid=post('orderid'.$sortid);
			//debugx(($sortid.' - '.$orderid.' - '.toInt($orderid));
			if(len($orderid)>0 && isInt($orderid)){
				$orderid=toInt($orderid);
				if($orderid!=$tableCat->getItemValueInt('orderid')){
					$sql='update '.$this->DataTableName.' set orderid='.$orderid.' where '.$this->_var['sqlquery.key'].' and sortid='.$sortid;
					//debugx($sql);
					DB::exec($sql);
					$isUpdate=true;
				}
			}
			$tableCat->doItemMove();
		}
		$this->doMessage('this','succeed:操作成功','您已成功对'.$this->DataNames.'进行了重新排序！',$this->_var['url']);
	}
	
	public function doParseList()
	{
		global $cfg,$ctl;
		$this->_var['query']=$this->_var['sqlquery.key'];
		switch($ctl->mode){
			case 'audit':
				$this->_var['query']=DB::sqla($this->_var['query'],'d_status in (0,2)');
				break;
			case 'trashbox':
				$this->theme->setModule('trashbox');
				$this->_var['query']=DB::sqla($this->_var['query'],'d_status=5');
				break;
			default:
				$ctl->mode='show';
				$this->_var['query']=DB::sqla($this->_var['query'],'d_status=1');
				break;
		}
		
		$this->_var['url']=urlLink($this->_var['url'],'mode='.$ctl->mode);
		$this->_var['order']='d_tim desc';
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->DataTableName);
		$this->p->setDB('id',$this->DataFieldID);
		$this->p->setDB('field','*');
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order',$this->_var['order']);
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->DataTablePX);
		$this->doDataFilter($this->tableList);
	}
	public function doDataFilter(&$tableData)
	{
		//$tableData->doAppendFields('');
		$tableData->doBegin();
		while($tableData->isNext()){
			if($tableData->getItemValueInt('tim_up')<1) $tableData->setItemValue('tim_up',$tableData->getItemValueInt('tim'));
		}
	}
	
	
	public function doThemeCache()
	{
		//$this->theme->doCacheFilterLoop('ilist','cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
}
?>