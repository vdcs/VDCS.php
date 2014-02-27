<?
class utilJson
{
	
	public static function enCode($array,$reals=false)
	{
		if($reals){
			utilArray::toRecursive($array,'urlencode',true);
			$re=json_encode($array);
			return urldecode($re);
		}
		else{
			return json_encode($array);
		}
	}
	
	public static function deCode($json)
	{
		return json_decode($json);
	}
	
}
?>