<?
class WebCommon
{
	
	public static function initChannel($channel=null)	//,$theme=null
	{
		define('APP_CHANNEL',$channel?$channel:self::getScript('channel'));
		//define('APP_THEME',$theme?$theme:APP_CHANNEL);
		$portal=$GLOBALS['_cfg']['channel'][APP_CHANNEL.'.portal'];
		if(!$portal) $portal=APP_CHANNEL;
		defined('APP_PORTAL')		||define('APP_PORTAL',$portal);
	}
	
	public static function getObject($ppx,&$portal=null,$module=null,$channel=APP_CHANNEL)
	{
		if($ppx!=null && $ppx=='_file_') $ppx==self::getScript('filename');
		if($portal=='_file_') $portal=self::getScript('filename');
		if(!$portal) $portal=APP_PORTAL_DEFAULT;
		return 'Channel'.ucfirst($channel).($ppx?ucfirst($ppx):'').($portal?ucfirst($portal):'').($module?ucfirst($module):'');
	}
	public static function getEntry($portal=null)
	{
		if($portal=='_file_') $portal=self::getScript('filename');
		$entry=$portal?('.'.$portal):'';
		return 'entry'.$entry.EXT_SCRIPT;
	}
	
	public static function getScript($key='full')
	{
		static $values;
		$re='';
		if(!isset($values)){
			$_script=$_SERVER['SCRIPT_NAME'];		//PHP_SELF
			$_ary=explode('/',$_script);
			$values['channel']=count($_ary)>2?$_ary[1]:'';
			$values['file']=end($_ary);
			$values['filename']=basename($_script,EXT_SCRIPT);
			$values['full']=$_script;
			//debuga($values);
		}
		return $values[$key];
	}
	
	public static function supportExtend($classname)
	{
		$treeVar=newTree();
		$treeVar->addItem('PAGE_CHN',PAGE_CHN);
		$treeVar->addItem('PAGE_P',PAGE_P);
		$treeVar->addItem('PAGE_M',PAGE_M);
		$treeVar->addItem('PAGE_MI',PAGE_MI);
		$treeVar->addItem('PAGE_X',PAGE_X);
		$treeVar->addItem('classname',$classname);
		$treeVar->addItem('script.request',$_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING']);
		$treeVar->addItem('status','class');
		$treeVar->addItem('message','Class no found.');
		switch(PAGE_X){
			case 'x':
			case 'xml':
				define('DEBUG_TYPE',1);
				pageHeader('xml');
				echo VDCSXCML::toMapXML($treeVar);
				break;
			case 'j':
			case 'json':
				put(json_encode($treeVar->getArray()));
				break;
			case 'e':
				put('Class no found: '.$classname);
				break;
			default:
				put('Class no found: '.$classname);
				break;
		}
		
		unset($treeVar);
	}
	
}
