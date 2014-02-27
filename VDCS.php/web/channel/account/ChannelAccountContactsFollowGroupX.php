<?
class ChannelAccountContactsFollowGroupX extends ChannelAccountBaseX
{
	
	public function parseCreateGroup()
	{
		$name=queryx('groupname');
		$summary=queryx('summary');
		$insertid=ContactsGroup::createGroup($this->ua,$name,$summary);
		switch($insertid){
			case 0:
				$this->setStatus('failed');
				break;
			default:
				$this->setStatus('succeed');
			 	$this->addVar('insertid',$insertid);
				break;
		}
	}
	
	public function parseGetfollowuid()
	{
		$uid=queryi('uid');
		if(!$uid) $uid=$this->ua->id;
		$this->addVar('follow_uids',ContactsFollow::getFollowUids($uid));
	}
	
	public function parseDelFromGroup()
	{
		$rootid=queryi('rootid');
		$groupid=queryi('groupid');
		$_status=ContactsFollow::delFromGroups($rootid,$groupid);
		$groupnames=ContactsFollow::getGroupTableByFollowid($rootid);
		$this->addVar('groupsname',$groupnames);
		switch($_status){
			case 1:
				$this->setStatus('succeed');
				break;
			case 2:
				$this->setStatus('already');
				break;
			default:
				$this->setStatus('failed');
				break;
		}
	}
	
	public function parseAddToGroup()
	{
		$rootid=queryi('rootid');
		$groupid=queryi('groupid');
		$_status=ContactsFollow::createNewGroups($rootid,$groupid);
		$groupnames=ContactsFollow::getGroupTableByFollowid($rootid);
		$this->addVar('groupsname',$groupnames);
		switch($_status){
			case 1:
				$this->setStatus('succeed');
				break;
			case 2:
				$this->setStatus('already');
				break;
			default:
				$this->setStatus('failed');
				break;
		}
	}
	
	//获取某用户的分组情况
	public function parseGetGroups()
	{
		$rootid=queryi('rootid');
		//debugx($rootid);
		$groupnames=ContactsFollow::getGroupTableByFollowid($rootid);
		if($groupnames) $this->setStatus('succeed');
		$this->addVar('groupsname',$groupnames);
	}
	
	public function parseDelGroup()
	{
		$id=queryi('groupid');
		$rootids=ContactsGroup::delGroup($id);
		foreach(explode(',',$rootids) as $rootid){
			ContactsFollow::isGroupMember($rootid);
		}
	}
	
	
	
}
?>