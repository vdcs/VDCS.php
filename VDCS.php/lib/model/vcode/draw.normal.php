<?php
if(!$vcodeword) $vcodeword='0000';

Header("Content-type: image/PNG");

$image_width=50;
$image_height=22;
$image_x=8;
$image_y=3;

$im=Imagecreate($image_width,$image_height);
$clr_bg=ImageColorAllocate($im,218,218,218);
$clr_font=ImageColorAllocate($im,0,0,0);
$clr_disturb=ImageColorAllocate($im,160,150,131);
$clr_frame=ImageColorAllocate($im,160,150,131);

Imagefill($im,66,30,$clr_bg);
Imagestring($im,5,$image_x,$image_y,$vcodeword,$clr_font);	//将四位整数验证码绘入图片

for($i=0;$i<200;$i++){			//加入干扰象素
	Imagesetpixel($im,rand()%80,rand()%20,$clr_disturb);
}

ImageLine($im,0,0,0,$image_height,$clr_frame);
ImageLine($im,($image_width-1),0,($image_width-1),$image_height,$clr_frame);
ImageLine($im,0,0,$image_width,0,$clr_frame);
ImageLine($im,0,($image_height-1),$image_width,($image_height-1),$clr_frame);

ImagePNG($im);
ImageDestroy($im);
?>