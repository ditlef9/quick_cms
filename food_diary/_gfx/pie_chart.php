<?php
/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['numbers'])) {
	$numbers = $_GET['numbers'];
	$numbers = strip_tags(stripslashes($numbers));
}
else{
	$numbers = "";
}

// width and height of the image
$width=80;
$height=80;

$simulate_old_gd=false; // do not use imagefilledarc although available?

// the pieces of the pie (in degree)
$pieces = explode(",", $numbers);
$degrees = array();
$y = 0;
for($x=0;$x<sizeof($pieces);$x++){
	$degree = ceil($pieces[$x]*(360/100));
	if($degree != "0"){
		$degrees[$y] = "$degree";
		$y++;
	}
}
$diagram=imagecreate($width,$height);

// background color
//$white=imagecolorallocate($diagram, 255, 255, 255);
$grey=imagecolorallocate($diagram, 251, 251, 251);
imagefilledrectangle($diagram,0,0,$width,$height,$grey);

// the circle is 2px smaller than the image
$width-=2;
$height-=2;

// we need a border color
$black=imagecolorallocate($diagram, 0, 0, 0);
$dark_grey = imagecolorallocate($diagram, 216, 216, 216);

// draw the border of the pie
imagearc($diagram, round($width/2), round($height/2), $width, $height, 0, 360, $dark_grey);

// position (in degrees) where to place the next piece
$position=270;

// we will use calculated gray colors for simple example
$color_counter = 0;
foreach($degrees as $deg){

	// calculate the color
	if($color_counter == 0){
		$color = imagecolorallocate($diagram,153,188,88); // Green
	}
	elseif($color_counter == 1){
		$color = imagecolorallocate($diagram,192,80,76); // Red
	}
	elseif($color_counter == 2){
		$color = imagecolorallocate($diagram,79,129,188); // Blue
	}
	elseif($color_counter == 3){
		$color = imagecolorallocate($diagram,159,167,30); // Yellow
	}
	$color_counter++;

	// position must be kept < 360
	if($position>360) $position-=360;

	if(!$simulate_old_gd && is_callable('imagefilledarc')){ 
  		imagefilledarc($diagram, round($width/2),
  		round($height/2), $width, $height, $position,
  		$position+$deg, $color,IMG_ARC_EDGED);
  	}
	else{
		// we use some maths to calculate the pixel on the circle
		$pix_x=round(floor(($width-2)/2)*cos($position/180*M_PI) +round($width/2));
		$pix_y=round(floor(($height-2)/2)*sin($position/180*M_PI) +round($height/2));
  
		// now we  draw a line from the mid of the circle to the
  		// calculated pixel on the circle
  		imageline($diagram, round($width/2), round($height/2), $pix_x, $pix_y, $black);
  
		// now we need a pixel for flood filling.
  		//- We could use maths to calculate a pixel inside the
  		// piece:
  		//$fill_x=round(floor(($width-10)/2)*cos(($position+2)/180*M_PI)+round($width/2));
  		//$fill_y=round(floor(($height-10)/2)*sin(($position+2)/180*M_PI)+round($height/2));
  		//- or we could use an universal pixel with less maths ;) 
  		// (top mid):
  		$fill_x=floor($width/2)-2;
 		$fill_y=3;

  		// now we flood fill the circle
  		@imagefilltoborder($diagram,$fill_x,$fill_y,$black,$color);
	}
	// the position of the next piece is $deg degrees further
	$position+=$deg;
}
// output the image
header('Content-type: image/png');
imagepng($diagram);
imagedestroy($digram);
?>