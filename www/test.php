<?php
require('common/include/configure.php');

debugs('Query String:');
debugs($_SERVER['QUERY_STRING']);

debugs('');

debugs('Script Name:');		//PHP_SELF,DOCUMENT_URI
debugs($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO']);

debugs('');

debugs('Request URI:');		//PHP_SELF
debugs($_SERVER['REQUEST_URI']);

debugs('');

phpinfo();
//if(query('phpinfo')) phpinfo();


dcsEnd();
