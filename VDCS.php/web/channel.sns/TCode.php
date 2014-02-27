<?
class TCode
{
	const MAX_MESSAGE		= 5000;		//140
	const MAX_SUMMARY		= 142;
	const MAX_SUMMARYS		= 68;		//文章summarys最多字数
	
	
	public static function toCut($re,$len,$smb='')
	{
		return utilCode::toCutt($re,$len,$smb);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function isSummaryMore($content)
	{
		
	}
	
	public static function toTransMessage($content)
	{
		$re=$content;
		$re=self::toFilterTag($re);
		$re=self::toFilterTags($re);
		$re=utilCode::toHTMLTag($re,1);
		$re=self::toCut($re,self::MAX_SUMMARY);
		$re=trim($re);
		$re=VDCSCodes::toCodes($re,1);
		return $re;
	}
	public static function toTransSummary($content,&$more=1)
	{
		$re=$content;
		$re=self::toFilterTag($re);
		$pattern=['<img[^>]+src\s*=\s*"?([^>"\s]+)"?[^>]*>'];
		$iseffect=utilRegex::isMatch($re,$pattern);
		$re=self::toFilterTags($re);
		$re=utilCode::toHTMLTag($re,1);
		if(!$iseffect && len($re)<self::MAX_SUMMARYS+20){
			$more=0;
			$re=$content;
		}
		else{
			$re=self::toCut($re,self::MAX_SUMMARYS);
			$re=trim($re);
			$re=VDCSCodes::toCodes($re,1);
			$re.='...';
		}
		return $re;
	}
	
	public static function toFilterTag($re)
	{
		//emotes
		$pattern='<img[^>]+src\s*=\s*"?\/images\/emotes\/([^>"\s]+)\/([^>"\s]+)\.gif"?[^>]*>';
		$_matches=utilRegex::toMatches($re,$pattern);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$type=$_matches[1][$m];
			$sn=$_matches[2][$m];
			$emt='';
			switch($type){
				case 'cool':		$emt='c';break;
				case 'em':
				default:		$emt='';break;
			}
			$values='[em'.$emt.$sn.']';
			$re=r($re,$_matches[0][$m],$values);
		}
		$re=str_replace([
				'<br>','<br/>','<br />','<p>','</p>'
			],[
				'[br]','[br]','[br]','','[br]'
			],$re);
		//debugvc($re);
		return $re;
	}
	public static function toFilterTags($re)
	{
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toTransContent($content)
	{
		$content=TAt::trans($content);

		$content=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i", '<a href="\\1\\3" target="_blank">\\1\\3</a>', ' '.$content);

		$content=trim($content);
		return $content;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toTransMedia($tData,$content)
	{
		$mediasAry=array();
		$pic=$tData->getItem('pic');
		if($pic){
			$spic=$pic;
			$type='pic';
			$picAry=[$type,$pic];
			array_push($mediasAry,$picAry);
		}
		$pics=$tData->getItem('pics');
		if($pics) array_push($mediasAry,$pics);
		$medias=$tData->getItem('medias');
		if($medias) array_push($mediasAry,$medias);
		return self::toTransPics($content,$mediasAry);
	}
	
	public static function toTransMediaVideo($tData,$content)
	{
		$mediasAry=array();
		$pic=$tData->getItem('video');
		if($pic){
			$spic=$pic;
			$type='video';
			$picAry=[$type,$pic];
			array_push($mediasAry,$picAry);
		}
		return self::toTransPics($content,$mediasAry);
	}
	public static function toTransPics($content,&$mediasAry=null)
	{
		$tablePic=isTable($content)?$content:self::toFilterPic($content);
		$re='';
		if(!$mediasAry) $mediasAry=array();
		if($tablePic->getRow()>0){
			$nimg=0;$nvideo=0;$nmusic=0;
			$tablePic->doBegin();
			for($t=0;$t<$tablePic->getRow();$t++){
				$type=$tablePic->getItemValue('type');
				switch($type){
					case 'img':
						if($nimg>=3) continue;$nimg++;
						break;
					case 'video':
						if($nvideo>=1) continue;$nvideo++;
						break;
					case 'music':
						if($nmusic>=1) continue;$nmusic++;
						break;
				}
				$url=$tablePic->getItemValue('url');
				$title=$tablePic->getItemValue('title');
				$img=$tablePic->getItemValue('img');
				$picAry=[$type,$url,$title,$img];
				array_push($mediasAry,$picAry);
				$tablePic->doItemMove();
			}
		}
		$re=json_encode($mediasAry);
		return $re;
	}
	
	public static function toFilterPic($content)
	{
		$reTable=newTable();
		$reTable->setFields('type,url,title,img,html');
		//img
		$pattern='<img.*src\s*=\s*[\"|\']?\s*([^<>\"\'\s]*)\s*[/]?>';
		$pattern='<img[^>]+src\s*=\s*"?([^>"\s]+)"?[^>]*>';
		$_matches=utilRegex::toMatches($content,$pattern);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$url=$_matches[1][$m];
			if(left($url,15)!='/images/emotes/'){
				$type='img';$title='';$img='';
				$html=$_matches[0][$m];
				$pattern=' class="(video|music)_holder"';
				$aryType=utilRegex::toParsePatternAry($html,$pattern);
				//debuga($aryType);
				if($aryType && $aryType[0]){
					$type=trim($aryType[0]);
					
					$pattern=' url="(.*?)"';
					$aryRes=utilRegex::toParsePatternAry($html,$pattern);
					//debuga($aryRes);
					if($aryRes && $aryRes[0]) $url=trim($aryRes[0]);
					
					$pattern=' title="(.*?)"';
					$aryTitle=utilRegex::toParsePatternAry($html,$pattern);
					//debuga($aryRes);
					if($aryTitle && $aryTitle[0]) $title=trim($aryTitle[0]);
					
					$pattern=' img="(.*?)"';
					$aryImg=utilRegex::toParsePatternAry($html,$pattern);
					//debuga($aryImg);
					if($aryImg && $aryImg[0]) $img=trim($aryImg[0]);
					
				}
				$treeItem=newTree();
				$treeItem->addItem('type',$type);
				$treeItem->addItem('url',$url);
				$treeItem->addItem('title',$title);
				$treeItem->addItem('img',$img);
				$treeItem->addItem('html',$html);
				$reTable->addItem($treeItem);
			}
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function urlT($id)
	{
		$url='/t/'.$id;
		return DCS::url($url);
	}
	
	
}
?>