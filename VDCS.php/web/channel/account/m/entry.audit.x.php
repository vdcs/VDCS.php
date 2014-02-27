<?php
class PagePortal extends ManagePortalBaseX
{
	
	public function doLoad()
	{
		//$this->refLoad();
	}
	
	protected function doHandle()	//$mod=null,$put=1
	{
		parent::doHandle();
		if($this->isHandle()){
			$ids=$this->chn->getVar('handle.ids');
			switch($this->chn->getVar('handle.value')){
				case 'delete':
					if($this->attrm->is()) $this->attrm->doDataRemove($ids);
					break;
			}
		}
	}
	
	protected function parseViewi()
	{
		$rootid=queryi('rootid');
		$module=querys('module');
		$auTree=newTree();
		$sqlTerm=DB::sqla('module='.DB::q($module,1),'rootid='.DB::q($rootid,1));
		$auTree=ManageRecordAudit::view($sqlTerm);
		if($auTree->getItem('id')<1){
			$this->setStatus('failed');
			$this->setMessage('审核记录不存在');
			return;
		}
		$this->addVarTree($auTree,'rainfo.');
		$this->setSucceed();
	}
	
}
?>