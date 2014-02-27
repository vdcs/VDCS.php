<?
global $routes;
$pathinfo=isset($_GET['router'])?$_GET['router']:$_SERVER['PATH_INFO'];
$routes=explode('.',$pathinfo);
define('PAGE_X',$routes[1]);
$routes=explode('/',$routes[0]);
//debuga($routes);
if(!$routes[1]) $routes[1]='welcome';
switch($routes[1]){
	case 'main':
		doManagePage(':main');
		break;
	case 'login':
		doManagePage(':login');
		break;
	case 'loginx':
		doManagePage(':loginx');
		break;
	case 'welcome':
	case 'frame':
		if(PAGE_X=='x') doManagePage(':frameX');
		else doManagePage(':frame');
		break;
	case 'res':
		doManagePage(':res');
		break;
	case 'portal':
	default:
		define('PAGE_CHN',$routes[1]);
		//if(!$routes[2]) $routes[2]='index';
		define('PAGE_P',$routes[2]);
		define('PAGE_M',$routes[3]);
		define('PAGE_MI',$routes[4]);
		define('MANAGE_CHANNEL_NOW',PAGE_CHN);
		ManageCommon::loadEntry('_commons','portal');
		doManagePage();
		break;
}
