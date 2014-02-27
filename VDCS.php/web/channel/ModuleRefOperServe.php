<?
trait ModuleRefOperServe
{
	
	public function doParseHandler()
	{
		$this->setStatus('init');
		switch($ctl->action){
			case 'del':
				$this->doParseDel();
				break;
			case 'restore':
				$this->doParseRestore();
				break;
			case 'delete':
				$this->doParseDelete();
				break;
		}
		
	}
	
	public function doParseDel()
	{
		$this->id=$ctl->id;
		$this->addVar('id',$this->id);
		$sqlquery=DB::sqlAppend($this->_var['query'],$this->FieldID.'='.$this->id);
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlquery,'',1);
		$ctl->treeData=DB::queryTree($sql);
		if($ctl->treeData->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		$treeSet=newTree();
		$treeSet->addItem($this->TablePX.'status','5');
		$treeSet->addItem($this->TablePX.'tim1',DCS::timer());
		$sql=DB::sqlUpdate($this->TableName,$treeSet->getFields(),$treeSet,$sqlquery);
		DB::exec($sql);
		$this->setStatus('succeed');
	}
	
	public function doParseRestore()
	{
		$this->id=$ctl->id;
		$this->addVar('id',$this->id);
		$sqlquery=DB::sqlAppend($this->_var['query'],$this->FieldID.'='.$this->id);
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlquery,'',1);
		$ctl->treeData=DB::queryTree($sql);
		if($ctl->treeData->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		$treeSet=newTree();
		$treeSet->addItem($this->TablePX.'status','1');
		$treeSet->addItem($this->TablePX.'tim1',DCS::timer());
		$sql=DB::sqlUpdate($this->TableName,$treeSet->getFields(),$treeSet,$sqlquery);
		DB::exec($sql);
		$this->setStatus('succeed');
	}
	
	public function doParseDelete()
	{
		$this->id=$ctl->id;
		$this->addVar('id',$this->id);
		$sqlquery=DB::sqlAppend($this->_var['query'],$this->FieldID.'='.$this->id);
		$sql=DB::sqlSelect($this->TableName,'count','*',$sqlquery,'');
		if(DB::queryInt($sql)<1){
			$this->setStatus('nodata');
			return;
		}
		$sql=DB::sqlDelete($this->TableName,$sqlquery);
		DB::exec($sql);
		$this->setStatus('succeed');
	}
	
}
?>