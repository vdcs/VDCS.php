<?
/*
$re.=NEWLINE.'<table width="100%" class="table-break">';
if($t) $re.=NEWLINE.'<tr><td>'.$t.'</td></tr>';
$re.=NEWLINE.'<tr><td>';
$re.=NEWLINE.'<pre style="text-align:left;">';
if(!is_array($o)) { $re.='[no Array print]'; }
else if(count($o)<1) { $re.='[no Array Length print]'; }
else { $re.=NEWLINE.print_r($o,true); }
$re.=NEWLINE.'</pre>';
$re.=NEWLINE.'</td></tr>';
$re.=NEWLINE.'</table>';
*/
/*
echo '<pre>';
reflection::export(new reflectionclass('PagePortal'));
echo '</pre>';
*/
//function debugAry($o,$t=''){debuga($o,$t);}function debugVar($o){debugx(test::toVarString($o));}function debugTxt($s,$t=1){debugx(test::toTxtString($s,$t));}function debugXML($s){debugx(test::toXMLString($s));}
class ResTest
{
	
	public static function logAction($name='action',$value=''){return ResLog::action($name,$value);}
	
	
	function put($s){echo $s;}
	function puts($s){echo $s.NEWLINE;}
	
	function debug(){for($i=0;$i<func_num_args();$i++) echo func_get_arg($i).NEWLINE;}
	function debugc(){for($i=0;$i<func_num_args();$i++) echo '<!--'.func_get_arg($i).'-->'.NEWLINE;}
	function debugv(){for($i=0;$i<func_num_args();$i++) echo '<pre>'.func_get_arg($i).NEWLINE.'</pre>';}
	function debugs(){for($i=0;$i<func_num_args();$i++) echo func_get_arg($i).'<br/>'.NEWLINE;}
	
	public static function j($s){debug('/* '.trim($s).' */'.NEWLINE);}
	public static function x($s,$br=true){
		switch(DEBUG_TYPE){
			case 1:		echo '<!--'.$s.'-->'.NEWLINE;break;		//debugc($s)
			case 2:		self::j($s);break;
			default:	$br?self::debugs($s):self::debug($s);break;
		}
	}
	public static function vc(){for($i=0;$i<func_num_args();$i++) self::x('<pre>'.htmlspecialchars(func_get_arg($i)).NEWLINE.'</pre>');}
	public static function a($o,$t=''){self::x(test::toAry($o,$t));}
	public static function _Var($o){self::x(test::toVar($o));}function _Txt($s,$t=1){self::x(test::toTxt($s,$t));}function _XML($s){self::x(test::toXML($s));}
	public static function _Ary($o,$t=''){self::a($o,$t);}
	public static function _Tree($o,$t=''){self::x(test::toTree($o,$t));}
	public static function _Table($o,$t=''){self::x(test::toTable($o,$t));}
	public static function _Object($o,$t=''){self::x(test::toObject($o,$t));}

	public static function toObject($o,$t=''){return print_r(get_object_vars($o),true);}
	
	
	public static function pr($o,$inline=false)
	{
		$re=print_r($o,true);
		$re=self::toVarString($re);
		if($inline) $re=r($re,NEWLINE,'');
		return $re;
	}
	
	public static function toVarString($re)
	{
		$re=str_replace("\r","\n",$re);			//回车
		$re=str_replace("\n\n","\n",$re);		//换行
		$re=str_replace("\n",NEWLINE,$re);		//换行
		return $re;
	}
	
	
	function toHTML($s)
	{
		return iso($s)?'[Ojbect]':htmlspecialchars($s);
		//return utilCode::toHTML($s,0,0);
	}
	
	function toXML($s)
	{
		$re=NEWLINE.'<!-- ';
		$re.=$s;
		$re.=' -->';
		return $re;
	}
	
	function toTxt($s,$t=1)
	{
		if($s===null) $s='NULL';
		if($t>1){
			$s=self::toHTML($s);
			$re=NEWLINE.'<textarea cols="60" rows="1">';
			$re.=NEWLINE.$s;
			$re.=NEWLINE.'</textarea>';
		}
		else{
			if($t==1) $s=self::toHTML($s);
			$re=NEWLINE.'<pre>';
			$re.=NEWLINE.$s;
			$re.=NEWLINE.'</pre>';
		}
		return $re;
	}
	
	
	function toStr($v)
	{
		$re=$v;
		if(is_array($re)) $re=self::pr($re);
		//elseif(iso($re)) $re=$re->;
		elseif(isTree($re)) $re=self::toTree($re);
		elseif(ins($re,'<?xml')) $re=htmlspecialchars($re);
		return $re;
	}
	
	function toAry($ary,$tit='')
	{
		if(!is_array($ary) && !is_object($ary)) return '[no Array print]';
		$re=NEWLINE.'<table width="100%" class="table-break">';
		$re.=NEWLINE.'<tr><td>';
		$re.=NEWLINE.'<pre style="text-align:left;">';
		//$re.=self::pr($ary);
		$re.=NEWLINE.'Array';
		$re.=NEWLINE.'(';
		foreach($ary as $k => $v){
			$str=self::toStr($v);
			$str=r($str,'&lt;!--','《!==');
			$str=r($str,'--&gt;','==》');

			$re.=NEWLINE.'    ['.$k.'] => '.$str;
		}
		$re.=NEWLINE.')';
		$re.=NEWLINE.'</pre>';
		$re.=NEWLINE.'</td></tr>';
		$re.=NEWLINE.'</table>';
		return $re;
	}
	
