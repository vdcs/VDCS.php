<?
trait ChannelRefView
{
	public $view;
	public $tableData=null,$tableDataPic=null,$tableDataFile=null;
	
	public function isView(){return $this->view->isDat();}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadView()
	{
		$this->view=new WebPageView();
		$this->view->setAuthMode(0);
		$this->view->doLoad();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseView()
	{
		if(!$this->view->isDat()){
			go('./');
			return;
		}
		$this->id=$this->view->getVar('id');
		//$this->classid=$this->view->getVar('classid');
		$this->classid=$this->view->getDataInt('classid');
		if($this->classid>0){
			$this->cfg->clas->setID($this->classid);
			$this->cfg->setTitle('class',$this->cfg->clas->getName($this->classid),$this->cfg->clas->getValue($this->classid,'linkurl'));
			$this->_var['classids']=$this->cfg->clas->getIDS($this->classid);
			if($this->_var['classids']){
				$this->treeClassFather=ModelClassExtend::toTree($this->cfg->clas->getTable(),$this->cfg->clas->getValue($this->classid,'fatherid'));
				if($this->treeClassFather->getCount()>0){
					$this->cfg->setTitle('portal',$this->treeClassFather->getItem('name'),$this->treeClassFather->getItem('curl'));
				}
			}
		}
		$this->view->doParse();
		
		if(!$this->_var['field.topic']) $this->_var['field.topic']='topic';
		$this->cfg->setTitle($this->view->getDatas($this->_var['field.topic']));
		//$this->cfg->setTitle('sub',$this->view->getDatas($this->_var['field.topic']));
		
		$this->view->doParsePic();
		
		$this->doParseViewData();
		$this->doParseViewExtend();
	}
	
	public function doParseViewData()
	{
		$this->tableData=self::getDataTable($this->cfg->getChannel(), $this->id);
		$this->tableDataPic=newTable();$this->tableDataPic->setFields($this->tableData->getFields());
		$this->tableDataAffix=newTable();$this->tableDataAffix->setFields($this->tableData->getFields());
		$this->tableData->doBegin();
		while($this->tableData->isNext()){
			switch($this->tableData->getItemValue('sort')){
				case 'pic':		$this->tableDataPic->addItem($this->tableData->getItemTree());break;
				case 'affix':		$this->tableDataAffix->addItem($this->tableData->getItemTree());break;
			}
		}
		self::doDataParse($this->tableData);
		self::doDataParse($this->tableDataPic);
		self::doDataParse($this->tableDataAffix);
	}
	
	public function doParseViewExtend()
	{
		
	}
	
	
	########################################
	########################################
	public static function getDataTable($channel, $rootid)
	{
		global $cfg;
		$reTable=newTable();
		$reTable->setFields('id,topic,total_view');
		
		$ChannelPreTree = CommonChannelExtend::getPreTree($channel);
		$TableName = $ChannelPreTree->getItem('data:table.name');
		if($TableName){
			$TablePx = $ChannelPreTree->getItem('data:table.px');
			$sqlRelate=$cfg->chn->getSQLStruct('data.relate');
			if(!$sqlRelate) $sqlRelate='rootid={$rootid}';
			//debugx($sqlRelate);
			$sqlRelate=rd($sqlRelate,'rootid',$rootid);
			$sql='SELECT * From '.$TableName.' where '.$sqlRelate.' order by '.$TablePx.'id asc';
			$reTable=DB::queryTable($sql);
			$reTable->doFilter($TablePx,'');
		}
		return $reTable;
	}
	
	public static function doDataParse(&$tableData)
	{
		$tableData->doAppendFields('sn,oe');
		$tableData->doBegin();
		while($tableData->isNext()){
			$t=$tableData->getI();
			$tableData->setItemValue('sn',$t);
			$tableData->setItemValue('oe',((($t-1)%2)+1));
			
			$tableData->setItemValue('pic',CommonTheme::toUploadURL($tableData->getItemValue('pic')));
			$tableData->setItemValue('url',CommonTheme::toUploadURL($tableData->getItemValue('url')));
		}
	}
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheView()
	{
		$this->theme->output=$this->view->toDTMLCache($this->theme->output,'cpo.view');
		$this->theme->doCacheFilterLoop('data','cpo.tableData');
		$this->theme->doCacheFilterLoop('data.pic','cpo.tableDataPic');
		$this->theme->doCacheFilterLoop('data.affix','cpo.tableDataAffix');
	}
	
}
?>