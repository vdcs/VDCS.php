<?
class PagePortal extends PortalCommonBaseX
{
	
	protected function parseViewi()
	{
		$uuid=queryi('uid');
		$staffid=DB::queryInt('select amid from db_account where uid='.$uuid);
		$staffTree=newTree();
		$staffTree=EcStaff::getTree($staffid);
		if($staffTree->getCount()<1){
			$this->setStatus('failed');
			$this->setMessage('用户不存在');
			return;
		}
		$this->addVarTree($staffTree,'staffinfo.');
		$this->setSucceed();
	}
	
}
?>