<?php
class UcaTransaction{
	const TableName			= 'dbu_transaction';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'module,rootid,uuid,uurc,money,balance,type,payment,summary,explain,tim';
	
	public static function getTree($id){
		$treeRS=newTree();
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null){
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}else if($treeRS->getItemInt('uuid')!=$ua->id){
			$_status=6;//没权限
		}
		return $_status;
	}
	
	//添加
	public static function add($ua,$tData){
		//$type=$tData->getItem('record_type');
		//if($type) $tData->addItem('type',$type);
		//$account_balance=0;//账户余额，先默认为0
		//$tData->addItem('account_balance',$account_balance);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('tim',DCS::timer());
		//$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		return $isexec;
	}
	//查询
	public static function query($sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		self::doFilterData($tableData);
		return $tableData;
	}
	
	//查询
	public static function querier($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		$params['query']=DB::sqla($params['query'],'uuid='.$ua->id);//$params['query'] 额外条件
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterData($tableData);
		return $tableData;
	}
	
	protected function doFilterData(&$tableData)
	{
		$tableData->doAppendFields('tim.name,type.name');
 		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('tim.name',datei('Y-m-d H:i:s',$tableData->getItemValue('tim')));
			$type=$tableData->getItemValue('type');
			switch($type){
				case 1:
					$tableData->setItemValue('type.name','收入');
					break;
				default:
					$tableData->setItemValue('type.name','支付');
					break;
			}
		}
	}
	
	//删除
	public static function delete($ua,$id)
	{
		$_status=self::isCheck($ua,$id,$treeRS);
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	
}

?>