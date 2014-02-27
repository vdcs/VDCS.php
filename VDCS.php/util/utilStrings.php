<?
class utilStrings extends utilString
{
	
	/*
	########################################
	########################################
	*/
	public static function doSwap(&$s1,&$s2){self::swap($s1,$s2);}
	public static function doLists($str,&$k,&$v,$sp='='){self::lists($str,$k,$v,$sp);}
	
	
	/*
	########################################
	########################################
	*/
	public static function toExtentAppend($re,$v,$smb=',',$totals=0)
	{
		if(len($re)>0){
			$arys=toSplit($re,$smb);
			$total=count($arys);
			$n=$total-$totals+1;if($n<0)$n=0;
			$re='';
			for($a=$n;$a<$total;$a++){
				$re.=$smb.$arys[$a];
			}
		}
		$re.=$smb.$v;
		$re=substr($re,len($smb));
		return $re;
	}
	
	public static function toExtentFilter($re,$v,$smb=',')
	{
		if(len($v)>0 && ins($smb.$re.$smb,$smb.$v.$smb)>0){
			$re=r($smb.$re.$smb,$smb.$v.$smb,$smb);
			if(len($re)>len($smb)){
				$re=substr($re,len($smb),len($re)-len($smb)-1);
			}
			else{
				$re=r($re,$smb,'');
			}
		}
		return $re;
	}
	
	public static function isExtentValue($s,$v,$smb=',')
	{
		$re=false;
		if(len($v)>0 && ins($smb.$s.$smb,$smb.$v.$smb)>0) $re=true;
		return $re;
	}
	
}
?>