	function toTree($tre,$tit=''){
		if(!isTree($tre)) return '[not tree]';
		$re=NEWLINE.'<table class="table tables table-bordered table-striped table-hover table-test">';
		$re.=NEWLINE.'<tr><td colspan="3">'.$tit.' total:'.$tre->getCount().'</td></tr>';
		$tre->doBegin();
		for($i=0;$i<$tre->getCount();$i++){
			$re.=NEWLINE.'<tr>';
			$re.=NEWLINE.'<td>['.$i.']</td>';
			$re.=NEWLINE.'<td>'.$tre->getItemKey().'</td>';
			$re.=NEWLINE.self::toValue($tre->getItemValue(),'<td>{$var}</td>');
			$re.=NEWLINE.'</tr>';
			$tre->doMove();
		}
		$re.=NEWLINE.'</table>';
		return $re;
	}
	
	function toTable($tbl,$tit=''){
		if(!isTable($tbl)) return '[not table]';
		$aryFields=explode(',',$tbl->getFields());
		$re=NEWLINE.'<table class="table table-bordered table-striped table-hover table-test">';
		$re.=NEWLINE.'<tr><td colspan="'.(count($aryFields)+1).'">'.$tit.' total:'.($tbl->getRow()).'</td></tr>';
		$re.=NEWLINE.'<tr>';
		$re.=NEWLINE.'<th></th>';
		reset($aryFields);
		for($a=0;$a<count($aryFields);$a++){
			$re.=NEWLINE.'<th>'.current($aryFields).'</th>';
			next($aryFields);
		}
		$re.=NEWLINE.'</tr>';
		$tbl->doItemBegin();
		for($i=0;$i<$tbl->getRow();$i++){
			$re.=NEWLINE.'<tr>';
			$re.=NEWLINE.'<td>['.($i+1).']</td>';
			reset($aryFields);
			for($a=0;$a<count($aryFields);$a++){
				$re.=NEWLINE.self::toValue($tbl->getItemValue(current($aryFields)),'<td>{$var}</td>');
				next($aryFields);
			}
			$re.=NEWLINE.'</tr>';
			$tbl->doItemMove();
		}
		$re.=NEWLINE.'</table>';
		return $re;
	}
	
	function toVar($ary,$t='ary')
	{
		$re='';
		if(is_array($ary)) $t='ary';
		switch($t){
			case 'ary':
				$re.=NEWLINE.'<table>';
				$re.=NEWLINE.'<tr><td>key</td><td>value</td></tr>';
				foreach($ary as $k=>$v){
					$re.=NEWLINE.'<tr><td>'.$k.'</td><td>'.$ary[$k].'</td></tr>';
				}
				$re.=NEWLINE.'</table>';
				break;
			case 'server':
				$re.=NEWLINE.'<table>';
				$re.=NEWLINE.'<tr><td>key</td><td>value</td></tr>';
				foreach($_SERVER as $k=>$v){
					$re.=NEWLINE.'<tr><td>'.$k.'</td><td>'.$_SERVER[$k].'</td></tr>';
				}
				$re.=NEWLINE.'</table>';
				break;
		}
		return $re;
	}
	
	
	function toValue($s,$tpl='{$var}'){
		if(!$tpl) $tpl='{$var}';
		if(is_array($s)){
			$val=self::pr($s);
			$val=self::toHTML($val);
			$val='<pre>'.$val.'</pre>';
		}
		elseif(is_object($s)){
			$val='(Object)';
		}
		else{
			$val=$s;
			$val=self::toHTML($val);
			if(len($val)>30){
				$val='<textarea cols="30" rows="3" style="width:95%;height:'.(len($val)>500?50:20).'px;">'.$val.'</textarea>';
			}
		}
		return r($tpl,'{$var}',$val);
	}
	/*
	Public Function toVariable(ByVal strer As String, Optional ByVal strTpl As String = "{$var}") As String
		If Len(strTpl) < 1 Then strTpl = "{$var}"
		Dim tmpTypeName As String, tmpVar As String
		tmpTypeName = TypeName(strer)
		Select Case LCase(tmpTypeName)
		Case "empty"
			tmpVar = "<font class=""gray i"">empty</font>"
		Case "string"
			tmpVar = oCode.toHTMLValue(strer, 0, 0)
			If Len(tmpVar) > 30 Then tmpVar = "<textarea cols=""30"" rows=""3"">" & tmpVar & "</textarea>"
		Case "byte", "double", "integer", "long", "currency", "date"
			tmpVar = "<font class=""gray i"">" & tmpTypeName & "</font> " & strer
		Case "variant()"
			tmpVar = "<font class=""gray i"">" & tmpTypeName & "</font>"
		Case Else
			tmpVar = "<font class=""gray i"">" & tmpTypeName & "</font>"
		End Select
		toVariable = Replace(strTpl, "{$var}", tmpVar)
	End Function
	*/
}
?>