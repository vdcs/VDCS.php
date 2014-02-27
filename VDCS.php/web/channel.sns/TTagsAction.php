<?
class TTagsAction extends TTags
{
	
	public static function logAdd($id,$tagsort,$tagid)
	{
		$_status=0;
		$tData=newTree();
		$tData->addItem('rootid',$id);
		$tData->addItem('tagsort',$tagsort);
		$tData->addItem('tagid',$tagid);
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$FieldsAdd='rootid,tagsort,tagid,status,tim';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
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
		$term=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,'set',$values,$term);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
}
?>