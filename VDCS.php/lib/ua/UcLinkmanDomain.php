<?
class UcLinkmanDomain
{
	const TableName			= 'dbu_linkman_domain';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		='uurc,uuid,type,names,email,name_en,firstname_en,lastname_en,area_country,area_province_en,area_city_en,address_en,name_cn,firstname_cn,lastname_cn,area_province_cn,area_city_cn,address_cn,phone,fax,postcode,status,tim,tim_up,explain';
	const TableEditFields		='name_en,firstname_en,lastname_en,area_country,area_province_en,area_city_en,address_en,name_cn,firstname_cn,lastname_cn,area_province_cn,area_city_cn,address_cn,postcode,phone,fax,email,status,tim_up,explain';

	public static function getTree($ua,$sqlTerm){
		//$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$treeRS=newTree();
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		self::decomPhone($treeRS);
		return $treeRS;
	}
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null,$sqlTerm=''){
		$_status=1;
		$treeRS=self::getTree($ua,$sqlTerm);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}else if($treeRS->getItemInt('uuid')!=$ua->id){
			$_status=6;//没权限
		}
		return $_status;
	}
	
	//检测是否存在
	public static function checkExists($ua,$type,$sqlTerm=''){
		$_isexists=0;
		$sqlQuery1=DB::sqla('uuid='.$ua->id,'type='.DB::q($type,1));
		$sqlQuery=DB::sqla($sqlTerm,$sqlQuery1);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$_isexists=DB::queryTree($sql)->getCount();
		return $_isexists;
	}
	
	//添加联系人
	public static function add($ua,$tData){
		$_status=0;
		self::comPhone($tData);
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem('id',$id);
		
		if($isexec) $_status=1;
	
		return $_status;
	}
	
	//编辑联系人
	public static function edit($ua,$id,$tData)
	{
		$_status=0;
		self::comPhone($tData);
		$tData->addItem('status',1);
		$tData->addItem('tim_up',DCS::timer());
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,self::TableEditFields,$tData,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//删除联系人
	public static function delete($ua,$id){
		$_status=self::isCheck($ua,$id,$treeRS);
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//查询联系人
	public static function query($ua,$sqlTerm='',$order='',$limit=0){
		$tableData=newTable();
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		return $tableData;
	
	}
	
	public static function decomPhone(&$tData)
	{
		$phone=$tData->getItem('phone');
		$arrPhone=utilString::toAry($phone,'-');
		$phoneregioncode=$arrPhone[0];
		if(!$phoneregioncode) $phoneregioncode='86';
		$tData->addItem('phoneregioncode','+'.$phoneregioncode);
		$phoneareacode=$arrPhone[1];
		if(!$phoneareacode) $phoneareacode='10';
		$tData->addItem('phoneareacode',$phoneareacode);
		$phonenumber=$arrPhone[2];
		$tData->addItem('phonenumber',$phonenumber);
		$phoneext=$arrPhone[3];
		$tData->addItem('phoneext',$phoneext);
	}
	
	public static function comPhone(&$tData) //compress
	{
		$phone='';
		$phoneregioncode=ltrim($tData->getItem('phoneregioncode'),'+');
		if(!$phoneregioncode) $phoneregioncode='86';
		if($phoneregioncode) $phone.=$phoneregioncode.'-';
		$phoneareacode=$tData->getItem('phoneareacode');
		if(!$phoneareacode) $phoneareacode='10';
		if($phoneareacode) $phone.=$phoneareacode.'-';
		$phonenumber=$tData->getItem('phonenumber');
		if($phonenumber) $phone.=$phonenumber.'-';
		$phoneext=$tData->getItem('phoneext');
		if($phoneext) $phone.=$phoneext;
		$tData->addItem('phone',$phone);
	}
	
}

?>