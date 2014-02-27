<?
class libTransact
{
	
	public function toValue($value,$type,$param,$key,$tData)
	{
		if(isn($value)) $value=$tData->getItem($key);
		$re=$value;
		if(len($type)>0){
		switch($type){
			case '':		$re=$value;break;
			case 'v':		$re=$key;break;
			case 'int':		$re=toInt(trim($value));break;
			case 'num':		$re=toNum(trim($value));break;
			case 'trim':		$re=trim($value);break;
			case 'nint':		$re=(isn($value) || toLower($value)=='null') ? 0 : 1;break;
			case 'bint':		$re=($value=='true') ? 1 : 0;break;
			case 'bint0':		$re=($value=='false') ? 1 : 0;break;
			case 'bint1':		$re=($value=='1') ? 0 : 1;break;
			case 'bint2':		$re=($value=='1') ? 2 : 1;break;
			case 'sint':		$re=len($value)>0 ? 1 : 0;break;
			case 'time.int':	
			case 'timei':		$re=VDCSTime::toNumber($value);break;
			case 'len':		$re=len(trim($value));break;
			default:
				$funcname='to'.ucfirst($type);
				if(method_exists($this,$funcname)){
					$re=$this->$funcname($value,$param,$key,$tData);
				}
				break;
		}
		}
		return $re;
	}
	
	public function toDemo($value,$param,$key,$tData)
	{
		$re=$value;
		
		return $re;
	}
	
}
?>