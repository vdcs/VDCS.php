<?
class TTagsPackage extends TTags
{
	const PackageTableName			= 'db_tags_package';
	const PackageFieldID			= 'id';
	
	
	/*
	########################################
	########################################
	*/
	public static function is($pakid)
	{
		$_status=0;
		if(!$pakid) return $_status;
		$sqlQuery='id='.$pakid;
		$sql=DB::sqlSelect(self::PackageTableName,'total',self::PackageFieldID,$sqlQuery,'');
		//debugx($sql);
		$_total=DB::queryInt($sql);
		if($_total>0) $_status=1;
		return $_status;
	}
	
	public static function getTagids($pakid)
	{
		$sqlQuery='id='.$pakid;
		$sql=DB::sqlSelect(self::PackageTableName,'','tags_data',$sqlQuery,'');
		//debugx($sql);
		$treePak=DB::queryTree($sql);
		$tags_data=$treePak->getItem('tags_data');
		$tags_ary=VDCSData::us($tags_data);
		$aryid=array();
		if($tags_ary){
			foreach($tags_ary as $id=>$name){
				if($id && $name) array_push($aryid,$id);
			}
		}
		return implode(',',$aryid);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function create($ua,$pakid,$tagids='',$tData=null)
	{
		$_status=1;
		if(!$pakid) return 0;
		if(self::is($pakid)){
			if(!$tagids) $tagids=self::getTagids($pakid);
			$tagAr=toSplit($tagids,',');
			foreach($tagAr as $tagid){
				$tagid=toi($tagid);
				if($tagid){
					TTagsFollow::create($ua,$tagid);
				}
			}
			self::update($pakid,'total_follow','+');
		}
		return $_status;
	}
	public static function cancel($ua,$pakid,$tagids='')
	{
		$_status=1;
		if(!$pakid) return 0;
		if(self::is($pakid)){
			$tagAr=toSplit($tagids,',');
			foreach($tagAr as $tagid){
				$tagid=toi($tagid);
				if($tagid){
					TTagsFollow::del($ua,$tagid);
				}
			}
		}
		return $_status;
	}
	
	public static function update($id,$field,$value)
	{
		$_status=0;
		$values='';
		switch($value){
			case '+':
			case 'plus':
				$values=$field.'='.$field.'+1';
				break;
			case '-':
			case 'minus':
				$values=$field.'='.$field.'-1';
				break;
			default:
				$values=$field.'='.DB::q($value,1);
				break;
		}
		$term=self::PackageFieldID.'='.$id;
		$sql=DB::sqlUpdate(self::PackageTableName,'set',$values,$term);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
}
?>