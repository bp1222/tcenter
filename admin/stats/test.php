<?
header("Content-type: image/png");
include("../../inc/connection.inc");

# query to get data
$query = "SELECT name, date, value FROM stats ORDER BY date;";
$result = pg_query($query);

# set width & height of image
$width = 600;
$height = 300;

# number of columns is number of returned items
$columns = pg_num_rows($result);

# the width of each column is the width of image / # of columns
$column_width = floor($width / $columns);

# padding between columns
$padding = min(max($passing,1), $column_width);

# create image
$im = imagecreate ($width, $height);

# set r, g, b values
$r = 0xaf;
$g = 0xaf;
$b = 0xaf;

# set colors
$gray 			= imagecolorallocate($im, $r, $g, $b);
$gray_lite	= imagecolorallocate($im, $r+40, $g+40, $b+40);
$gray_dark	= imagecolorallocate($im, $r-20, $g-20, $b-20);
$white			= imagecolorallocate($im, 0xff, 0xff, 0xff);

# set the background of the image
imagefilledrectangle($im, 0, 0, $width, $height, $white);

$open = array();
$closed = array();
$values = array();

# generate the arrays
while ($row = pg_fetch_assoc($result)) {
	$name = trim($row['name']);
	$date = trim($row['date']);
	$value = trim($row['value']);
	$values = trim($row['value']);

	if ($name == "open") {
		$open["$date"] = $value;
	}
	else {
		$closed["$date"] = $value;
	}

}

$maxv = 0;

$max_height = $height - $column_width;

for( $i=0; $i < $columns; $i++) {
	$maxv = max($values[$i],$maxv);
}

$a = 0;
foreach ($open as $key => $value) {

		# this is the date for the column
    $column_name = $key;

		# the height of the column
		$column_height = ($max_height / 100) * (( $value / $maxv) * 100);

		# set the coordinates of the column
		$x1 = $a * $column_width;
		$y1 = $height - $column_height;
		$x2 = (($a+1) * $column_width) - $padding;
		$y2 = $height;

		# generate the filled rectangle
		imagefilledrectangle($im, $x1, $y1, $x2, $y2, $gray);

		# offset for the 3D part
		$offset = ($column_width - $padding) / 2;

		# array containing coordinates for top of column
		$pt = array($x1, $y1, 
								$x1 + $offset, 
								$y1 - $offset, 
								$x2 + $offset, 
								$y1 - $offset, 
								$x2, $y1);

		# draw the array
		imagefilledpolygon($im, $pt, 4, $gray_lite);

		# generate the side of the column
		$pt = array($x2, $y1,
								$x2 + $offset,
								$y1 - $offset,
								$x2 + $offset,
								$y2 - $offset,
								$x2, $y2);

		# draw the side of the column
		imagefilledpolygon($im, $pt, 4, $gray_dark);
		
		$a++;
}

# generate image
imagepng($im);

?>
