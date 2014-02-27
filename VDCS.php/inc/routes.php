<?

global $routes;
$pathinfo=isset($_GET['router'])?$_GET['router']:$_SERVER['PATH_INFO'];
//debugx($_SERVER['PATH_INFO']);

$routes=explode('.',$pathinfo);
define('PAGE_X',$routes[1]);

$routes=explode('/',$routes[0]);
//if(!$routes[1]) $routes[1]='welcome';


//debuga($routes);
//debugx('PAGE_CHN='.PAGE_CHN.', PAGE_P='.PAGE_P.', PAGE_M='.PAGE_M.', PAGE_MI='.PAGE_MI.', PAGE_X='.PAGE_X);


function routeDefine()
{
	global $routes;
	define('PAGE_CHN',$routes[1]);
	//if(!$routes[2]) $routes[2]='index';
	define('PAGE_P',$routes[2]);
	define('PAGE_M',$routes[3]);
	define('PAGE_MI',$routes[4]);
}
