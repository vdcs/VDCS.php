<?
class XlogPost extends Xlog
{
	
	/*
	########################################
	########################################
	*/
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=0;
		
		$treeRS=self::getTree($id);
		if($treeRS->getItemInt('uuid')!=$ua->id) return 6;
		if($treeRS->getCount()<1) return 5;
		
		$_status=1;
		return $_status;
	}
	
	public static function delete($ua,$id)
	{
		$_status=0;
		$_status=self::isCheck($ua,$id,$treeRS);
		if(!$_status) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sqlQueryRoot='rootid='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		
		if($isexec){
			$sqlQuery='id='.$id;
			/*
			$sql=DB::sqlDelete('db_mcontent',$sqlQuery);
			//debugx($sql);
			DB::exec($sql);
			
			$sql=DB::sqlDelete('dbu_notice',$sqlQueryRoot);
			//debugx($sql);
			DB::exec($sql);
			*/
			$_status=1;
		}
		$_status=1;
		
		return $_status;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function edit($ua,$id,$tData)
	{
		$_status=0;
		
		$type=$tData->getItemInt('type');
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',1);
		//$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$content=rtrim($tData->getItem('content'));
		$content=TAt::filterID($content);
		$tData->addItem('content',$content);
		$contents=TCode::toTransContent($content);
		$tData->addItem('contents',$contents);
		$tData->addItem('summarys',$contents);
		
		$sqlTerm=self::FieldID.'='.$id;
		//主数据 保存
		$FieldsAdd='sorts,types,topic,message,summarys,content,tim_up';
		$sql=DB::sqlUpdate(self::TableName,$FieldsAdd,$tData,$sqlTerm);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=$id;
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			$_status=1;
			
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
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uuno',$ua->getNo());
		$tData->addItem('uuname',$ua->getNames());
		
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$content=rtrim($tData->getItem('content'));
		$content=TAt::filterID($content);
		$tData->addItem('content',$content);
		$contents=TCode::toTransContent($content);
		$tData->addItem('contents',$contents);
		$tData->addItem('summarys',$contents);
		
		//主数据 保存
		$FieldsAdd='uurc,uuid,uuno,uuname,sorts,types,topic,message,summarys,content,status,tim,tim_up';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$rootid=DB::insertid();
		$tData->addItem('id',$rootid);
		
		//dcsLog('rootid',$rootid);
		if($isexec){
			$_status=1;
			
		}
		
		unset($content);
		return $_status;
	}
	
}
?>