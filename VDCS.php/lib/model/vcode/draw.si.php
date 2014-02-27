<?php
if(!$vcodeword) $vcodeword='0000';

require_once(VDCS_INC_PATH.'external/securimg/Securimg.php');

$simg = new Securimg();
$simg->setCode($vcodeword);
//Change some settings
$simg->image_width		= 125;
$simg->image_height		= 40;
$simg->perturbation		= 0.85;
$simg->image_bg_color		= new SecurimgColor('#f6f6f6');
$simg->use_transparent_text	= true;
$simg->text_scale		= 60;
$simg->text_color		= new SecurimgColor('#333');
$simg->text_transparency	= 50; // 100 = completely transparent
$simg->num_lines		= 3;
$simg->line_color		= new SecurimgColor('#eaeaea');
$simg->signature_color		= new SecurimgColor(rand(0, 64), rand(64, 128), rand(128, 255));
$simg->use_wordlist		= true; 

$simg->show('default');
unset($simg);
?>