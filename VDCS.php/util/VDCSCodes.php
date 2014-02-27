<?
class VDCSCodes
{
	
	public static function toPattern($s){ return '/'.$s.'/is';}
	
	public static function toCodes($re,$code=0)
	{
		switch($code){
			case 1:			$re=self::toConvUBB($re);break;			//UBB模块
			case 2:			$re=self::toConvHTML($re);break;		//HTML模块
			default:		$re=utilCode::toHTMLRemark($re);break;		//常规模块，仅支持换行
		}
		return $re;
	}
	
	public static function toCodesConv($re,$t='')
	{
		switch(toLower($t)){
			case 'ubb':		$re=self::toConvUBB($re);
			case 'html':		$re=self::toConvHTML($re);
			case 'emotes':
			case 'em':		$re=self::toConvEmotes($re);
			case 'signature':
			case 'sign':		$re=self::toConvSignature($re);
			default:		$re=self::toCodes($re,toInt($t));
		}
		return $re;
	}
	
	
	//########################################
	//########################################
	public static function toConvEmotes($re)
	{
		$urlImages=appURL('images');
		$re=preg_replace('/<url:'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'url.$1\']',$re);
		$re=utilRegex::toReplacePattern($re,'<img class="icon" src="'.$urlImages.'emotes/em/$1.gif'.'" />',self::toPattern('\[em([0-9]|[0-9]{2})\]'));
		$re=utilRegex::toReplacePattern($re,'<img class="icon" src="'.$urlImages.'emotes/em/$1.gif'.'" />',self::toPattern('\[emd*([0-9]|[0-9]{2})\]'));
		$re=utilRegex::toReplacePattern($re,'<img class="icon" src="'.$urlImages.'emotes/cool/$1.gif'.'" />',self::toPattern('\[emc*([0-9]|[0-9]{2})\]'));
		$re=r($re,'[br]','<br/>'.NEWLINE);
		return $re;
	}
	
