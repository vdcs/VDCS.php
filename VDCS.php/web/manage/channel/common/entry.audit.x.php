<?
class PagePortal extends PortalCommonBaseX
{
	
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