<?
/*
sudo php -c /opt/local/etc/php54/php.ini /var/wwwroot/VDCS/VDCS.php/www/pcli.php router=common/test action=abcd value=test
*/

function __cliInit($cfg)
{
	$srv=array();
	$srv['dir']='/';
    $srv['REQUEST_METHOD']='GET';
	$srv['SERVER_ADDR']='127.0.0.1';
	$srv['SERVER_PORT']='80';
	$srv['SERVER_NAME']='localhost';
	$srv['HTTP_HOST']='';
	if(is_array($cfg)) $srv=array_merge($srv,$cfg);
	if(!$srv['HTTP_HOST']) $srv['HTTP_HOST']=$srv['SERVER_NAME'];

	global $argv,$argc;
	$querya=array();
	for($a=1;$a<$argc;$a++){
		$values=$argv[$a];
		//echo $values."\n";
		array_push($querya,$values);
		$p=strpos($values,'=');
		if($p===false){
			$_GET[$values]='';
		}
		else{
			$_GET[substr($values,0,$p)]=substr($values,$p+1);
		}
	}

    $scriptpath=$_SERVER['SCRIPT_NAME'];
    $p=strrpos($scriptpath,DIRECTORY_SEPARATOR);
    $pathroot=substr($scriptpath,0,$p+1);
    $pathroot=str_replace('\\','/',$pathroot);
    $scriptname=substr($scriptpath,$p+1);

	$_SERVER['SERVER_ADDR']=$srv['SERVER_ADDR'];
	$_SERVER['SERVER_PORT']=$srv['SERVER_PORT'];
	$_SERVER['SERVER_NAME']=$srv['SERVER_NAME'];
	$_SERVER['HTTP_HOST']=$srv['HTTP_HOST'];

	$_SERVER['QUERY_STRING']=implode('&',$querya);
	$_SERVER['REQUEST_METHOD']=$srv['REQUEST_METHOD'];
	$_SERVER['PHP_SELF']=$srv['dir'].$scriptname;
	$_SERVER['SCRIPT_NAME']=$srv['dir'].$scriptname;
	$_SERVER['REQUEST_URI']=$srv['dir'].$scriptname;
	$_SERVER['DOCUMENT_URI']=$srv['dir'].$scriptname;
	$_SERVER['DOCUMENT_ROOT']=substr($pathroot,0,strlen($pathroot)-1);
	$_SERVER['SCRIPT_FILENAME']=$pathroot.$scriptname;
	$_SERVER['PATH_TRANSLATED']=$pathroot.$scriptname;
}
function __cliTest()
{
	print_r($_GET);
	print_r($_SERVER);
}
function is_cli_request(){return (php_sapi_name() === 'cli' OR defined('STDIN'));}

/*
Array
(
    [USER] => _www
    [HOME] => /Library/WebServer
    [FCGI_ROLE] => RESPONDER
    [QUERY_STRING] => 
    [REQUEST_METHOD] => GET
    [CONTENT_TYPE] => 
    [CONTENT_LENGTH] => 
    [SCRIPT_NAME] => /test.php
    [REQUEST_URI] => /test.php
    [DOCUMENT_URI] => /test.php
    [DOCUMENT_ROOT] => /var/wwwroot/VDCS/VDCS.php/www
    [SERVER_PROTOCOL] => HTTP/1.1
    [GATEWAY_INTERFACE] => CGI/1.1
    [SERVER_SOFTWARE] => nginx/1.4.1
    [REMOTE_ADDR] => 127.0.0.1
    [REMOTE_PORT] => 52607
    [SERVER_ADDR] => 127.0.0.1
    [SERVER_PORT] => 80
    [SERVER_NAME] => php.vdcs.cc
    [REDIRECT_STATUS] => 200
    [SCRIPT_FILENAME] => /var/wwwroot/VDCS/VDCS.php/www/test.php
    [PHP_SELF] => /test.php
    [PATH_INFO] => 
    [PATH_TRANSLATED] => /var/wwwroot/VDCS/VDCS.php/www/test.php
    [HTTP_HOST] => php.vdcs.cc
    [HTTP_CONNECTION] => keep-alive
    [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,* / *;q=0.8
    [HTTP_USER_AGENT] => Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36
    [HTTP_ACCEPT_ENCODING] => gzip,deflate,sdch
    [HTTP_ACCEPT_LANGUAGE] => zh-CN,zh;q=0.8
    [HTTP_COOKIE] => expires=1420585474; user=remember%3Dyes%26id%3D10001%26password%3D4621d373cade4e83; PHPSESSID=hgturt5q9skl4091df6dlr48l4
    [REQUEST_TIME_FLOAT] => 1389254241.2625
    [REQUEST_TIME] => 1389254241
)
Array
(
    [TERM_PROGRAM] => Apple_Terminal
    [SHELL] => /bin/bash
    [TERM] => xterm-256color
    [TMPDIR] => /var/folders/8m/cwgbjbfx7dd5vmgfqd7m59xm0000gn/T/
    [Apple_PubSub_Socket_Render] => /tmp/launch-e80VVo/Render
    [TERM_PROGRAM_VERSION] => 326
    [TERM_SESSION_ID] => F4CE6284-CD90-4E87-827C-AC745425636C
    [USER] => Ranom
    [SSH_AUTH_SOCK] => /tmp/launch-LYqLCt/Listeners
    [__CF_USER_TEXT_ENCODING] => 0x1F5:25:52
    [PATH] => /opt/local/bin:/opt/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin
    [__CHECKFIX1436934] => 1
    [PWD] => /var/wwwroot/VDCS/VDCS.php/www
    [LANG] => zh_CN.UTF-8
    [PS1] => [\u@\h \W]$ 
    [SHLVL] => 1
    [HOME] => /Users/Ranom
    [LOGNAME] => Ranom
    [SECURITYSESSIONID] => 186a4
    [_] => /usr/bin/php
    [OLDPWD] => /var/wwwroot/VDCS/VDCS.php
    [PHP_SELF] => test.php
    [SCRIPT_NAME] => test.php
    [SCRIPT_FILENAME] => test.php
    [PATH_TRANSLATED] => test.php
    [DOCUMENT_ROOT] => 
    [REQUEST_TIME_FLOAT] => 1389254249.403
    [REQUEST_TIME] => 1389254249
    [argv] => Array
        (
            [0] => test.php
            [1] => action=abc
        )

    [argc] => 2
)
*/

