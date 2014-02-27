<?
if(!$vcodeword) $vcodeword='0000';

Header("Content-type: image/JPEG");

$imtxt_x=50;
$imtxt_y=22;
$image_width=50;
$image_height=22;
$image_x=8;
$image_y=3;

$imtxt=imagecreatetruecolor($imtxt_x,$imtxt_y);		//imagecreate($imtxt_x,$imtxt_y);
$im =imagecreatetruecolor($image_width,$image_height);	//imagecreate($image_width,$image_height);

$clr_white =ImageColorAllocate($imtxt,255,255,255);
$clr_black =ImageColorAllocate($imtxt,0,0,0);
$clr_grey  =ImageColorAllocate($imtxt,210,210,210);

imagefill($imtxt,0,0,$clr_white);

imagestring($imtxt,5,$image_x,$image_y,$vcodeword,$clr_black);
imagecopyresized($im,$imtxt,0,0,0,0,$image_width,$image_height,$imtxt_x,$imtxt_y);
imagedestroy($imtxt);

$pixels=$image_width*$image_height/10;
for ($i=0;$i<$pixels;$i++)
{
	ImageSetPixel($im,rand(0,$image_width),rand(0,$image_height),ImageColorAllocate($im,rand(0,255),rand(0,255),rand(0,255)));
}

$lines_x=($image_width-1)/4;
for ($i=0;$i<=$lines_x;$i++)
{
	ImageLine($im,$i*$lines_x,0,$i*$lines_x,$image_height,ImageColorAllocate($im,rand(0,255),rand(0,255),rand(0,255)));
}
$lines_y=($image_height-1)/2;
for ($i=0;$i<=$lines_y;$i++)
{
	ImageLine($im,0,$i*$lines_y,$image_width,$i*$lines_y,ImageColorAllocate($im,rand(0,255),rand(0,255),rand(0,255)));
}

ImageJPEG($im);
ImageDestroy($im);
?>