<?
function doManagePage($className=VDCS_MANAGE_ENTRY_PORTAL,$check=true)
{
	if(!$className) $className=VDCS_MANAGE_ENTRY_PORTAL;
	if(left($className,1)=='@') $className=r(VDCS_MANAGE_ENTRY_PORTAL_CHANNEL,'@@@',ucfirst(toSubstr($className,2)));
	if(left($className,1)==':') $className=r(VDCS_MANAGE_ENTRY_PORTAL_PAGE,':::',ucfirst(toSubstr($className,2)));
	if($check&&!class_exists($className,false)){
		mPagePortalReload($className);
		return;
	}
	//debugx($className);
	define('APP_OBJECTNAME',$className);
	//##########
	//APP_PORTAL_DEFAULT;
	define('PAGE_IS',true);
	/**/
	defined('PAGE_CHN') || define('PAGE_CHN',queryx('cp'));
	defined('PAGE_P') || define('PAGE_P',queryx('p'));
	defined('PAGE_M') || define('PAGE_M',queryx('m'));
	defined('PAGE_MI') || define('PAGE_MI',queryx('mi'));
	defined('PAGE_X') || define('PAGE_X',queryx('x'));
	//##########
	global $mpo;		//,$mpMod,$mpFrame,$mr,$ma,$uu,$ua,$theme;
	$mpo=new $className;
	//$mpo->model=&$mpMod;
	//$mpo->frame=&$mpFrame;
	//$mpo->ruler=&$mr;
	//$mpo->ma=&$ma;
	//$mpo->ua=&$ua;
	$mpo->inite();$mpo->initer();
	$mpo->themeInit();
	//$mpo->initPre();$mpo->init();$mpo->initPos();
	$mpo->inited();$mpo->init();
	$mpo->doAuth();						//身份检测
	$mpo->doInitPre();$mpo->doInit();$mpo->doInitPos();	//页面初始化
	$mpo->doIniter();
	$mpo->doLoadPre();$mpo->doLoad();$mpo->doLoadPos();	//页面载入
	$mpo->ruler->doPopedomCheck();				//权限检测
	if($mpo->ruler->isPopedom()){
		//$mpo->doFrame();				//页面框架
		$mpo->doParsed();				//页面解析
	}
	else{
		$mpo->doMessages('!system','!nopopedom');
		//$mpo->theme->setPage('common');
	}
	$mpo->doThemer();
	$mpo->themeParse();					//模板解析
	if(DEBUG_OUT && $mpo->isPage() && DCS::isLocal()){
		ResMessage::debugi();
	}
	$mpo->doDestroy();					//页面对像消毁
	$mpo->doClear();					//页面清理
	mend();
}
