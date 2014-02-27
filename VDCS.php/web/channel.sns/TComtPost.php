<?
class TComtPost extends TComt
{
	
	/*
	########################################
	########################################
	*/
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=0;
		
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1) $_status=5;
		if($treeRS->getItemInt('uuid')!=$ua->id) $_status=6;
		
		$_status=1;
		return $_status;
	}
	
	public static function delete($ua,$id)
	{
		$_status=0;
		$_status=self::isCheck($ua,$id,$treeRS);
		if(!$_status) return $_status;
		
		$sqlQuery='id='.$id;
		$sqlQueryRoot='rootid='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		TGamecard::addExpItem($ua->id,'gc_sendcomment',1);
		if($isexec){
			$sql=DB::sqlDelete('db_mat',$sqlQueryRoot);
			//debugx($sql);
			//DB::exec($sql);
			
			$sql=DB::sqlDelete('dbu_notice',$sqlQueryRoot);
			//debugx($sql);
			//DB::exec($sql);
			
			$_status=1;
		}
		$_status=1;
		
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function send($ua,$rootid,$tData)
	{
		$_status=0;
		
		$treeBase=TMlog::getTree($rootid);
		if($treeBase->getCount()<1){
			return $_status;
		}
		
		$relayid_=$treeBase->getItemInt('relayid');
		$isrelay=false;
		if($tData->getItem('isreply')=='yes'){
			$isreply=true;
			$relayidr=$rootid;
			$relayid=$rootid;
			if($relayid_>0) $relayid=$relayid_;
			$tData->addItem('relayid',$relayid);
		}
		
		$tData->addItem('rootid',$rootid);
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uuno',$ua->getNo());
		$tData->addItem('uuname',$ua->getNames());
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',0);			// 1=正常 2=删除
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$message=$tData->getItem('message');
		$message=TAt::filterID($message);//关于at的相关处理
		if($isreply){
			//$message
		}
		$tData->addItem('message',$message);
		$summarys=$message;
		$summarys=utilCode::toHTMLTag($summarys,1);
		$summarys=TCode::toTransMessage($summarys);//将标签转义
		$summarys=TCode::toTransContent($summarys);//AT处理？
		$tData->addItem('summarys',$summarys);
		
		$urid=0;$urname='';
		$replyuid=$tData->getItemInt('replyuid');
		if($replyuid>0){
			TAt::values($replyuid,$urid,$urname);//获取回复评论的用户id和name
		}
		$tData->addItem('urid',$urid);
		$tData->addItem('urname',$urname);
		$FieldsAdd='rootid,uurc,uuid,uuno,uuname,urid,urname,message,summarys,fromid,status,tim,tim_up';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		TGamecard::addExpItem($ua->id,'gc_sendcomment');//添加评论
		//debugx($sql);
		$isexec=DB::exec($sql);
		$talkid=DB::insertid();
		
		$ground=$talkid;$floor=0;
		
		$replyid=$tData->getItemInt('replyid');
		if($replyid){
			$treeReply=self::getTree($replyid);
			if($treeReply->getCount()>0){
				$ground=$treeReply->getItemInt('ground');
				$floor=$treeReply->getItemInt('floor')+1;
			}
			else{
				$replyid=0;
				//$tData->addItem('replyid',$replyid);
			}
		}
		
		if($isexec){
			$sql='update '.TMlog::TableName.' set total_comment=total_comment+1 where '.TMlog::FieldID.'='.$rootid;
			DB::exec($sql);
			//对被回复对象进行添加被评论
			$uuid2=DB::queryInt('select uuid from db_mlog where id='.$rootid.'');
			TGamecard::addExpItem($uuid2,'gc_commentby');				//添加被评论
			
			//@ 处理
			if($replyuid>0){
				$atmsg=mb_substr($message,4,strlen($message),'utf-8');		//如果是回复，回复对象不进行@
			}else{
				$atmsg=$message;	
			}
			TMlogAt::save($ua,$rootid,$talkid,$atmsg);
			
			$rootuid=$treeBase->getItemInt('uuid');
			//dcsLog('rootuid',$rootuid);
			if($rootuid!=$ua->id){				//即时提示评论消息
				TNoticeAction::countComment($ua,[
					'rootid'=>$rootid,
					'talkid'=>$talkid,
					'rootuid'=>$rootuid,
				'-'=>'-']);
			}
			
			$replyuid=$tData->getItemInt('replyuid');
			
			if($replyuid>0 && $replyuid!=$rootuid){		//如果有人对评论进行了回复，则提示
				TNoticeAction::countComment($ua,[
					'rootid'=>$rootid,
					'talkid'=>$talkid,
					'rootuid'=>$replyuid,
				'-'=>'-']);
			}
			
			if($replyuid>0 && $replyuid!=$rootuid){		//如果有人对评论进行了回复，则提示,貌似前台没有显示
				TNoticeAction::countReply($ua,[
					'rootid'=>$rootid,
					'talkid'=>$talkid,
					'replyuid'=>$replyuid,
				'-'=>'-']);
			}
			
			if($isreply){					//转发
				$tData->addItem('relayid',$relayid);
				$tData->addItem('talkid',$talkid);
				TMlogPost::send($ua,$tData);
				
				//转发也会有at提示
				$atuid=DB::queryInt('select uuid from db_mlog where id='.$relayid.'');
				TNoticeAction::countAt($ua,[
					'atuid'=>$atuid,
				'-'=>'-']);
				//转发at提示结束
			}
			$_status=1;
			
			$sql=DB::sqlUpdate(self::TableName,'','ground='.$ground.',floor='.$floor.',replyid='.$replyid.',status=1','id='.$talkid);
			DB::exec($sql);
		}
		
		unset($message,$summarys);
		return $_status;
	}
	
}
