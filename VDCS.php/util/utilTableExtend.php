<?
class utilTableExtend
{
	
	public static function getItem($o,$strFieldTerms,$strFieldValue)
	{
		if(ins($strFieldTerms,'=')>0){
			utilString::lists($strFieldTerms,$tmpKey,$tmpValue,'=');
			//list($tmpKey,$tmpValue)=explode('=',$strFieldTerms);
			//$tmpKey=substr($strFieldTerms,0,ins($strFieldTerms,'=')-1);
			//$tmpValue=substr($strFieldTerms,ins($strFieldTerms,'='));
			//debugx($tmpKey.'--'.$tmpValue);
		}
		else{
			$tmpKey=$o->getFields();
			$tmpKey=substr($tmpKey,0,ins($tmpKey,',')-1);
			$tmpValue=$strFieldTerms;
		}
		$re='';
		$o->doItemBegin();
		for($t=0;$t<$o->getRow();$t++){
			if($o->getItemValue($tmpKey)==$tmpValue){
				$re=$o->getItemValue($strFieldValue);
				break;
			}
			$o->doItemMove();
		}
		return $re;
	}
	
	public static function getAtt($o,$strFieldValue,$strFieldTitle,$strFieldStar='',$strSpace='--')
	{
		$re='';
		if(isTable($o)){
			$o->doItemBegin();
			for($t=0;$t<$o->getRow();$t++){
				$tmpSpace='';
				if($strFieldStar){ for($s=2;$s<$o->getItemValueInt('star');$s++){ $tmpSpace.=$strSpace; } }
				$re.=';'.$o->getItemValue($strFieldValue).'='.$tmpSpace.$o->getItemValue($strFieldTitle);
				$o->doItemMove();
			}
			if($re) $re=substr($re,1);
		}
		return $re;
	}
	
}
?>