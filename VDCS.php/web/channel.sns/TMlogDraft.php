<?
class TMlogDraft extends TMlog
{
	
	/*
	########################################
	########################################
	*/
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$sqlQuery='id ='.$id;
		$sql=DB::sqlSelect('db_mlog_draft','','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		if($treeRS->getCount()<1) $_status=5;
		if($treeRS->getItemInt('uuid')!=$ua->id) $_status=6;
		$_status=1;
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function edit($ua,$id,$tData)
	{
		$tableName='db_mlog_draft';
		$type=$tData->getItemInt('type');
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',1);
		//$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$more=0;
		if($type==5){
			$content=rtrim($tData->getItem('content'));
			$content=TAt::filterID($content);
			$tData->addItem('content',$content);
			$more=1;
			//处理 summary
			//if(len($tData->getItem('summary'))<1){
				$summary=TCode::toTransSummary($content,$more);
				$summarys=TCode::toTransContent($summary);
				//$summarys=utilCode::toHTMLTag($summary);//去除summary的格式
				$tData->addItem('summary',$summary);
				$tData->addItem('summarys',$summarys);
				$contents=TCode::toTransContent($content);
				$tData->addItem('contents',$contents);
			//}
		}
		else{
			$message=trim($tData->getItem('message'));
			$message=TAt::filterID($message);
			$tData->addItem('message',$message);
			$summary=TCode::toTransMessage($message,$more);
			$summarys=TCode::toTransContent($summary);
			$tData->addItem('summarys',$summarys);
			$type=0;
			$tData->addItem('type',$type);
		}
		$tData->addItem('more',$more);
		
		//处理 pic
		//$pics=TCode::toTransPics($content);
		$pics=TCode::toTransMedia($tData,$content);
		$tData->addItem('pics',$pics);
		$tData->addItem('ispic',0);
		if($tData->isItem('pic')) $tData->addItem('ispic',1);
		
		$sqlTerm=self::FieldID.'='.$id;
		//主数据 保存
		$FieldsAdd='message,summarys,content,more,tagids,source,pics,pic,ispic,tim_up';
		$sql=DB::sqlUpdate($tableName,$FieldsAdd,$tData,$sqlTerm);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=$id;
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			$_status=1;
			unset($content);
			return $rootid;
		}
	}
	
	public static function send($ua,$tData)
	{
		$tableName='db_mlog_draft';
		$_status=0;
		
		$type=$tData->getItemInt('type');
		
		//$tData->addItem('fromid',$fromid);
		//if($appid<1) $appid=1;
		//$tData->addItem('appid',$appid);
		//$tData->addItem('tagid',0);
		//$tData->addItem('relayid',0);
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uuno',$ua->getNo());
		$tData->addItem('uuname',$ua->getNames());
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$more=0;
		if($type==5){
			$content=rtrim($tData->getItem('content'));
			$content=TAt::filterID($content);
			$tData->addItem('content',$content);
			$more=1;
			//处理 summary
			if(len($tData->getItem('summary'))<1){
				$summary=TCode::toTransSummary($content,$more);
				$summarys=TCode::toTransContent($summary);
				$summarys=utilCode::toHTMLTag($summary);//去除summary的格式
				$tData->addItem('summary',$summary);
				$tData->addItem('summarys',$summarys);
				$contents=TCode::toTransContent($content);
				$tData->addItem('contents',$contents);
			}
		}
		else{
			$message=trim($tData->getItem('message'));
			$message=TAt::filterID($message);
			$tData->addItem('message',$message);
			$summarys=TCode::toTransMessage($message,$more);
			$summarys=TCode::toTransContent($summarys);
			//debug($summarys);
			$tData->addItem('summarys',$summarys);
			$type=0;
			$tData->addItem('type',$type);
		}
		$tData->addItem('more',$more);
		
		//处理 pic
		//$pics=TCode::toTransPics($content);
		$pics=TCode::toTransMedia($tData,$content);
		$tData->addItem('pics',$pics);
		$tData->addItem('ispic',0);
		if($tData->isItem('pic')) $tData->addItem('ispic',1);
		
		//主数据 保存
		$FieldsAdd='appid,tagid,relayid,talkid,uurc,uuid,uuno,uuname,type,message,summarys,content,more,tagids,source,pics,pic,ispic,fromid,status,tim,tim_up';
		$sql=DB::sqlInsert($tableName,$FieldsAdd,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=DB::insertid();
		$tData->addItem('id',$rootid);
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			$_status=1;
			unset($content);
			return $rootid;
		}
	}
	
}
?>