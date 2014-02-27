<?
class TMlogPost extends TMlog
{
	
	/*
	########################################
	########################################
	*/
	
	public static function del($ua,$id)
	{
		$_status=0;
		$_status=self::isCheck($ua,$id,$treeRS);
		if(!$_status) return $_status;
		
		$tagids=$treeRS->vi('tagids');
		$type=$treeRS->vi('type');
		//dcsLog('tagids',$tagids);
		
		$sqlQuery=self::FieldID.'='.$id;
		$sqlQueryRoot='rootid='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		/*
		if($type==5){
			TGamecard::addExpItem($ua->id,'gc_hpost',1);//减少一条发布
		}else{
			TGamecard::addExpItem($ua->id,'gc_lpost',1);//减少一条发布
		}
		*/
		if($isexec){
			$sqlQuery='id='.$id;
			$sql=DB::sqlDelete('db_mcontent',$sqlQuery);
			//debugx($sql);
			DB::exec($sql);
			
			$sql=DB::sqlDelete('db_mcomment',$sqlQueryRoot);
			//debugx($sql);
			DB::exec($sql);
			
			$sql=DB::sqlDelete('db_mat',$sqlQueryRoot);
			//debugx($sql);
			DB::exec($sql);
			
			$sql=DB::sqlDelete('dbu_notice',$sqlQueryRoot);
			//debugx($sql);
			//DB::exec($sql);
			
			/*
			//改变标签的文章数量
			if($tagids){
				TGamecard::addExpItem($ua->id,'gc_tag',1);//减少一条贴标签
				$tagidsAr=explode(',',$tagids);
				foreach($tagidsAr as $value){
					self::delmtags($id,$value);//删除db_mtags中的数据
				}
			}
			*/
			$_status=1;
		}
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function edit($ua,$id,$tData)
	{
		$_status=0;
		$type=$tData->vi('type');
		
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
				//$summarys=utilCode::toHTMLTag($summary);	//去除summary的格式
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
		//$medias=TCode::toTransPics($content);
		$medias=TCode::toTransMedia($tData,$content);
		$tData->addItem('medias',$medias);
		$tData->addItem('ispic',0);
		if($tData->isItem('pic')) $tData->addItem('ispic',1);
		
		$sqlTerm=self::FieldID.'='.$id;
		//主数据 保存
		$FieldsAdd='message,summarys,more,tagids,source,medias,pic,ispic,tim_up';
		$sql=DB::sqlUpdate(self::TableName,$FieldsAdd,$tData,$sqlTerm);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=$id;
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			//内容 保存
			if(len($content)>0){
				$tData->addItem('id',$rootid);
				$tData->addItem('sp_code',1);
				$FieldsAdd='summary,content,tim_up';
				$sql=DB::sqlUpdate(self::ContentTableName,$FieldsAdd,$tData,$sqlTerm);
				$isexec=DB::exec($sql);
			}
			$_status=1;
			//标签清理
			self::tagClear($ua,$rootid,'0');
			
			//标签 保存
			$tagn=0;
			$tagsAry=array();
			$_tagids=$tData->getItem('tagids');
			$tagidsAr=toSplit($_tagids,',');
			foreach($tagidsAr as $value){
				$tagid=toi($value);
				if($tagid>0 && $tagn<5 && !in_array($tagid,$tagsAry)){
					self::tagAdd($rootid,$tagsort,$tagid);
					array_push($tagsAry,$tagid);
					$tagn++;
				}
			}
			
			//标签 更新ID集
			$tagids=implode(',',$tagsAry);
			$sql='update '.self::TableName.' set tagids='.DB::q($tagids,1).' where '.self::FieldID.'='.$rootid;
			DB::exec($sql);
			
			//@
			$tableAt=TMlogAt::getByRID($rootid);
			$atouids=$tableAt->getValues('uuid');
			
			//@ 处理
			TMlogAt::save($ua,$rootid,0,$tData->getItem('message'),$atouids);
			$atuids=TMlogAt::save($ua,$rootid,0,$content,$atouids);
			
			//@ 清理
			/*
			if($atouids || $atuids){
				if(!$atouids) $atouids='0';
				if(!$atuids) $atuids='0';
				TMlogPost::atClear($ua,$rootid,$atouids.','.$atuids);
			}
			*/
			TMlogPost::atClear($ua,$rootid,$atuids);
			
			//标签清理
			//self::tagClear($ua,$rootid,$tagids);
		}
		
		unset($content);
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function send($ua,$tData)
	{
		$_status=0;
		
		$type=$tData->vi('type');
		
		//$tData->addItem('fromid',$fromid);
		//if($appid<1) $appid=1;
		//$tData->addItem('appid',$appid);
		//$tData->addItem('tagid',0);
		//$tData->addItem('relayid',0);
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		//$tData->addItem('uuno',$ua->getNo());
		$tData->addItem('uuname',$ua->getNames());
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$more=0;
		if($type==5){
			$content=rtrim($tData->getItem('content'));
			//$content=TAt::filterID($content);
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
			//TGamecard::addExpItem($ua->id,'gc_hpost');
		}
		else{
			$message=trim($tData->getItem('message'));
			$message=TAt::filterID($message);
			$tData->addItem('message',$message);
			$summarys=TCode::toTransMessage($message,$more);//将空格转为nbsp
			$summarys=TCode::toTransContent($summarys);
			//debug($summarys);
			$tData->addItem('summarys',$summarys);
			$type=0;
			$tData->addItem('type',$type);
			$content=$tData->getItem('content');
			//TGamecard::addExpItem($ua->id,'gc_lpost');
		}
		$tData->addItem('more',$more);
		
		//处理 pic
		//$medias=TCode::toTransPics($content);
		$medias=TCode::toTransMedia($tData,$content);
		$tData->addItem('medias',$medias);
		$tData->addItem('ispic',0);
		if($tData->isItem('pic')) $tData->addItem('ispic',1);
		
		//主数据 保存
		$FieldsAdd='appid,tagid,relayid,talkid,uurc,uuid,uuno,uuname,type,message,summarys,more,tagids,source,medias,pic,ispic,fromid,status,tim,tim_up';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=DB::insertid();
		$tData->addItem('id',$rootid);
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			//内容 保存
			if($content){
				$tData->addItem('sp_code',1);
				$FieldsAdd='id,summary,content,sp_code,status,tim,tim_up';
				$sql=DB::sqlInsert(self::ContentTableName,$FieldsAdd,$tData);
				$isexec=DB::exec($sql);
			}
			$_status=1;
			//标签 保存
			$tagn=0;
			$tagsAry=array();
			$_tagids=$tData->getItem('tagids');
			$tagidsAr=toSplit($_tagids,',');
			//if(count($tagidsAr)>0) TGamecard::addExpItem($ua->id,'gc_tag');//贴标签
			foreach($tagidsAr as $value){
				$tagid=toi($value);
				if($tagid>0 && $tagn<5 && !in_array($tagid,$tagsAry)){
					self::tagAdd($rootid,$tagsort,$tagid);
					array_push($tagsAry,$tagid);
					$tagn++;
				}
			}
			
			
			//标签 更新ID集
			$tagids=implode(',',$tagsAry);
			$sql='update '.self::TableName.' set tagids='.DB::q($tagids,1).' where '.self::FieldID.'='.$rootid;
			DB::exec($sql);
			
			//@ 处理
			TMlogAt::save($ua,$rootid,0,$tData->getItem('message'));
			TMlogAt::save($ua,$rootid,0,$content);
			//TMlogAt::save($ua,$rootid,0,$contents);
			
			//$sql='update '.$ua->TableName.' set u_total_post=u_total_post+1 where '.$ua->FieldID.'='.$ua->id;
			//DB::exec($sql);
			TNoticeAction::sets($ua,'{tpx}total_post={tpx}total_post+1',$ua->id);
			
			//开放平台 同步
			$sync_open=$tData->getItem('sync_open');
			$sync_weibo=$tData->getItem('sync_weibo');
			if($sync_open=='yes'){//同步到腾讯微博
				TSync::post($ua,$tData);
			}
			else if($sync_weibo=='yes'){	//同步到微博
				TSync::postWeibo($ua,$tData);
			}
		}
		
		unset($content);
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function delmtags($rootid,$tagid)
	{
		$sql='delete from db_mtags where rootid='.$rootid.' and tagid='.$tagid.'';
		DB::query($sql);//将db_mtags中的数据删除
		//dcsLog('db_mtags',$sql);
		$sql='update db_tags set total_post=total_post-1 where tagid='.$tagid.'';
		DB::query($sql);//将标签中文章数改变
		//dcsLog('db_tags',$sql);
	}
	
	public static function tagAdd($rootid,$tagsort,$tagid)
	{
		$_status=0;
		if($tagid>0){
			$tData=newTree();
			$tData->addItem('rootid',$rootid);
			$tData->addItem('tagsort',$tagsort);
			$tData->addItem('tagid',$tagid);
			$tData->addItem('status',1);
			$tData->addItem('tim',DCS::timer());
			$FieldsAdd='rootid,tagsort,tagid,status,tim';
			$sql=DB::sqlInsert(self::TagsTableName,$FieldsAdd,$tData);
			$isexec=DB::exec($sql);
			if($isexec) $_status=1;
			
			//添加到db_tags里面的total_post中
			$sql='update db_tags set total_post=total_post+1 where tagid='.$tagid.'';
			DB::query($sql);
		}
		return $_status;
	}
	
	public static function tagClear($ua,$rootid,$tagids)
	{
		$_status=0;
		if(len($tagids)>0){
			$sqlTerm='rootid='.$rootid.' and tagid not in ('.$tagids.')';
			$sql=DB::sqlDelete(self::TagsTableName,$sqlTerm);
			$isexec=DB::exec($sql);
			if($isexec) $_status=1;
		}
		return $_status;
	}
	
	public static function atClear($ua,$rootid,$uids)
	{
		$_status=0;
		if(len($uids)>0){
			$sqlTerm='rootid='.$rootid.' and uuid not in ('.$uids.')';
			$sql=DB::sqlDelete(self::AtTableName,$sqlTerm);
			debugx($sql);
			$isexec=DB::exec($sql);
			if($isexec) $_status=1;
		}
		return $_status;
	}
	
}
?>