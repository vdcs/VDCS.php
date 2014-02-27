<?php
if(!$vcodeword) $vcodeword='0000';

require(VDCS_INC_PATH.'external/securimg/Securimg.php');

$simg = new Securimg();
$simg->setCode($vcodeword);
//$simg->setCode('test');

$simg->outputAudioFile();
unset($simg);
?>