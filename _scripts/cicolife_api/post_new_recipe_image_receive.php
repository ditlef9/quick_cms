<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
}

/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_recipes		= $mysqlPrefixSav . "recipes";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";


/*- Variables ------------------------------------------------------------------------- */
$fm = "";

// Path
$year = date("Y");
$month = date("m");

if(!(is_dir("../../_uploads/recipes"))){
	mkdir("../../_uploads/recipes");
}
if(!(is_dir("../../_uploads/recipes/_image_uploads"))){
	mkdir("../../_uploads/recipes/_image_uploads");
}

if(isset($_POST['inp_recipe_id']) && isset($_POST['inp_category_id']) && isset($_POST['inp_user_id']) && isset($_POST['inp_image_base'])){
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = output_html($inp_recipe_id);
	if(empty($inp_recipe_id)){
		echo"Empty recipe_id";
	}
	else{
		$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

		// Check if it alreaddy exists
		$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_recipe_id) = $row;

		if($get_recipe_id == ""){
			echo"Recipe not found";
		}
		else{
			// Category
			$inp_category_id = $_POST['inp_category_id'];
			$inp_category_id = output_html($inp_category_id);

			// User
			$inp_user_id = $_POST['inp_user_id'];
			$inp_user_id = output_html($inp_user_id);

			// Upload dir
			$upload_path = "../../_uploads/recipes/_image_uploads/$inp_category_id";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}
			$upload_path = "../../_uploads/recipes/_image_uploads/$inp_category_id/$inp_user_id";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}
			$upload_path = "../../_uploads/recipes/_image_uploads/$inp_category_id/$inp_user_id/$year";
			if(!(is_dir("$upload_path"))){
				mkdir("$upload_path");
			}


			// IP
			$inp_ip = $_SERVER['REMOTE_ADDR'];
			$inp_ip = output_html($inp_ip);
			$inp_ip_mysql = quote_smart($link, $inp_ip);

			// Upload image
			$img_code = $_POST['inp_image_base'];
			$img_data = base64_decode($img_code);
			$image_path = $upload_path . "/" . $get_recipe_id . "-" . $inp_ip . ".jpg";
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
					$image_final_path = $upload_path . "/" . $get_recipe_id . "-" . $inp_ip . ".png";

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
					$inp_recipe_image_path = str_replace("../../", "", $upload_path);
					$inp_recipe_image_path = output_html($inp_recipe_image_path);
					$inp_recipe_image_path_mysql = quote_smart($link, $inp_recipe_image_path);

					$inp_recipe_image = $get_recipe_id . "-" . $inp_ip . ".png";
					$inp_recipe_image = output_html($inp_recipe_image);
					$inp_recipe_image_mysql = quote_smart($link, $inp_recipe_image);

					$inp_recipe_thumb = $get_recipe_id . "-thumb-" . $inp_ip . ".png";
					$inp_recipe_thumb = output_html($inp_recipe_thumb);
					$inp_recipe_thumb_mysql = quote_smart($link, $inp_recipe_thumb);

						
					// Update recipe
					mysqli_query($link, "UPDATE $t_recipes SET recipe_image_path=$inp_recipe_image_path_mysql, recipe_image=$inp_recipe_image_mysql, recipe_thumb=$inp_recipe_thumb_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));

					
					// Thumb 300 x 200
					$width = $newwidth;
					$height = $newheight;

					$thumb_final_path = $upload_path . "/" . $get_recipe_id . "-thumb-" . $inp_ip . ".png";
					$newwidth=300;
					$newheight=200; // ($height/$width)*$newwidth
					$tmp=imagecreatetruecolor($newwidth,$newheight);
					$src = imagecreatefrompng($image_final_path);
					imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
					imagepng($tmp, $thumb_final_path);
					imagedestroy($tmp);




					// For user
					$image_path_saying = str_replace("../../", "", $image_final_path);
					echo"$image_path_saying";



				}
			}
			else{
				echo"Image doesnt exists on server";
			}
		} // recipe_id found mysql
	} // empty recipe_id

}
else{
	echo"Missing recipe_id OR inp_image_base";
}

?>