<?
class PageViewQuestion extends PageView
{
	public $tableAnswer,$tableAnswerBest;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableAnswer,$this->tableAnswerBest);
	}
	
	public function isSolved(){return ($this->getDataInt('state')==1);}
	public function isTimed(){return ($this->getDataInt('_timed_status')==0);}
	public function doParseValid()
	{
		$this->doParseTimed();
		$this->addData('is_solved',$this->isSolved()?'true':'false');
		$this->addData('is_timed',$this->isTimed()?'true':'false');
	}
	public function doParseTimed()
	{
		$tim=$this->getDataInt('tim');
		$timed_status=$this->getDataInt('timed_status');
		$timed_day=$this->getDataInt('timed_day');
		$timed_expire=$this->getDataInt('timed_expire');
		$_timed_expire=0;
		if($timed_expire>0){
			$_timed_expire=$timed_expire;
		}
		else{
			if($timed_day>0) $_timed_expire=VDCSTime::toDateAdd($tim,'d',$timed_day);
		}
		$timNow=DCS::timer();
		//debugx($tim_expire.','.$tim.','.$timNow);
		$_timed_status=($_timed_expire>$timNow)?'0':'1';
		$this->addData('_timed_status',$_timed_status);
		$this->addData('_timed_expire',$_timed_expire);
	}
	public function doParseAnswer()
	{
		$this->rootid=$this->id;
		
		$_table=$cfg->vp('answer:table.name');
		$_url=$cfg->toLinkURL('view.page');
		$_url=rd($_url,'id',$this->id);
		$sqlQuery='rootid='.$this->rootid.' and a_status>0 and a_state=0';
		$sqlOrder='a_tim asc';
		$this->pa=new libPaging();
		$this->pa->setPageNum(5);
		$this->pa->setListNum(4);
		$this->pa->setConfig('url',$_url);
		$this->pa->setDB('table',$_table);
		$this->pa->setDB('id','a_id');
		$this->pa->setDB('field','*');
		$this->pa->setDB('query',$sqlQuery);
		$this->pa->setDB('order',$sqlOrder);
		$this->pa->setTotal(DB::queryInt($this->pa->getSQL('count')));
		$this->pa->doParse();
		
		$this->tableAnswer=$this->pa->toTable();
		$this->tableAnswer->doFilter($cfg->vp('answer:table.px'));
		$this->doAnswerFilter($this->tableAnswer);
		
		if($this->isSolved()){
			$sqlQuery='rootid='.$this->rootid.' and a_status>0 and a_state>0';
			$sqlOrder='a_state desc,a_tim asc';
			$sql=DB::sqlSelect($_table,'','*',$sqlQuery,$sqlOrder);
			$this->tableAnswerBest=DB::queryTable($sql);
			$this->tableAnswerBest->doFilter($cfg->vp('answer:table.px'));
			$this->doAnswerFilter($this->tableAnswerBest);
		}
	}
	public function doAnswerFilter(&$tableData)
	{
		UaExtend::appendInfo($tableData);
		$_pageBase=$this->pa->getPageBase();
		$tableData->doAppendFields('sn,_remark,_ip');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('sn',$tableData->getI()+$_pageBase);
			$tableData->setItemValue('_remark',VDCSCodes::toCodes($tableData->getItemValue('remark'),$tableData->getItemValueInt('sp_code')));
			$tableData->setItemValue('_ip',CommonExtend::toSafeIP($tableData->getItemValue('sp_ip')));
		}
	}
	
	public function getAnswerTree($rootid,$id)
	{
		if($rootid<0) $rootid=$this->id;
		$sqlQuery='rootid='.$rootid.' and a_id='.$id;
		$sql=DB::sqlSelect($cfg->vp('answer:table.name'),'','*',$sqlQuery,'',1);
		$reTree=DB::queryTree($sql);
		if($reTree->getCount()>0){
			$reTree->doFilter($cfg->vp('answer:table.px'));
			$reTree->addItem('_remark',VDCSCodes::toCodes($reTree->getItem('remark'),$reTree->getItemInt('sp_code')));
			$reTree->addItem('_ip',CommonExtend::toSafeIP($reTree->getItem('sp_ip')));
			UaExtend::appendTreeInfo($reTree);
		}
		return $reTree;
	}
	
}
?>