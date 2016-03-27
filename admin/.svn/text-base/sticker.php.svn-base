<?
/*
 * sticker.php
 *
 * generate a label
 */

$font_loc=dirname($_SERVER["PATH_TRANSLATED"])."/"."arialbd.ttf";

$t = $_REQUEST['ticket'];
header("Content-type: image/png");
$im = @imagecreate(171, 60) or die("Cannot Initialize new GD image stream");
$bc = imagecolorallocate($im, 255, 255, 255);
$tc = imagecolorallocate($im, 0, 0, 0);
imagettftext($im, 18, 0, 2, 40, $tc, $font_loc, $t);
imagepng($im);
imagedestroy($im);

?>
