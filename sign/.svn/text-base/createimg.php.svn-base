<HTML>
<HEAD>

</HEAD>
<BODY>
<?php

$aConn = new COM("SIGPLUS.SigPlusCtrl.1");
$aConn->InitSigPlus();
$aConn->SigCompressionMode = 1;
$aConn->SigString="$_REQUEST[SigField]";
$aConn->ImageFileFormat = 4; //4=jpg, 0=bmp, 6=tif
$aConn->ImageXSize = 500; //width of resuting image in pixels
$aConn->ImageYSize =165; //height of resulting image in pixels
$aConn->ImagePenWidth = 11; //thickness of ink in pixels
$aConn->JustifyMode = 5;  //center and fit signature to size
$aConn->WriteImageFile("C:\\test.jpg");

?>

</BODY>
</HTML>