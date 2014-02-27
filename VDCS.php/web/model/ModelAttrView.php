<?
class ModelAttrView extends ModelAttr
{
	const DataPX		= 'attr.';
	
	
	public function doParse()
	{
		$this->doLoad();
		$this->loadData();
		//debugTable($this->tableData);
		if(!$this->isData) return;
		//$this->doParseData();
	}
	public function doParseData()
	{
		$this->treeDat=newTree();
		$this->treeData->doBegin();
		for($t=0;$t<$this->treeData->getCount();$t++){
			$this->treeDat->addItem(self::DataPX.$this->treeData->getItemKey(),$this->treeData->getItemValue());
			$this->treeData->doMove();
		}
		//debugTree($this->treeDat);
	}
	public function getDat($k){return $this->treeDat->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCache($re,$vo='')
	{
		if(!$vo) $vo=self::KEY.'.';
		if(right($vo,1)!='.') $vo.='.';
		//####################
		$re=CommonTheme::toCacheFilterLoop($re,self::KEY.'s',$vo.'tableData');
		$re=CommonTheme::toCacheFilterLoop($re,self::KEY.'var',$vo.'tableDataVar');
		$re=CommonTheme::toCacheFilterLoop($re,self::KEY.'sel',$vo.'tableDataSel');
		//####################
		return $re;
	}
}
?>