<?
class PagePortal extends PortalCommonBaseX
{
	
	protected function parseViewi()
	{
		$uuid=queryi('uid');
		$staffid=DB::queryInt('select amid from db_account where uid='.$uuid);
		$treeStaff=newTree();
		$treeStaff=EcStaff::getTree($staffid);
		if($treeStaff->getCount()<1){
			$this->setStatus('failed','Staff不存在');
			return;
		}
		$this->addVarTree($treeStaff,'staffinfo.');
		$this->setSucceed();
	}
	
}
