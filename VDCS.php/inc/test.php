<?

//function printx($s){puts($s);}
function printx($s){debugx($s);}

/*
$n=10000;
timerBegin();
for($i=0;$i<$n;$i++){
	//$v=str_replace('345','ab','123456789012345678901234567890');
	//$v=is_long('123.45');
	$v=$_GET['action'];
}
printx(timerExec());
timerBegin();
for($i=0;$i<$n;$i++){
	//$v=r('123456789012345678901234567890','345','ab');
	//$v=isInt('123.45');
	$v=query('action');
}
printx(timerExec());
*/

printx('<a href="?">Test</a> &nbsp; <a href="?action=test123&action2=123.45&value[]=321&value[]=aav">Action</a>');
/**/
printx('query='.query('action'));
printx('query='.query('action'));
printx('query='.query('action'));
printx('query='.query('action'));
printx('queryi='.queryi('action2'));
printx('queryi='.queryi('action2'));
printx('queryi='.queryi('action2'));
printx('queryi='.queryi('action2'));
//printx('querye='.(querye('action2')?'TRUE':'FALSE'));
//printx('query='.query('action'));
printx('query='.query('action2'));
printx('queryi='.queryi('action2'));
printx('queryn='.queryn('action2'));
printx('queryn='.queryn('action2'));
printx('');

/*
printx('len='.len('12345我'));
printx('vi='.vi('12345'));
printx('vn='.vn('12345'));
printx('');

printx('r='.r('123456789012345678901234567890','3451','ab'));

printx('ins='.ins('12345','34'));
printx('insr='.insr('123456789','34'));
printx('ins='.ins('一二三四五','三四'));
printx('inp='.inp('aa,bb,cc','cc'));
printx('inp='.inp('11,22,33','22'));
printx('inps='.inp('aaa=bba=ccc','ccc','='));
printx('inps='.inp('aaa=bba=ccc','ccc'));
printx('');
*/
/*
printx('substri='.substri('abcdefg',3,2));
printx('substri='.substri('abcdefg',3));
printx('toSubstr='.toSubstr('abcdefg',3,2));
printx('toSubstr='.toSubstr('abcdefg',3));
printx('left='.left('abcdefg',3));
printx('lefti='.lefti('abcdefg',3));
printx('right='.right('abcdefg',3));
printx('righti='.righti('abcdefg',3));
printx('');
*/
/*
printx('r='.r('123456789012345678901234567890','345','ab'));
printx('rd='.rd('haha{$action}haha{$action}hehe','action','action value'));
printx('');

printx('substri='.substri('abcdefg',1));
printx('isSecure='.isSecure('123'));
printx('isSecure='.isSecure('ha?ha'));
//printx('isSecure='.(isSecure('ha?ha')?'TRUE':'FALSE'));
printx('');
*/
/*
function isempty($s){return empty($s);}
$abc='11';
printx('gettype='.gettype($abc));
printx('tn='.tn($abc));
unset($abc);
printx('is_null='.(is_null($abc)?'TRUE':'FALSE'));
printx('isn='.(isn($abc)?'TRUE':'FALSE'));
printx('empty='.(isempty($abc)?'TRUE':'FALSE'));
printx('ise='.(ise($abc)?'TRUE':'FALSE'));
$abc=array();
printx('is_array='.(is_array($abc)?'TRUE':'FALSE'));
printx('isa='.(isa($abc)?'TRUE':'FALSE'));
class class_a{}
$abc=new class_a();
printx('is_object='.(is_object($abc)?'TRUE':'FALSE'));
printx('iso='.(iso($abc)?'TRUE':'FALSE'));
printx('');
*/
/*
printx('is_long='.(is_long(123)?'TRUE':'FALSE'));
printx('is_long='.(is_long('123')?'TRUE':'FALSE'));
printx('toInt='.toInt(123.45));
printx('toInt='.toInt('123.45'));
printx('isInt='.(isInt(123)?'TRUE':'FALSE'));
printx('isInt='.(isInt('123')?'TRUE':'FALSE'));
printx('isInt='.(isInt('123.45')?'TRUE':'FALSE'));
printx('isNum='.(isNum('123.45')?'TRUE':'FALSE'));
printx('isNum='.(isNum('test.123')?'TRUE':'FALSE'));
printx('is_numeric='.(is_numeric('123')?'TRUE':'FALSE'));
printx('');
*/
/*
printx('isRealPath='.(isRealPath('root/haha')?'TRUE':'FALSE'));
printx('isRealPath='.(isRealPath('/D://root/haha')?'TRUE':'FALSE'));
printx('urlLink='.urlLink('/view.html','action=abc'));
printx('urlLink='.urlLink('','action=abc'));
printx('');
*/
/*
printx('requests_set='.requests_set('test','test value'));
printx('requests_get='.requests_get('test'));
printx('');
*/

//debugx('charpush='.charpush('12345我','哈哈'));


exit();
