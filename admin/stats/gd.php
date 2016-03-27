<?php

// This part of the code is the same as the other 
// graph example

$values = array("23","32","38","57","12",
                "4","36","54","32","15",
                "43","44","30");

$columns  = count($values);
$width = 200;
$height = 100;

$column_width = floor($width / $columns) ;

$padding = min(max($padding,1),$column_width);

$im        = imagecreate($width,$height);

$r         = 0xaf;
$g         = 0xaf;
$b         = 0xaf;

// Create the colors.

$gray      = imagecolorallocate ($im,$r,$g,$b);
$gray_lite = imagecolorallocate ($im,$r+40,$g+40,$b+40);
$gray_dark = imagecolorallocate ($im,$r-20,$g-20,$b-20);
$white     = imagecolorallocate ($im,0xff,0xff,0xff);
    
imagefilledrectangle($im,0,0,$width,$height,$white);

$maxv = 0;

// The first change - we need to reduce the maximum height
// of the columns to allow for the vertical effect.

$max_height=$height - $column_width;

for($i=0;$i<$columns;$i++)$maxv = max($values[$i],$maxv);
        
for($i=0;$i<$columns;$i++)
{
    $column_height = ($max_height / 100) * (( $values[$i] / $maxv) *100);

    $x1 = $i*$column_width;
    $y1 = $height-$column_height;
    $x2 = (($i+1)*$column_width)-$padding;
    $y2 = $height;

// Draw the main columm

    imagefilledrectangle($im,$x1,$y1,$x2,$y2,$gray);

// This is the offset for the 3D angle

    $offset = ($column_width-$padding) / 2;

// Create an array for the top part of the column

    $pt = array($x1,$y1,
                $x1+$offset,$y1-$offset,
                $x2+$offset,$y1-$offset,
                $x2,$y1);

// Now draw it.

    imagefilledpolygon($im,$pt,4,$gray_lite);

// Create the side of the column

    $pt = array($x2,$y1,
                $x2+$offset,$y1-$offset,
                $x2+$offset,$y2-$offset,
                $x2,$y2);

// And draw that part too

    imagefilledpolygon($im,$pt,4,$gray_dark);

}

// return the image using imagepng or imagejpeg.

	imagepng($im);

?> 
