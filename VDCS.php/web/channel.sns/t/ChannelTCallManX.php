<?
class ChannelTCallManX extends ChannelTBaseX
{
	
	public function parseMan()
	{
		$ids=querys('uid',1);
		//debugx($ids);
		
		$tableData=newTable();
		$tableData->setFields('id,message,time,linkurl,uid,ulink');
		$ida=toSplit($ids,',');
		foreach($ida as $id){
			$sql='select * from db_mlog where uuid='.$id.' order by tim desc limit 0,1';
			$treeDat=DB::queryTree($sql);
			$treeItem=newTree();
			if($treeDat->getCount()>0){
				$treeItem->addItem('id',$treeDat->getItem('id'));
				//$treeItem->addItem('message',TCode::toTransContent($treeDat->getItem('message')));
				$treeItem->addItem('message',$treeDat->getItem('summarys'));
				$treeItem->addItem('time',datei($treeDat->getItem('tim')));
				$treeItem->addItem('linkurl','/u/'.$treeDat->getItem('uuid'));
				$treeItem->addItem('uid',$treeDat->getItem('uuid'));
			}
			//debugTree($treeItem);
			$tableData->addItem($treeItem);
		}
		UaExtend::appendInfo($tableData,['relateid'=>'uid','fieldx'=>'sign,avatar']);
		$tableData->begin();
		while($tableData->next()){
			$uid=$tableData->getItemValueInt('uid');
			if($uid>0){
				$tableData->setItemValue('ulink','/u/'.$uid);
				if(len($tableData->getItemValue('uavatar'))<1){
					$tableData->setItemValue('uavatar','/avatar/'.$uid.'_m.gif');
				}

			}
		}
		//debugTable($tableData);

		$this->setTable($tableData);
		$this->setSucceed();
	}
	
}
?>