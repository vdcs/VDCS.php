<?
global $_cfg;
//debugx('MANAGE_CHANNEL_NOW='.MANAGE_CHANNEL_NOW.';PAGE_P='.PAGE_P.';PAGE_M='.PAGE_M.';PAGE_MI='.PAGE_MI.';PAGE_I='.PAGE_I.';PAGE_X='.PAGE_X);
$_cfg['entry']['filepath']=ManageCommon::entryPath(MANAGE_CHANNEL_NOW,PAGE_P,PAGE_M,PAGE_MI,PAGE_I,PAGE_X);
$_cfg['entry']['file']=$_cfg['entry']['filepath'];
//debugx('--'.$_cfg['entry']['filepath']);
$_cfg['entry']['_class']=false;
if(!isFile($_cfg['entry']['filepath'])){
	$className='Portal'.ucfirst(PAGE_CHN).ucfirst(PAGE_P).ucfirst(PAGE_M).ucfirst(PAGE_MI).ucfirst(PAGE_I).ucfirst(PAGE_X);
	//debugx($className);
	//if(class_exists($className,false)){
	$file=$className.EXT_SCRIPT;
	//if(isFile($_cfg['sys.path']['vdcs.mchannel'].$file) || isFile($_cfg['sys.path']['vdcs.mchannel'].MANAGE_CHANNEL_NOW.'/'.$file) || isFile(rd($_cfg['sys.path']['manage.channel'],'channel',MANAGE_CHANNEL_NOW).$file)){
	if(isFile(mautoload_path($file))){
		//debugx($file);
		define('APP_OBJECTPATH',$file);
		eval('class PagePortal extends '.$className.'{}');
		$_cfg['entry']['_class']=true;
	}
}
if(!$_cfg['entry']['_class']){
	if(!isFile($_cfg['entry']['filepath'])){
		$_cfg['entry']['type']='noentry';
		$_cfg['entry']['file']=r($_cfg['entry']['filepath'],ManageCommon::getPath(),_BASE_DIR_ROOT.ManageCommon::NAME().'/');
		$_cfg['entry']['filepath']='';
		if(PAGE_X){
			class PagePortal extends PageMessagePortalX{}
		}
		else{
			class PagePortal extends PageMessagePortal{}
		}
	}
	if($_cfg['entry']['filepath']){
		define('APP_OBJECTPATH',$_cfg['entry']['filepath']);
		//debugx('=='.$_cfg['entry']['filepath']);
		include($_cfg['entry']['filepath']);
	}
}
