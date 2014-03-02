<?php
require('common/include/configure.php');

debugs('NOW: '.datei());

debugs('Query String:');
debugs($_SERVER['QUERY_STRING']);

debugs('');

debugs('Script Name:');		//PHP_SELF,DOCUMENT_URI
debugs($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO']);

debugs('');

debugs('Request URI:');		//PHP_SELF
debugs($_SERVER['REQUEST_URI']);

debugs('');

if(query('phpinfo')) phpinfo();

$sleep_value=query('sleep');
if($sleep_value){
	$sleep_value=toi($sleep_value);
	if($sleep_value<1) $sleep_value=5;
	timerBegin();
	sleep($sleep_value);
	debugx('sleep over: '.datei().', with time: '.timerExec());
}


dcsEnd();
