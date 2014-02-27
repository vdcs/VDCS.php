<?
defined('_BASE_PRICE_PCS') || 			define('_BASE_PRICE_PCS',		2);

class utilCode extends utilString
{
	public static function toEncodeFilterValue($s,$fmt,$p1='',&$isv=true)
	{
		if(!$fmt) return $s;
		if(len($p1)>0){
			if(inp('cut,html,text,explain,summary,remark',$fmt)>0){
				$s=self::toCuted($s,toInt($p1));
			}
		}
		switch($fmt){
			case 'int':		$re=toInt($s);break;
			case 'num':		$re=toNum($s);break;
			case 'xml':		$re=self::toXMLValue($s);break;
			case 'xmlc':		$re=self::toXMLC($s);break;
			case 'js':		$re=self::toJS($s);break;
			case 'html':		$re=self::toHTML($s);break;
			case 'html':		$re=self::toHTML($s);break;
			case 'text':		$re=self::toHTMLText($s);break;
			case 'explain':
			case 'summary':		$re=self::toHTMLExplain($s);break;
			case 'tim':		$re=self::toTime($s,$p1);break;
			case 'time':		$re=self::toTime($s,'Y-m-d H:i:s');break;
			case 'day':		$re=self::toTime($s,'d');break;
			case 'month':		$re=self::toTime($s,'m');break;
			case 'year':		$re=self::toTime($s,'Y');break;
			case 'days':		$re=self::toTime($s,'m-d');break;
			case 'date':		$re=self::toTime($s,'Y-m-d');break;
			case 'dates':		$re=self::toTime($s,'Y-m-d H:i');break;
			case 'times':		$re=self::toTimes($s);break;	 //channels.toTimes
			case 'url':		$re=self::toURL($s);break;
			case 'urlencode':	$re=self::urlEncode($s);break;
			case 'explain.js':	$re=self::toJS(self::toHTMLValue($s,2,0));break;
			case 'filesize':	$re=self::toFileSize($s,$p1);break;
			case 'disksize':	$re=self::toDiskSize($s,$p1);break;
			case 'price':		$re=self::toPrices($s);break;
			case 'pricevalue':	$re=self::toPriceValue($s);break;
			case 'money':		$re=self::toPrices($s);break;
			case 'emoney':		$re=self::toNumber($s,1);break;
			case 'number':		$re=self::toNumber($s,toInt($p1));break;
			case 'commak':		$re=self::toCommaK($s);break;
			case 'per':		$re=self::toPercent($s,toInt($p1));break;
			case 'discount':	$re=self::toDiscount($s,toInt($p1));break;
			case 'tzone':
			case 'trimzone':	$re=self::toTrimZero($s);break;
			case 'remark':		$re=self::toHtmlRemark($s);break;
			case 'cut':		$re=$s;break;
			case 'form':		$re=self::toFormValue($s);break;
			case 'form.input':	$re=self::toFormInput($s);break;
			case 'form.textarea':	$re=self::toFormTextarea($s);break;
			case 'form.hidden':	$re=self::toFormHidden($s);break;
			case 'form.value':	$re=self::toFormValue($s);break;
			case 'form.editor':	$re=self::toFormEditor($s);break;
			case 'form.editorhtml':	$re=self::toFormEditorHtml($s);break;
			case 'form.checked':	$re=self::toFormChecked($s);break;
			default :		$re=$s;$isv=false;break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toNumber($s,$n=2)
	{
		//return round($s,$n);
		return sprintf("%01.".$n."f",$s);
	}
	public static function toPrice($s){return self::toNumber($s,_BASE_PRICE_PCS);}
	public static function toPrices($s)
	{
		//$s=self::toNumber($s,_BASE_PRICE_PCS);
		return sprintf("%01."._BASE_PRICE_PCS."f",$s);
	}
	
	public static function toPriceValue($price1,$price2=null,$unit=null,$unitw=null)
	{
		$re='';
		if($price1>0){
			if(isn($unit)) $unit=appv('unit.price');
			if(isn($unitw)) $unitw=appv('unit.wing');
			$price1=toNum($price1);
			$re.=($price1>10000?(($price1/10000).$unitw):$price1);
			$price2=toNum($price2);
			if($price2>0){
				$re.='-'.($price2>10000?(($price2/10000).$unitw):$price2);
			}
			$re.=$unit;
		}
		return $re;
	}
	
	public static function toCommaK($s)
	{
		//$s=123456789;
		$s=number_format($s,3,'',',');
		$s=substr($s,0,(strlen($s)-3));
		return $s;
	}
	
	public static function toDiscount($re,$discount=0)
	{
		$re=toNum($re);
		if(isInt($discount_)) $discount_=100;
		If($discount_>0 && $discount_<100) $re=$re*$discount_/100;
		return self::toPrice($re);
	}
	
	public static function toPercent($s,$fmt='')
	{
		$s=toInt($s);
		if($s<1 || $s>100) $s=100;
		return $s.'%';
	}
	
	public static function toTime($s,$fmt='Y-m-d H:i:s')
	{
		$re='';
		if(strlen($s)>0){
			if(!is_numeric($s)) $s=VDCSTime::toNumber($s);
			return gmdate($fmt,$s+DCS::timezone(1));
		}
		return $re;
	}
	public static function toTimes($n)
	{
		$re=VDCSTime::toString($n);
		$re=str_replace(' ','<br/>',$re);
		return '<span class="time">'.$re.'</span>';
	}
	
	public static function toFileSize($size,$mi=1)
	{
		if(!isInt($size)){
			if(len($size)<1) $size='0B';
			return $size;
		}
		if(!$mi) $mi=1;
		if($mi=='k') $mi=1024;
		if($mi=='m') $mi=1024*1024;
		if($mi=='g') $mi=1024*1024*1024;
		if(!$mi) $mi=1;
		$size=toInt($size)*toInt($mi);
		$a=array('B','KB','MB','GB','TB','PB');
		$pos=0;
		while($size>=1024){
			$size/=1024;
			$pos++;
		}
		return round($size,$pos>2?3:$pos).''.$a[$pos];
	}
	public static function toDiskSize($size,$mi='m')
	{
		if(!isInt($size)){
			if(len($size)<1) $size='0B';
			return $size;
		}
		if(!$mi) $mi='m';
		if($mi=='k') $mi=1024;
		if($mi=='m') $mi=1024*1024;
		if($mi=='g') $mi=1024*1024*1024;
		if(!$mi) $mi=1;
		$size=toInt($size)*toInt($mi);
		$a=array('B','K','M','G','T','P');
		$pos=0;
		while($size>=1024){
			$size/=1024;
			$pos++;
		}
		return round($size,$pos>2?3:$pos).''.$a[$pos];
	}
	
	public static function toIDS($s,$p1=',')
	{
		$arys=explode($p1,$s);
		foreach($arys as $v){
			if(!is_numeric($v) || strpos($v,'.')>-1 || strpos($v,'-')>-1) $v=0;
			if(len($v)>0){
				//if($strt=='array') { $re[$i]=$v;$i++;} 
				$re.=$p1.$v;
			}
		}
		$re=substr($re,strlen($p1));
		return $re;
	}
	
	public static function toValues($values,$type=0,$ret=null,$smb=',')
	{
		$re=array();
		if(!$smb) $smb=',';
		if(is_array($values)){
			$avalue=$values;
			is_null($ret) && $ret='array';
		}
		else{
			$avalue=toSplit($values,$smb);
			is_null($ret) && $ret='string';
		}
		foreach($avalue as $value){
			switch($type){	//int,num,id,x
				case 1:		if(!is_numeric($value) || strpos($value,'.')>-1) $value=null;break;
				case 2:		if(!is_numeric($value)) $value=null;break;
				case 5:		if(!is_numeric($value) || strpos($value,'.')>-1 || strpos($value,'-')>-1) $value=null;break;
				case 9:		if(!isx($value)) $value=null;break;
			}
			if(!is_null($value)) array_push($re,$value);
		}
		return $ret=='array'?$re:implode($smb,$re);
	}
	
	/*
Public Function toBetweenInt(ByVal num As Long,ByVal min As Long,ByVal max As Long,Optional ByVal def As Long=0) As Long
    If num < min Or num > max Then num=def
    toBetweenInt=num
End Function
Public Function toBetweenNum(ByVal num As Double,ByVal min As Double,ByVal max As Double,Optional ByVal def As Double=0) As Double
    If num < min Or num > max Then num=def
    toBetweenNum=num
End Function
	*/
	
	
	/*
	########################################
	########################################
	*/
	/*
Public Function toTrim(ByVal re As String,Optional ByVal strt As Integer=0) As String
    re=Trim(re)
    toTrim=re
End Function

Public Function toTrimed(ByVal re As String,Optional ByVal strt As Integer=0) As String
    If Len(re)=0 Then Exit Function
    Dim t As Boolean
    If strt > 0 Then
        re=RTrim(re)
        t=True
        Do While t=True
            If Right(re,1) <> Chr(10) Then t=False: Exit Do
            re=Left(re,Len(re) - 1)
        Loop
        re=RTrim(re)
    End If
    If strt > 1 Then
        If strt > 2 Then re=LTrim(re)
        t=True
        Do While t=True
            If Left(re,1) <> Chr(10) And Left(re,1) <> Chr(13) Then t=False: Exit Do
            re=Right(re,Len(re) - 1)
        Loop
        If strt > 2 Then re=LTrim(re)
    End If
    toTrimed=re
End Function
	*/
	
	public static function toTrimZero($str)
	{
		list($int,$dec)=explode('.',(string)$str);	//拆解字串,格式為: 整數.小數
		$dec = rtrim($dec,'0');				//將小數點 ,右邊的0去除
		if(empty($dec)) return trim($int.$dec);
		return trim($int.'.'.$dec);			//重組格式 ,並返回
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toCuts($re,$len){return left($re,$len);}
	public static function toCut($re,$len,$suffix=''){return self::toCut_s($re,$len,$suffix);}
	public static function toCuted($re,$len,$suffix=''){return self::toCut_s($re,$len,$suffix);}
	public static function toCutt($re,$len,$suffix=''){return self::toCut_s($re,$len,$suffix,1);}
	
	public static function toCut_s($str,$len,$suffix='',$tlen=2)		//single
	{
		$_len=0;$_suffix='';
		$re='';$n=0;
		//$tlen=2;			//汉字 =2 other =1
		$clen=3;			//utf-8 =3  other =2
		$str_len=strlen($str);
		for($i=0;$i<$str_len;$i++){
			if(ord($str[$i])>127){
				$n+=$clen;
				$i+=($clen-1);
				$_len+=$tlen;
			}
			else{
				$n++;
				$_len++;
			}
			if($_len>=$len){
				if($n<strlen($str)) $_suffix=$suffix;
				break;
			}
		}
		for($i=0;$i<$n;$i++){$re.=$str[$i];}
		return $re.$_suffix;
	}
	public static function toCut_d($str,$strLen,$suffix='')		//double
	{
		if($strLen<=0) return $str;
		$re='';$k=3;				//utf-8 =3  other =2
		while($i <= len($str)){
			if(ord($str[($i-1)])>127){
				$han++;
				$i=$i+$k;
			}
			else{
				$eng++;
				$i=$i+1;
			}
			if(($han+$eng)>=$strLen){
				$strLen=$eng+(int)$han*$k;
				break;
			}
		}
		for($i=0;$i<$strLen;$i++){ $re.=$str[$i];}
		return $re.$suffix;
	}
	
	public static function lens($str){return self::toLen($str,2);}
	public static function lent($str){return self::toLen($str,1);}
	public static function toLen($str,$tlen=2)
	{
		$re=0;$n=0;$clen=3;
		$str_len=strlen($str);
		for($i=0;$i<$str_len;$i++){
			if(ord($str[$i])>127){
				$i+=($clen-1);
				$re+=$tlen;
			}
			else{
				$re++;
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toHTMLValue($re,$t=1,$c=0)
	{
		if(strlen($re)<1) return '';
		$re=trim($re);
		if($c>0) $re=self::toCut($re,$c);
		//$tmpstr=htmlspecialchars($tmpstr);				//stripslashes
		$re=stripslashes($re);
		$re=str_replace('  ',' &nbsp;',$re);
		//$re=str_replace('&','&amp;',$re);
		$re=str_replace('\'','&#39;',$re);
		$re=str_replace('"','&quot;',$re);
		$re=str_replace('<','&lt;',$re);
		$re=str_replace('>','&gt;',$re);
		$re=preg_replace('/&amp;(#\d{3,5};)/','&\\1',$re);
		switch($t){
			case 1:
				$re=str_replace("\t",'&nbsp;',$re);	//制表符
				$re=str_replace("\n",'',$re);		//换行
				$re=str_replace("\r",'',$re);		//回车
				break;
			case 2:
				$re=str_replace("\t",'&nbsp; &nbsp; &nbsp; &nbsp; ',$re);
				$re=nl2br($re);
				break;
		}
		return $re;
	}
	public static function toHTML($re,$c=0) { return self::toHTMLValue($re,0,$c);}
	public static function toHTMLTopic($re,$c=0) { return self::toHTMLValue($re,1,$c);}
	public static function toHTMLText($re,$c=0) { return self::toHTMLValue($re,1,$c);}
	
	public static function toHTMLExplain($re,$c=0)
	{
		$re=self::toHTMLTag($re);
		$re=self::toHTMLValue($re,2,$c);
		return $re;
	}
	public static function toHTMLRemark($re,$c=0)
	{
		if(strlen($re)<1) return '';
		$re=trim($re);
		if($c>0) $re=self::toCut($re,$c);
		$re=str_replace("\t",'&nbsp; &nbsp; &nbsp; &nbsp; ',$re);
		$re=nl2br($re);
		return $re;
	}
	public static function toHTMLEncode($re)
	{
		$re=self::toHTMLValue($re,0,0);
		$re=str_replace('\'','&#39;',$re);
		$re=str_replace('"','&quot;',$re);
		return $re;
	}
	
	public static function toHTMLTag($re,$t=0,$c=0)
	{
		$re=preg_replace('/\<(.[^\>]*)\>/is','',$re);
		//$re=preg_replace('/\[(.[^\]]*)\]/is','',$re);
		switch($t){
			case 1:
				$re=str_replace("\t",'',$re);		//制表符
				$re=str_replace("\n",'',$re);		//换行
				$re=str_replace("\r",'',$re);		//回车
				break;
			case 2:
				$re=str_replace("\t",'&nbsp; &nbsp; &nbsp; &nbsp; ',$re);
				$re=nl2br($re);
				break;
		}
		if($c>0) $re=self::toCut($re,$c);
		return $re;
	}
	public static function toHTMLTags($re)
	{
		$re=preg_replace('/\&lt;(.[^\&gt;]*)\&gt;/is','',$re);
		return $re;
	}

	
	/* ################################## */
	public static function toFormInput($re,$t=1)
	{
		if(strlen($re)<1) return '';
		if($t) $re=stripslashes($re);
		$re=str_replace('<','&lt;',$re);
		$re=str_replace('>','&gt;',$re);
		$re=str_replace('\'','&#39;',$re);
		$re=str_replace('"','&quot;',$re);
		return $re;
	}
	
	public static function toFormTextarea($re)
	{
		if(strlen($re)<1) return '';
		if($t) $re=stripslashes($re);
		$re=str_replace('<','&lt;',$re);
		$re=str_replace('>','&gt;',$re);
		//$re=str_replace('\'','&#39;',$re);
		//$re=str_replace('"','&quot;',$re);
		return $re;
	}
	
	public static function toFormHidden($s,$t=1) { return self::toFormInput($s,$t);}
	public static function toFormValue($s,$t=1) { return self::toFormInput($s,$t);}
	
	public static function toFormEditor($re,$c=0)
	{
		if(strlen($re)<1) return '';
		$re=rtrim($re);
		if($c>0) $re=self::toCut($re,$c);
		$re=str_replace('<','&lt;',$re);
		$re=str_replace('>','&gt;',$re);
		$re=str_replace('\'','&#39;',$re);
		$re=str_replace('"','&quot;',$re);
		//re=Replace(re,Chr(10),"\n")			//换行
		//re=Replace(re,Chr(13),"")			//回车
		return $re;
	}
	
	public static function toFormEditorHtml($re,$c=0)
	{
		if(strlen($re)<1) return '';
		$re=rtrim($re);
		if($c>0) $re=self::toCut($re,$c);
		$re=str_replace('<','&lt;',$re);
		$re=str_replace('>','&gt;',$re);
		$re=str_replace('\'','&#39;',$re);
		$re=str_replace('"','&quot;',$re);
		/*
		$re=r($re,'\\','\\\\');
		$re=r($re,'/','\\/');
		$re=r($re,'\'','\\\'');
		$re=r($re,'"','\\"');
		*/
		//$re=r(re,Chr(10),"\n");		//换行
		//$re=Replace(re,Chr(13),"");			//回车
		return $re;
	}
	
	public static function toFormChecked($value)
	{
		$re='';
		if($value=='yes' || ($value!='' && $value!='no')) $re='checked';
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toTxt($re)
	{
		
		return $re;
	}
	
	public static function toString($s){if($s==null)$s='';return $re;}
	
	public static function toJS($re)
	{
		$re=str_replace('\\','\\\\',$re);
		$re=str_replace('\'','\\\'',$re);
		$re=str_replace('"','\\"',$re);
		$re=str_replace('(','\\(',$re);
		$re=str_replace(')','\\)',$re);
		//$re=str_replace('<','&lt;',$re);
		//$re=str_replace('>','&gt;',$re);
		//re=Replace(re,Chr(10),"\n")		//换行
		//re=Replace(re,Chr(13),"")			//回车
		return $re;
	}
	
	public static function toXMLC($re)
	{
		//$re=r($re,' ','&nbsp;');
		//$re=r($re,'&','&amp;');
		$re=r($re,'&','&#38;');
		$re=r($re,'<','&lt;');
		$re=r($re,'>','&gt;');
		//$re=str_replace('<![CDATA[','&lt;![CDATA[',$re);
		//$re=str_replace(']]>',']]&gt;',$re);
		//$re=r($re,"\n","<br>");	//换行
		//$re=r($re,"\r","");		//回车
		return $re;
	}
	
	public static function toXMLValue($re)
	{
		if(is_array($re)) return '(Array)';
		elseif(is_object($re)) return '(Object)';
		if(strpos($re,'<')!==false || strpos($re,'>')!==false || strpos($re,'&')!==false || strpos($re,"\r")!==false || strpos($re,"\n")!==false){
			//$re=str_replace('<','&lt;',$re);
			//$re=str_replace('>','&gt;',$re);
			$re=str_replace('<![CDATA[','&lt;![CDATA[',$re);
			$re=str_replace(']]>',']]&gt;',$re);
			$re='<![CDATA['.$re.']]>';
		}
		return $re;
	}
	
	public static function toSQL($re,$q=0)
	{
		//$re=addslashes($re);
		//if(get_magic_quotes_gpc()){
			$re=str_replace('\\','\\\\',$re);
			$re=str_replace('`','\\`',$re);
			$re=str_replace('\'','\\\'',$re);
		//}
		if($q==1) $re='\''.$re.'\'';
		return $re;
	}
	public static function toSQLAppend($re,$query,$term='')
	{
		if($query){
			if(!$term) $term='and';
			$re=($re)?$re.' '.$term.' '.$query:$query;
		}
		return $re;
	}
	
	/*
	Public Function toRegexPattern($re)
		re=Replace(re,".","\.")
		re=Replace(re,"$","\$")
		re=Replace(re,"""","\""")
		toRegexPattern=re
	End Function
	*/
	
	public static function toTags($re)
	{
		return trim(strip_tags($re));
	}
	
	public static function toSlashs(&$ary)
	{
		if(is_array($ary)){
			foreach($ary as $k=>$v){
				if(is_array($v)){
					self::toSlashs($ary[$k]);
				}else{
					$ary[$k]=addslashes($v);
				}
			}
		}
	}
	
	/*
Public Function toDTMLCache(ByVal re As String) As String
    re=Replace(re,"""","""""")
    re=Replace(re,vbCrLf,"""&vbcrlf&""")
    toDTMLCache=re
End Function
	*/
	
	public static function toVarBlank($re)
	{
		$re=str_replace(NEWLINE,'',$re);		//大换行
		$re=str_replace("\t",'',$re);			//制表符
		$re=str_replace("\n",'',$re);			//换行
		$re=str_replace("\r",'',$re);			//回车
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toURL($re)
	{
		return self::toHTMLValue($re,0,0);
		//return urlencode($re);
	}
	
	public static function urlLink($re,$params)		//URL连接&编码
	{
		if($params && !is_string($params)){
			$parama=array();
			if(isa($params)){
				$params0=$params;
				$params=newTree();
				$params->setArray($params0);
			}
			$params->doBegin();
			for($t=0;$i<$params->getCount();$i++){
				array_push($parama,$params->getItemKey().'='.self::urlEncode($params->getItemValue()));
				$params->doMove();
			}
			$params=ajoin($parama,'&');
		}
		if($params){
			if(ins($re,'?')<1) $re.='?';
			else if(right($re,1)!='&') $re.='&';
			$re.=$params;
		}
		return $re;
	}
	public static function toLink($re,$params){return self::urlLink($re,$params);}			//hold
	public static function toURLAppend($re,$params){return self::urlLink($re,$params);}		//hold
	public static function urlQuery($url,$params){return self::urlLink($re,$params);}		//hold
	
	//rawurlencode:功能和urlencode基本一样，采用的是RFC1738编码，因此空格会编码为%20。
	public static function urlEncode($re){return rawurlencode($re);}
	public static function urlDecode($re){return rawurldecode($re);}
	public static function toURLEncode($re){return self::urlEncode($re);}				//hold
	public static function toURLDecode($re){return self::urlDecode($re);}				//hold
	
	
	/*
	########################################
	########################################
	*/
	/*
	Public Function toEncodeFilename($re)
		Dim re,tmpNum As Long,tmpName,tmpExt
		tmpNum=InStrRev(re,".")
		If tmpNum > 0 Then
			tmpName=Left(re,tmpNum - 1)
			tmpExt=Right(re,Len(re) - tmpNum)
			re=init.iserver.URLEncode(tmpName) & "." & tmpExt
		Else
			re=init.iserver.URLEncode(re)
		End If
		toEncodeFilename=re
	End Function
	*/
	
	
	/*
	########################################
	########################################
	*/
	/*
Public Function toOptionItems(ByVal items_ As String,Optional p01 As String="|",Optional p02 As String=":",Optional p1 As String=";",Optional p2 As String="=")
    Dim re As String,tmpAry As Variant,a As Integer
    tmpAry=Split(items_,p01)
    For a=0 To UBound(tmpAry)
        If InStr(tmpAry(a),p02) < 1 Then
            tmpAry(a)=tmpAry(a) & (p2 & tmpAry(a))
        Else
            tmpAry(a)=commons.r(tmpAry(a),p02,p2)
        End If
        re=re & (p1 & tmpAry(a))
    Next
    If Len(re) > 0 Then re=Mid(re,2)
    Erase tmpAry
    toOptionItems=re
End Function
Public Function toOptionAtt(ByVal items_ As String,Optional p1 As String=";",Optional p2 As String="=") As String
    Dim re As String
    If InStr(items_,";") > 0 Then
        re=toOptionItems(items_,";","=",p1,p2)
    ElseIf InStr(items_,"|") > 0 Then
        re=toOptionItems(items_,"|",":",p1,p2)
    ElseIf InStr(items_,",") > 0 Then
        re=toOptionItems(items_,",","=",p1,p2)
    Else
        re=toOptionItems(items_,";","=",p1,p2)
    End If
    toOptionAtt=re
End Function
	*/
	
	
	/*
	########################################
	########################################
	*/
	public static function getRandomNum($n)
	{
		mt_srand((double)microtime()*1000000);
		return mt_rand(0,$n);
	}
	/*
	Public Function getRandInt($strnum As Integer) As Long
		Dim re As Long,nn As Integer
		Randomize
		re=Int(strnum * Rnd) + 1
		For nn=1 To 5
			If re <= strnum Then Exit For
			re=re \ 2
			nn=nn - 1
		Next
		If re < 1 Then re=1
		getRandInt=re
	End Function
	*/
	public static function getRandNum($n){return self::getRand($n,0);}
	public static function getRand($n,$t=-1)
	{
		switch($t){
			case 0:		$chars='0123456789';break;
			case 2:		$chars='abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
			case 3:		$chars='abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-_~!@#$%^&*()[]{};\':",./<>?=+';break;
			case 6:		$chars='abcdefghijklmnopqrstuvwxyz';break;
			case 7:		$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
			// 默认去掉了容易混淆的字符oOLl和数字01
			case 8:		$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';break;
			default:	$chars='abcdefghijklmnopqrstuvwxyz0123456789';break;
		}
		//str_shuffle 函数随机地打乱字符串中的所有字符
		return self::toRandom($chars,$n);
	}
	
	public static function toRandom($chars,$n)
	{
		$re='';
		$max=strlen($chars)-1;
		mt_srand((double)microtime()*1000000);
		for($i=0;$i<$n;$i++){
			$re.=$chars[mt_rand(0,$max)];
		}
		return $re;
	}
	/*
	Public Function getRandDate($stry As Long)
		If Not IsNumeric(stry) < 1 Then stry=1900
		Dim tmpMonthNum As Integer,tmpYear As Long,tmpMonth As Integer,tmpDay As Integer
		tmpYear=stry + getRandInt(10)
		tmpMonth=getRandInt(12)
		tmpMonthNum=30
		If tmpMonth=2 Then tmpMonthNum=28
		tmpDay=getRandInt(tmpMonthNum)
		getRandDate=tmpYear & "-" & tmpMonth & "-" & tmpDay
	End Function
	*/
	
	
	/*
	########################################
	########################################
	*/
	//Public Function conv(ByVal s As String,ByVal Conversion As VbStrConv,Optional ByVal LocaleID As Long) As String
	//Public Function eval(ByVal s As String) As String
	//Public Function ConvUnicode(ByVal strData As String,Optional ByVal Charset As String=commons.CHARSET_DEFAULT) As String
	
}
?>