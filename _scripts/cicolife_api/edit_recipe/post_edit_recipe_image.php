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
	$query = "SELECT recipe_id, recipe_password FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_password) = $row;

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

if(!(is_dir("../../../_uploads"))){
	mkdir("../../../_uploads");
}
if(!(is_dir("../../../_uploads/recipes/_image_uploads"))){
	mkdir("../../../_uploads/recipes/_image_uploads");
}


	
if(!(isset($_POST['inp_category_id']))){
	echo"Missing inp_category_id";
	die;
}

if(!(isset($_POST['inp_user_id']))){
	echo"Missing inp_user_id";
	die;
}

if(!(isset($_POST['inp_image_base']))){
	echo"Missing inp_image_base'";
	die;
}


$inp_recipe_id = $_POST['inp_recipe_id'];
$inp_recipe_id = output_html($inp_recipe_id);
if(empty($inp_recipe_id)){
	echo"Empty recipe_id";
}
else{
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	// Check if it alreaddy exists
	$query = "SELECT recipe_id, recipe_image_path, recipe_image, recipe_thumb FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb) = $row;

	if($get_recipe_id == ""){
		echo"Recipe not found";
	}
	else{
		// Delete old image and thumb
		if(file_exists("$get_recipe_image_path/$get_recipe_image") && $get_recipe_image != ""){
			unlink("$get_recipe_image_path/$get_recipe_image");
		}
		if(file_exists("$get_recipe_image_path/$get_recipe_thumb") && $get_recipe_thumb != ""){
			unlink("$get_recipe_image_path/$get_recipe_thumb");
		}
	
		// Category
		$inp_category_id = $_POST['inp_category_id'];
		$inp_category_id = output_html($inp_category_id);

		// User
		$inp_user_id = $_POST['inp_user_id'];
		$inp_user_id = output_html($inp_user_id);
		if($inp_user_id == ""){
			echo"Missing user_id";
		}
		else{

			// Upload dir
			$upload_path = "../../../_uploads/recipes/_image_uploads/$inp_category_id";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}
			$upload_path = "../../../_uploads/recipes/_image_uploads/$inp_category_id/$inp_user_id";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}
			$upload_path = "../../../_uploads/recipes/_image_uploads/$inp_category_id/$inp_user_id/$year";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}

			// Random id
			$datetime = date("ymdhis");

			// IP
			$inp_ip = $_SERVER['REMOTE_ADDR'];
			$inp_ip = output_html($inp_ip);
			$inp_ip_mysql = quote_smart($link, $inp_ip);

			// Upload image
			$img_code = $_POST['inp_image_base'];
			$img_data = base64_decode($img_code);
			$image_path = $upload_path . "/" . $get_recipe_id . "-" . $datetime . "." . $inp_ip . ".jpg";
			$extension = getExtension($image_path);
			file_put_contents($image_path,$img_data);

			// Check image
			if(file_exists("$image_path")){
				// Image size
				list($width,$height) = getimagesize($image_path);
	
				if($width == "" OR $height == ""){
					echo"getimagesize_failed";
					unlink("$image_path");
				}
				else{	
					$image_final_path = $upload_path . "/" . $get_recipe_id . "-" . $datetime . "." . $inp_ip . ".png";

					// Image should be 1000 x 667
					$newwidth=1000;
					$newheight=($height/$width)*$newwidth; // 667
					$tmp=imagecreatetruecolor($newwidth,$newheight);
						
					if($extension == "jpg" || $extension == "jpeg" ){
						$src = imagecreatefromjpeg($image_path);
					}
					else{
						$src = imagecreatefrompng($image_path);
					}

					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
					imagepng($tmp, $image_final_path);
					imagedestroy($tmp);
					
					// Delete old path
					if($image_path != "$image_final_path"){
						unlink("$image_path");
					}

					// Update Recipes
					$inp_recipe_image_path = str_replace("../../../", "", $upload_path);
					$inp_recipe_image_path = output_html($inp_recipe_image_path);
					$inp_recipe_image_path_mysql = quote_smart($link, $inp_recipe_image_path);

					$inp_recipe_image = $get_recipe_id . "-" . $datetime . "." . $inp_ip . ".png";
					$inp_recipe_image = output_html($inp_recipe_image);
					$inp_recipe_image_mysql = quote_smart($link, $inp_recipe_image);

					$inp_recipe_thumb = $get_recipe_id . "-thumb-" . $datetime . "." . $inp_ip . ".png";
					$inp_recipe_thumb = output_html($inp_recipe_thumb);
					$inp_recipe_thumb_mysql = quote_smart($link, $inp_recipe_thumb);

						
					// Update recipe
					mysqli_query($link, "UPDATE $t_recipes SET recipe_image_path=$inp_recipe_image_path_mysql, recipe_image=$inp_recipe_image_mysql, recipe_thumb=$inp_recipe_thumb_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));

				
					// Thumb 300 x 200
					$thumb_final_path = $upload_path . "/" . $get_recipe_id . "-thumb-" . $datetime . "." . $inp_ip . ".png";
					resize_crop_image(300, 200, $image_final_path, $thumb_final_path);
				

					// For user
					$image_path_saying = str_replace("../../../", "", $image_final_path);
					echo"$image_path_saying";

					// Index
					$fh = fopen("$upload_path/index.php", "w") or die("can not open file");
					fwrite($fh, "Access denied");
					fclose($fh);
				}
			}
			else{
				echo"Image doesnt exists on server";
			}
		} // user_id
	} // recipe_id found mysql
} // empty recipe_id
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