<?
trait ManageRefSearch
{
	
	public function loadSearch()
	{
		if(!$this->_isLoadSearch){
			$this->s=new libSearch();
			$this->s->doInit();
			$this->modulesc=$this->modules;//$this->modulesc????
			$this->s->setFieldID($this->getConfig($this->modulesc,'table.field.id'));
			$this->s->setFields($this->getConfig($this->modulesc,'search.fields'));
			//$this->s->setFieldsProperty($this->getConfig($this->modulesc,'search.fields.property'));
			$this->s->setRelations($this->getConfig($this->modulesc,'search.relations'));
			$this->s->setTimes($this->getConfig($this->modulesc,'search.times'));
			$this->s->setTermType($this->getConfig($this->modulesc,'search.term.type'));
			$this->s->setMode($this->getConfig($this->modulesc,'search.mode'));
			$this->_isLoadSearch=true;
		}
	}
	
	public function setSearchMode($s){$this->_var['SearchMode']=$s;}
	
	public function toSearchQuery($query='')
	{
		$searchQuery=$this->s->getQuery();
		if($searchQuery) $query=$this->s->toSQLAppend($query,'('.$this->s->getQuery().')');
		/*
		$re='';
		if(!$key) $key='uname';
		if($querydef) $re=$s->getQuery();
		if($s->field==$key){
			$re='uuid in (select uid from '.$uu[$rc]->TableName.' where '.r($s->getQuery(),$key,'name').')';
		}
		return $re;
		*/
		return $query;
	}

}
?>