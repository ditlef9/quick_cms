<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../../_admin/_functions/output_html.php");
include("../../../_admin/_functions/clean.php");
include("../../../_admin/_functions/quote_smart.php");


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_recipes		= $mysqlPrefixSav . "recipes";


/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}


/*- Variables ------------------------------------------------------------------------- */
$fm = "";


if(isset($_POST['inp_recipe_id'])){
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = output_html($inp_recipe_id);
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	// Check if it exists
	$query = "SELECT recipe_id, recipe_image_path, recipe_image, recipe_thumb, recipe_password FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_password) = $row;

	if($get_recipe_id == ""){
		$fm = "Recipe not found";
	}
	else{
		
		if(isset($_POST['inp_recipe_password'])){
			$inp_recipe_password = $_POST['inp_recipe_password'];
			$inp_recipe_password = output_html($inp_recipe_password);

			if($inp_recipe_password == "$get_recipe_password"){

			}
			else{
				echo"Wrong recipe password";
				die;
			}
		}
		else{
			echo"Missing recipe_password";
			die;
		}
	}
}
else{
	echo"Missing recipe_id";
	die;
}
// Path
$year = date("Y");
$month = date("m");

if(!(is_dir("../../../recipes"))){
	mkdir("../../../recipes");
}
if(!(is_dir("../../../recipes/_image_uploads"))){
	mkdir("../../../recipes/_image_uploads");
}


// Random id
$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
shuffle($seed); // probably optional since array_is randomized; this may be redundant
$random_string = '';
foreach (array_rand($seed, 2) as $k) $random_string .= $seed[$k];


// IP
$inp_ip = $_SERVER['REMOTE_ADDR'];
$inp_ip = output_html($inp_ip);
$inp_ip_mysql = quote_smart($link, $inp_ip);

// Find image to rotate
if(file_exists("../../../$get_recipe_image_path/$get_recipe_image") && $get_recipe_image != ""){
	// Roate it 
	$image_final_path = "../../../" . $get_recipe_image_path . "/" . $get_recipe_id . "-" . $random_string . "." . $inp_ip . ".png";

	// Load
	$source = imagecreatefrompng("../../../$get_recipe_image_path/$get_recipe_image");
	$original_x = imagesx($source);
	$original_y = imagesy($source);

	$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
   
	// Rotate
   	$rotate = imagerotate($source, 270, $bgColor);
   	imagesavealpha($rotate, true);
   	imagepng($rotate, $image_final_path);



	// Free memory
	imagedestroy($source);
	imagedestroy($rotate); 

	// Delete old image
	unlink("../../../$get_recipe_image_path/$get_recipe_image");

	// Update
	$inp_recipe_image = $get_recipe_id . "-" . $random_string . "." . $inp_ip . ".png";
	$inp_recipe_image_mysql = quote_smart($link, $inp_recipe_image);
	mysqli_query($link, "UPDATE $t_recipes SET recipe_image=$inp_recipe_image_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));

	echo"$get_recipe_image_path/$inp_recipe_image";



	// Delete old thumb
	if(file_exists("../../../$get_recipe_image_path/$get_recipe_thumb") && $get_recipe_thumb != ""){
		unlink("../../../$get_recipe_image_path/$get_recipe_thumb");
	}

	
	// Thumb 300 x 200
	$thumb_final_path = "../../../" . $get_recipe_image_path . "/" . $get_recipe_id . "-thumb-" . $random_string . "." . $inp_ip . ".png";
	resize_crop_image(300, 200, $image_final_path, $thumb_final_path);

	// Update
	$inp_recipe_thumb = $get_recipe_id . "-thumb-" . $random_string . "." . $inp_ip . ".png";
	$inp_recipe_thumb_mysql = quote_smart($link, $inp_recipe_thumb);
	mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb=$inp_recipe_thumb_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));

	
}
else{
	echo"Image not found: ../../../$get_recipe_image_path/$get_recipe_image";
}



//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];
 
    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
     
    $image($dst_img, $dst_dir, $quality);
 
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}

?>