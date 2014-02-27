<?php

/*
Linux/Mac:
sudo php -c /opt/local/etc/php54/php.ini pcli.php router=common/test action=abcd aaa=ccc fdasdf

Windows:
D:\Dev\PHP5.4ts\php.exe -f E:\wwwroot\netlan\miibeian\www\pcli.php router=icp/pai ap=download
*/

require('cli.php');
$_srv=array('SERVER_ADDR'=>'127.0.0.1','SERVER_NAME'=>'miibeian.7x24.dev');
__cliInit($_srv);
//__cliTest();
//die();
require('common/include/config.php');
require(VDCS_WEB_PATH.'Pages.res.php');
webAgent();