	public static function toConvSignature($re)
	{
		$re=self::toConvBase($re);
		//####################
		$tableRule=self::getRuleTable('signature');
		$tableRule->doItemBegin();
		for($t=0;$t<$tableRule->getRow();$t++){
			//$treeRule=$tableRule->getItemTree();
			$re=utilRegex::toReplacePattern($re,$tableRule->getItemValue('html'),self::toPattern($tableRule->getItemValue('regex')));
			$tableRule->doItemMove();
		}
		//####################
		unsetr($tableRule);
		//####################
		$re=self::toConvEmotes($re);
		return $re;
	}
	
	
	//########################################
	//########################################
	public static function toConvUBB($re)
	{
		$re=self::toConvBase($re);
		//####################
		$tableRule=self::getRuleTable('ubb');
		$tableRule->doItemBegin();
		for($t=0;$t<$tableRule->getRow();$t++){
			//$treeRule=$tableRule->getItemTree();
			$re=utilRegex::toReplacePattern($re,$tableRule->getItemValue('html'),self::toPattern($tableRule->getItemValue('regex')));
			$tableRule->doItemMove();
		}
		//####################
		unsetr($tableRule);
		//####################
		$re=self::toConvEmotes($re);
		return $re;
	}
	
	
	//########################################
	//########################################
	public static function toConvHTML($re)
	{
		//$re=self::toConvBase($re);
		//####################
		$tableRule=self::getRuleTable('html');
		$tableRule->doItemBegin();
		for($t=0;$t<$tableRule->getRow();$t++){
			//$treeRule=$tableRule->getItemTree();
			//debugvc(self::toPattern($tableRule->getItemValue('regex')));
			$re=utilRegex::toReplacePattern($re,$tableRule->getItemValue('html'),self::toPattern($tableRule->getItemValue('regex')));
			$tableRule->doItemMove();
		}
		$re=r($re,'[br]','[br]'.NEWLINE);
		/*
		//####################
		$tableRule=self::getRuleTable('html');
		$tableRule->doItemBegin();
		for($t=0;$t<$tableRule->getRow();$t++){
			//$treeRule=$tableRule->getItemTree();
			$re=utilRegex::toReplacePattern($re,$tableRule->getItemValue('html'),self::toPattern($tableRule->getItemValue('regex')));
			$tableRule->doItemMove();
		}
		*/
		//####################
		unsetr($tableRule);
		//####################
		$re=self::toConvEmotes($re);
		return $re;
	}
	
	
	//########################################
	//########################################
	public static function toConvBase($re)
	{
		$re=r($re,NEWLINE,'[br]');
		$re=r($re,"\n",'[br]');
		$re=r($re,"\r",'');
		$re=r($re,"\t",'&nbsp;&nbsp;');
		$re=r($re,'  ','&nbsp;&nbsp;');
		$re=r($re,'<','&lt;');
		$re=r($re,'>','&gt;');
		return $re;
	}
	
	
	//########################################
	//########################################
	public static function getRuleTable($res)
	{
		$reTable=newTable();
		$arys=VDCSCache::getCache('common.codes.'.$res,'config',false);
		if(isa($arys)){
			$reTable->setArray($arys);
		}
		else{
			$resFilter='ubb.filter';
			if(inp('html',$res)>0) $resFilter='html.filter';
			$_xml=VDCSDTML::getConfigContent('common.config/data/codes/'.$res);
			$tableFilter=VDCSDTML::getConfigTable('common.config/data/codes/'.$resFilter);
			$tableFilter->doItemBegin();
			for($t=0;$t<$tableFilter->getRow();$t++){
				$tmpKey=$tableFilter->getItemValue('key');
				$tmpRegex=$tableFilter->getItemValue('regex');
				if(Len($tmpKey)>0 && len($tmpRegex)>0) $_xml=r($_xml,$tmpKey,$tmpRegex);
				$tableFilter->doItemMove();
			}
			unsetr($tableFilter);
			$reTable=VDCSDTML::toConfigTable($_xml);
			VDCSCache::setCache('common.codes.'.$res,$reTable->getArray(),'config');
		}
		return $reTable;
	}
	
	
	
	
	//########################################
	//########################################
	public static function toFilterTXT($re,$decode=false)
	{
		if($decode) $re=htmlspecialchars_decode($re);
		$re=preg_replace('@<script(.*?)</script>@is', '', $re ); 
		$re=preg_replace('@<iframe(.*?)</iframe>@is', '', $re ); 
		$re=preg_replace('@<style(.*?)</style>@is', '', $re ); 
		$re=preg_replace('@<(.*?)>@is', '', $re ); 
	}
	
	
	//########################################
	//########################################
	//输出安全的html
	public static function toHTMLSafe($text, $tags=null)
	{
		$text=trim($text);
		//完全过滤注释
		$text=preg_replace('/<!--?.*-->/','',$text);
		//完全过滤动态代码
		$text=preg_replace('/<\?|\?'.'>/','',$text);
		//完全过滤js
		$text=preg_replace('/<script?.*\/script>/','',$text);
	
		$text=str_replace('[','&#091;',$text);
		$text=str_replace(']','&#093;',$text);
		$text=str_replace('|','&#124;',$text);
		//过滤换行符
		$text=preg_replace('/\r?\n/','',$text);
		//br
		$text=preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
		$text=preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
		//过滤危险的属性，如：过滤on事件lang js
		while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
			$text=str_replace($mat[0],$mat[1],$text);
		}
		while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
			$text=str_replace($mat[0],$mat[1].$mat[3],$text);
		}
		if(empty($tags)){
			$tags='table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
		}
		//允许的HTML标签
		$text=preg_replace('/<('.$tags.')( [^><\[\]]*)>/i','[\1\2]',$text);
		//过滤多余html
		$text=preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
		//过滤合法的html标签
		while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
			$text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
		}
		//转换引号
		while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
			$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
		}
		//过滤错误的单个引号
		while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
			$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
		}
		//转换其它所有不合法的 < >
		$text=str_replace('<','&lt;',$text);
		$text=str_replace('>','&gt;',$text);
		$text=str_replace('"','&quot;',$text);
		 //反转换
		$text=str_replace('[','<',$text);
		$text=str_replace(']','>',$text);
		$text=str_replace('|','"',$text);
		//过滤多余空格
		$text=str_replace('  ',' ',$text);
		return $text;
	}
	
	public static function toUBB($Text)
	{
		$Text=trim($Text);
		//$Text=htmlspecialchars($Text);
		$Text=preg_replace("/\\t/is","  ",$Text);
		$Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
		$Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
		$Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
		$Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
		$Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
		$Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
		$Text=preg_replace("/\[separator\]/is","",$Text);
		$Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$Text);
		$Text=preg_replace("/\[url=http:\/\/([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
		$Text=preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
		$Text=preg_replace("/\[url\]http:\/\/([^\[]*)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\1</a>",$Text);
		$Text=preg_replace("/\[url\]([^\[]*)\[\/url\]/is","<a href=\"\\1\" target=_blank>\\1</a>",$Text);
		$Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text);
		$Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
		$Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text);
		$Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
		$Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
		$Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
		$Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
		$Text=preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis","color_txt('\\1')",$Text);
		$Text=preg_replace("/\[emot\](.+?)\[\/emot\]/eis","emot('\\1')",$Text);
		$Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
		$Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
		$Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
		$Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is"," <div class='quote'><h5>引用:</h5><blockquote>\\1</blockquote></div>", $Text);
		$Text=preg_replace("/\[code\](.+?)\[\/code\]/eis","highlight_code('\\1')", $Text);
		$Text=preg_replace("/\[php\](.+?)\[\/php\]/eis","highlight_code('\\1')", $Text);
		$Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div class='sign'>\\1</div>", $Text);
		$Text=preg_replace("/\\n/is","<br/>",$Text);
		return $Text;
	}
	
	public static function highlightCode($str,$show=false)
	{
		if(is_file($str)){
			$str	=	file_get_contents($str);
		}
		$str=stripslashes(trim($str));
		// The highlight string function encodes and highlights
		// brackets so we need them to start raw
		$str=str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
	
		// Replace any existing PHP tags to temporary markers so they don't accidentally
		// break the string out of PHP, and thus, thwart the highlighting.
	
		$str=str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);
	
		// The highlight_string function requires that the text be surrounded
		// by PHP tags.  Since we don't know if A) the submitted text has PHP tags,
		// or B) whether the PHP tags enclose the entire string, we will add our
		// own PHP tags around the string along with some markers to make replacement easier later
	
		$str='<?php //tempstart'."\n".$str.'//tempend ?>'; // <?
	
		// All the magic happens here, baby!
		$str=highlight_string($str, TRUE);
	
		// Prior to PHP 5, the highlight function used icky font tags
		// so we'll replace them with span tags.
		if (abs(phpversion()) < 5)
		{
			$str=str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
			$str=preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
		}
	
		// Remove our artificially added PHP
		$str=preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
		$str=preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
		$str=preg_replace("#//tempend.+#is", "</span>\n</code>", $str);
	
		// Replace our markers back to PHP tags.
		$str=str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
		$line	=	explode("<br />", rtrim(ltrim($str,'<code>'),'</code>'));
		$result =	'<div class="code"><ol>';
		foreach($line as $key=>$val){
			$result .=  '<li>'.$val.'</li>';
		}
		$result .=  '</ol></div>';
		$result=str_replace("\n", "", $result);
		if( $show!== false){
			echo($result);
		}else{
			return $result;
		}
	}
	
	public static function removeXSS($val)
	{
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
		// this prevents some character re-spacing such as <java\0script>
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
		$val=preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	
		// straight replacements, the user should never need these since they're normal characters
		// this prevents like <IMG SRC=@avascript:alert('XSS')>
		$search='abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';
		for ($i=0; $i < strlen($search); $i++){
			// ;? matches the ;, which is optional
			// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
	
			// @ @ search for the hex values
			$val=preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
			// @ @ 0{0,7} matches '0' zero to seven times
			$val=preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
		}
	
		// now the only remaining whitespace attacks are \t, \n, and \r
		$ra1=array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		$ra2=array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ra=array_merge($ra1, $ra2);
	
		$found=true; // keep replacing as long as the previous round replaced something
		while ($found == true){
			$val_before=$val;
			for ($i=0; $i < sizeof($ra); $i++){
				$pattern='/';
				for ($j=0; $j < strlen($ra[$i]); $j++){
					if ($j > 0){
						$pattern .= '(';
						$pattern .= '(&#[xX]0{0,8}([9ab]);)';
						$pattern .= '|';
						$pattern .= '|(&#0{0,8}([9|10|13]);)';
						$pattern .= ')*';
					}
					$pattern .= $ra[$i][$j];
				}
				$pattern .= '/i';
				$replacement=substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
				$val=preg_replace($pattern, $replacement, $val); // filter out the hex tags
				if ($val_before == $val){
					// no replacements were made, so exit the loop
					$found=false;
				}
			}
		}
		return $val;
	}
	
	
	
	
	/** 
	* 说明：过滤HTML字串 
	* 参数： 
	* $str : 要过滤的HTML字串 
	* $tag : 过滤的标签类型 
	* $keep_attribute : 
	* 要保留的属性,此参数形式可为 
	* href 
	* href,target,alt 
	* array('href','target','alt') 
	*/ 
	public static function HTMLFilter(&$str,$tag,$keep_attribute)
	{
		//检查要保留的属性的参数传递方式
		if(!is_array($keep_attribute)){
			//没有传递数组进来时判断参数是否包含,号
			if(strpos($keep_attribute,',')){
				//包含,号时,切分参数串为数组
				$keep_attribute = explode(',',$keep_attribute);
			}else{
				//纯字串,构造数组
				$keep_attribute = array($keep_attribute);
			}
		}
		
		echo("·过滤[$tag]标签,保留属性:".implode(',',$keep_attribute).'<br>');
		
		//取得所有要处理的标记
		$pattern = "/<$tag(.*)<\/$tag>/i";
		preg_match_all($pattern,$str,$out);
		
		//循环处理每个标记
		foreach($out[1] as $key => $val){
			//取得a标记中有几个=
			$cnt = preg_split('/ *=/i',$val);
			$cnt = count($cnt) -1;
			
			//构造匹配正则
			$pattern = '';
			for($i=1; $i<=$cnt; $i++){
				$pattern .= '( .*=.*)';
			}
			//完成正则表达式形成,如/(<a)( .*=.*)( .*=.*)(>.*<\/a>/i的样式
			$pattern = "/(<$tag)$pattern(>.*<\/$tag>)/i";
			
			//取得保留属性
			$replacement = match($pattern,$out[0][$key],$keep_attribute);
			
			//替换
			$str = str_replace($out[0][$key],$replacement,$str); 
		}
	}
	
	/**
	* 说明：构造标签,保留要保留的属性
	* 参数：$reg : pattern,preg_match的表达式
	* $str : string,html字串
	* $arr : array,要保留的属性
	* 返回：
	* 返回经保留处理后的标签,如
	* <A href='http://www.e.com' target=_blank alt=e e e>e.com</A>
	*/
	public static function HTMLMatch($reg,&$str,$arr)
	{
		//match
		preg_match($reg,$str,$out);
		
		//取出保留的属性
		$keep_attribute = '';
		foreach($arr as $k1=>$v1){
			//定义的要保留的属性的数组
			foreach($out as $k2=>$v2){
				//匹配=后的数组
				$attribute = trim(substr($v2,0,strpos($v2,'=')));
				//=前面的
				if($v1 == $attribute){
					//要保留的属性和匹配的值的=前的部分相同
					$keep_attribute .= $v2;
					//保存此匹配部分的值
				}
			}
			
		}
		
		//构造返回值,结构如:<a href=xxx target=xxx class=xxx>aadd</a>
		$keep_attribute = $out[1].$keep_attribute.($out[count($out)-1]);
		//返回值
		return $keep_attribute;
	}
	
}
?>