<?
trait PortalUaRefAuthCertif
{
	
	public function refLoad()
	{
		$this->theme->setSubdir('ua');
		//$this->theme->setPage('auth.certif');
		//$this->theme->setPage($this->_p_);
		//$this->theme->setModule($this->_m_);
	}
	
	protected function doListFilter(&$tableData)
	{
		$relateid=$this->getConfig('table.field.relateid');
		if(!$relateid) $relateid=$this->FieldID;
		$relateids=$tableData->getValues($relateid);
		if($relateids){
			$_tablename=$this->getConfig('basic:table.name');
			$_fieldid=$this->getConfig('basic:table.field.id');
			$_fields=$this->getConfig('basic:list.table.fields');
			if(!$_fieldid) $_fieldid=$relateid;
			if($_tablename && $_fields) $tableData=CommonExtend::toExtendTable($tableData,$relateid.'='.$_fieldid,'',$_tablename,$_fieldid.','.$_fields,$_fieldid.' in ('.$relateids.')');
			$_tablename=$this->getConfig('info:table.name');
			$_fieldid=$this->getConfig('info:table.field.id');
			$_fields=$this->getConfig('info:list.table.fields');
			if(!$_fieldid) $_fieldid=$relateid;
			if($_tablename && $_fields) $tableData=CommonExtend::toExtendTable($tableData,$relateid.'='.$_fieldid,'',$_tablename,$_fieldid.','.$_fields,$_fieldid.' in ('.$relateids.')');
		}
		//debugTable($tableData);
	}
	
	
}