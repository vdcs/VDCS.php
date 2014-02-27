<?
class ChannelCompanyList extends ChannelCompanyBase
{
	use ChannelRefList;
	
	public $tableAreas;
	public $treeSort,$tableSorts,$tableSortRoot,$tableSort1,$tableSort2,$tableSort3,$tableSort4,$tableSort5;
	public $classid,$specialid,$year,$mode;
	protected $_pagenum=5;
	protected $_listnum=20;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableAreas);
		unsetr($this->treeSort,$this->tableSorts);
		unsetr($this->tableSort1,$this->tableSort2,$this->tableSort3,$this->tableSort4,$this->tableSort5);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		$this->doLoadClass();
		
		$this->cfg->setTitle('');
		
		$this->_var['area']=queryx('area');
		$this->_var['s1']=queryx('s1');
		$this->_var['s2']=queryx('s2');
		$this->_var['s3']=queryx('s3');
		$this->_var['s4']=queryx('s4');
		$this->_var['s5']=queryx('s5');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		$this->doParseList();
		$this->doParseSort();
	}
	
	public function doParseListQuery()
	{
		if($this->_var['area'] || $this->_var['s1'] || $this->_var['s2'] || $this->_var['s3'] || $this->_var['s4'] || $this->_var['s5']){
			if($this->_var['area']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_area=\''.$this->_var['area'].'\'');
			if($this->_var['s1']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_sort1=\''.$this->_var['s1'].'\'');
			if($this->_var['s2']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_sort2=\''.$this->_var['s2'].'\'');
			if($this->_var['s3']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_sort3=\''.$this->_var['s3'].'\'');
			if($this->_var['s4']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_sort4=\''.$this->_var['s4'].'\'');
			if($this->_var['s5']) $this->_var['query']=DB::sqlLink($this->_var['query'],'c_sort5=\''.$this->_var['s5'].'\'');
			$this->_var['url']=utilCode::toURLAppend($this->_var['url'],'area='.$this->_var['area'].'&s1='.$this->_var['s1'].'&s2='.$this->_var['s2'].'&s3='.$this->_var['s3'].'&s4='.$this->_var['s4'].'&s5='.$this->_var['s5'].'');
		}
	}
	
	public function doParseSort()
	{
		$this->tableAreas=VDCSDTML::getConfigTable('common.channel/infos/data.area');
		$this->treeSort=newTree();
		$this->tableSorts=newTable();
		if($this->classid<1) return;
		
		$this->_isCache=true;
		$this->sort=new ModelSort();
		if(len($channel_)<1) $channel_=$this->cfg->getChannel();
		$this->sort->setChannel($channel_);
		$this->sort->setClassid($this->classid);
		$this->sort->setUse(true);
		$this->sort->setCache($this->_isCache);
		$this->sort->doInit();
		
		$this->tableSorts=$this->sort->getTable();
		$this->tableSortRoot=$this->sort->getTableRoot();
		$this->tableSortRoot->doBegin();
		while($this->tableSortRoot->isNext()){
			$n=$this->tableSortRoot->getI();
			$this->treeSort->addItem('id'.$n,$this->tableSortRoot->getItemValueInt('id'));
			$this->treeSort->addItem('name'.$n,$this->tableSortRoot->getItemValue('name'));
		}
		//debugTree($this->treeSort);
		if($this->treeSort->getItemInt('id1')>0) $this->tableSort1=$this->sort->getTableSub($this->treeSort->getItemInt('id1'));
		if($this->treeSort->getItemInt('id2')>0) $this->tableSort2=$this->sort->getTableSub($this->treeSort->getItemInt('id2'));
		if($this->treeSort->getItemInt('id3')>0) $this->tableSort3=$this->sort->getTableSub($this->treeSort->getItemInt('id3'));
		if($this->treeSort->getItemInt('id4')>0) $this->tableSort4=$this->sort->getTableSub($this->treeSort->getItemInt('id4'));
		if($this->treeSort->getItemInt('id5')>0) $this->tableSort5=$this->sort->getTableSub($this->treeSort->getItemInt('id5'));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		$this->doThemeCacheList();
		
		$this->theme->doCacheFilterLoop('areas','cpo.tableAreas');
		$this->theme->doCacheFilterTree('sort','cpo.treeSort');
		$this->theme->doCacheFilterLoop('sorts','cpo.tableSorts');
		$this->theme->doCacheFilterLoop('sortroot','cpo.tableSortRoot');
		$this->theme->doCacheFilterLoop('sort1','cpo.tableSort1');
		$this->theme->doCacheFilterLoop('sort2','cpo.tableSort2');
		$this->theme->doCacheFilterLoop('sort3','cpo.tableSort3');
		$this->theme->doCacheFilterLoop('sort4','cpo.tableSort4');
		$this->theme->doCacheFilterLoop('sort5','cpo.tableSort5');
	}
	
}
?>