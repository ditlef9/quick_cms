<?php 
/**
*
* File: recipes/submit_recipe_step_3_directions_upload_image.php
* Version 1.0.0
* Date 21:22 10.01.2021
* Copyright (c) 2021 S. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Tables ------------------------------------------------------------------------ */
$t_recipes_images			= $mysqlPrefixSav . "recipes_images";


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;


	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);

	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql AND recipe_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		if($process == "1"){
		

			// Finnes mappen?
			$year = date("Y");
			$upload_path = "$root/_uploads/recipes/_image_uploads/$get_recipe_category_id/$get_recipe_id";

			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/recipes"))){
				mkdir("$root/_uploads/recipes");
			}
			if(!(is_dir("$root/_uploads/recipes/_image_uploads"))){
				mkdir("$root/_uploads/recipes/_image_uploads");
			}
			if(!(is_dir("$root/_uploads/recipes/_image_uploads/$get_recipe_category_id"))){
				mkdir("$root/_uploads/recipes/_image_uploads/$get_recipe_category_id");
			}
			if(!(is_dir("$root/_uploads/recipes/_image_uploads/$get_recipe_category_id/$get_recipe_id"))){
				mkdir("$root/_uploads/recipes/_image_uploads/$get_recipe_category_id/$get_recipe_id");
			}

			// Upload image
			reset ($_FILES);
			$temp = current($_FILES);

			if(!(isset($temp['name']))){
				header("HTTP/1.1 400 Could not upload.");
				return;
			}

			$file_name = basename($temp['name']);
			$file_exp = explode('.', $file_name); 
			$inp_ext = $file_exp[count($file_exp) -1]; 
			$inp_ext = strtolower($inp_ext);
			$inp_ext = output_html($inp_ext);


			$new_name = str_replace("$inp_ext", "", $file_name);
			$new_name = clean($new_name);
			$new_name = output_html($new_name);
			$new_name = $get_recipe_id . "_" . $new_name . ".$inp_ext";

			$target_path = $upload_path . "/" . $new_name;

			// Sjekk om det er en OK filendelse
			if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
				// Verify extension
				header("HTTP/1.1 400 Invalid extension.");
				return;
			}
			else{
				if(move_uploaded_file($temp['tmp_name'], $target_path)) {
					list($width,$height) = @getimagesize($target_path);
					if($width == "" OR $height == ""){
						unlink($target_path);
						header("HTTP/1.1 400 Invalid extension.");
						return;
					}


					// Get extension
					$inp_ext_mysql = quote_smart($link, $inp_ext);


					// MySQL Image
					$inp_type = "image";
					$inp_type_mysql = quote_smart($link, $inp_type);

					$inp_title = $temp['name'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_file_path = "_uploads/recipes/_image_uploads/$get_recipe_category_id/$get_recipe_id";
					$inp_file_path_mysql = quote_smart($link, $inp_file_path);

					$inp_file_name = output_html($new_name);
					$inp_file_name_mysql = quote_smart($link, $inp_file_name);

					$inp_file_thumb_a = $get_recipe_id . "_" . $new_name . "_thumb_a" . "." . $inp_ext;
					$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

					$inp_file_thumb_b = $get_recipe_id . "_" . $new_name . "_thumb_b" . "." . $inp_ext;
					$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_b);

					$inp_file_thumb_c = $get_recipe_id . "_" . $new_name . "_thumb_c" . "." . $inp_ext;
					$inp_file_thumb_c_mysql = quote_smart($link, $inp_file_thumb_c);

					// Dates
					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");

					// Me
					$query = "SELECT user_id, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;

					// Get my profile image
					$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
					$r = mysqli_query($link, $q);
					$rowb = mysqli_fetch_row($r);
					list($get_my_photo_id, $get_my_photo_destination) = $rowb;


					$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);


					// IP
					$my_ip = "";
					$my_ip = $_SERVER['REMOTE_ADDR'];
					$my_ip = output_html($my_ip);
					$my_ip_mysql = quote_smart($link, $my_ip);

					$my_hostname = "";
					$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$my_hostname = output_html($my_hostname);
					$my_hostname_mysql = quote_smart($link, $my_hostname);

					$my_user_agent = "";
					if($configSiteUseGethostbyaddrSav == "1"){
						$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
					}
					$my_user_agent = output_html($my_user_agent);
					$my_user_agent_mysql = quote_smart($link, $my_user_agent);

				
					mysqli_query($link, "INSERT INTO $t_recipes_images
					(image_id, image_user_id, image_recipe_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments) 
					VALUES 
					(NULL, $my_user_id_mysql, $get_recipe_id, $inp_title_mysql, '', $inp_file_path_mysql, $inp_file_thumb_a_mysql, $inp_file_thumb_b_mysql, $inp_file_thumb_c_mysql, $inp_file_name_mysql, '', '', '$datetime', $my_ip_mysql, '0', '', 0, '', 0, 0, '', '0')")
					or die(mysqli_error($link));



					// Resize image if it is over 1920 in witdth
					if($width > 1921){
						$newwidth=1920;
						// $newheight=($height/$width)*$newwidth; // 667
						$newheight=1080;
						resize_crop_image($newwidth, $newheight, "../$inp_file_path/$inp_file_name", "../$inp_file_path/$inp_file_name");
					}




					// Respond to the successful upload with JSON.
					// Use a location key to specify the path to the saved image resource.
					// { location : '/your/uploaded/image/file'}
					$filetowrite = $upload_path . "/" . $new_name;
					echo json_encode(array('location' => "../$inp_file_path/$inp_file_name"));
				} // uploaded
				else{
					header("HTTP/1.1 400 Could not upload.");
					return;
				}
			} // jpg, png
		} // process == 1
	} // recipe found
}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/submit_recipe.php\">
	";
}

?>