<?
class TLike
{
	const LikeTableName		= 'db_mlike';
	
	
	public static function like($ua,$id)
	{
		$_status=2;
		if(!self::is($ua,$id)){
			$treeData=newTree();
			$treeData->addItem('rootid',$id);
			$treeData->addItem('dataid',$talkid);
			$treeData->addItem('uurc',$ua->rc);
			$treeData->addItem('uuid',$ua->id);		
			$treeData->addItem('status',1);
			$treeData->addItem('tim',DCS::timer());
			$treeData->addItem('sp_ip',DCS::ip());
			$treeData->addItem('sp_agent',DCS::agent());
			
			$FieldsAdd='rootid,dataid,uurc,uuid,status,tim,sp_ip,sp_agent';
			$sql=DB::sqlInsert(self::LikeTableName,$FieldsAdd,$treeData);
			$isexec=DB::exec($sql);
			if($isexec){
				$_status=1;
			}else{
				$_status=0;	
			}
		}
		return $_status;
	}
	
	//判断是否喜欢过
	public static function is($ua,$id)
	{
		$sql='select count(*) from '.self::LikeTableName.' where uuid='.$ua->id.' and rootid='.$id;
		return DB::queryInt($sql);
	}
	
	
	//******//
	public static function querier($rootid,&$p)
	{
		$total=self::getTotal($rootid);//获取总条数
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getList($rootid,$p->getPage(),$p->getListNum());
		self::doDataFilter($tableData);//增加用户信息
		return $tableData;
	}
	
	public static function getTotal($rootid)
	{
	
		$sql='select count(*) from '.self::LikeTableName.' where rootid='.$rootid.'';
		//$sql='select count(*) from '.self::LikeTableName.' where rootid=30';
			
		return DB::queryInt($sql);//获取到数据的总条数
	}
	
	public static function getList($rootid,$page,$row=self::RowDef)
	{
		$sql='select uuid from '.self::LikeTableName.' where rootid='.$rootid;
		//$sql='select uuid from '.self::LikeTableName.' where rootid=30';
				
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	public static function doDataFilter(&$tableData)
	{
		UaExtend::appendInfo($tableData,['fields'=>'sign']);
		return;
		//$tableData->doAppendFields('unames,usign');
		$tableData->doBegin();
		while($tableData->isNext()){
			//$tableData->setItemValue('unames',$tableUser->getTermsValue('uuid='.$tableData->getItemValue('uuid'),'names'));
		}
		//debugTable($tableData);
	}
	
	public static function getUserTableByID($ids)
	{
		global $ua;
		if(!$ids) return newTable();
		$sqlQuery=$ua->FieldID.' in ('.$ids.')';
		//debugx($ua->FieldID);
		$sql=DB::sqlQuery($ua->TableName,$ua->FieldID.','.$ua->TablePX.'names'.','.$ua->TablePX.'sign',$sqlQuery);
		//debugx($sql);
		$reTable=DB::queryTable($sql);
		//debugTable($reTable);
		return $reTable;
	}

}
