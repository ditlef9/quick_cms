<?php 
/**
*
* File: food/new_workout_plan_weekly_step_2_template_image.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}
if(isset($_GET['session_id'])){
	$session_id = $_GET['session_id'];
	$session_id = output_html($session_id);
}
else{
	$session_id = "";
}
if(isset($_GET['session_main_id'])){
	$session_main_id = $_GET['session_main_id'];
	$session_main_id = output_html($session_main_id);
}
else{
	$session_main_id = "";
}

if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_workout_plan - $l_workout_plans";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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

	// Get workout plan weekly
	$weekly_id_mysql = quote_smart($link, $weekly_id);
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql AND workout_weekly_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	
	

	if($get_current_workout_weekly_id == ""){
		echo"<p>Weekly not found.</p>";
	}
	else{
		if($action == ""){
	
			echo"
			<h1>$l_new_weekly_workout_plan</h1>
	

			<!-- Exisitng image -->
			<p>";
			if($get_current_workout_weekly_image_file != ""){
				if(file_exists("$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file")){

					echo"
					<img src=\"$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file\" alt=\"$get_current_workout_weekly_image_file\" /><br />
					";
					$check_for_template_image = substr("$get_current_workout_weekly_image_file", 0, 1);
					if($check_for_template_image != "t"){
						// echo"<a href=\"weekly_workout_plan_edit_image_rotate.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\">$l_rotate</a>";
					}
				}
				else{

					echo"<p><b>$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file</b> finnes ikke";
				}
			}
			echo"
			</p>
			<!-- //Exisitng image -->

			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->
			<!-- Form -->
			

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<h2>$l_upload_describing_image</h2>

			<p>
			$l_upload_a_image_from_your_device_that_describes_your_workout_plan.
			$l_the_image_should_be 1280x720.
			</p>

			<form method=\"post\" action=\"new_workout_plan_weekly_step_2_template_image.php?weekly_id=$get_current_workout_weekly_id&amp;action=upload_image&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

			<p><b>$l_new_image (1280x720 jpg):</b><br />
			<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			
			<p>
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
			<!-- //Form -->


	
			<hr />
			<h2>$l_use_existing_describing_image</h2>

			<p>
			$l_select_one_image_below_that_describes_your_workout_plan
			</p>
			";

			// Custom pages
			$filenames = "";
			$dir = "_gfx/image_templates/";
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === 'thumbs') continue;
					$extension = get_extension($file);
					$image_name = str_replace(".$extension", "", $file);
	
					// Check if this is a thumb
					$check = explode("_", $file);
					$array_size = sizeof($check);

					if($array_size == "1"){

					// Thumb
					$workout_weekly_image_thumb_big = "$image_name" . "_thumb_400x269." . $extension;
					$workout_weekly_image_thumb_medium = "$image_name" . "_thumb_145x98." . $extension;
					if(!(file_exists("_gfx/image_templates/thumbs/$workout_weekly_image_thumb_big"))){
						if(!is_dir("_gfx/image_templates/thumbs")){
							mkdir("_gfx/image_templates/thumbs");
						}
						resize_crop_image("400", "269", "_gfx/image_templates/$file", "_gfx/image_templates/thumbs/$workout_weekly_image_thumb_big");
					}
					if(!(file_exists("_gfx/image_templates/thumbs/$workout_weekly_image_thumb_medium"))){
						resize_crop_image("145", "98", "_gfx/image_templates/$file", "_gfx/image_templates/thumbs/$workout_weekly_image_thumb_medium");
					}

					echo"
					<a href=\"new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&amp;l=$l&amp;action=use_template&amp;image_file=$image_name&amp;process=1\"><img src=\"_gfx/image_templates/thumbs/$workout_weekly_image_thumb_medium\" alt=\"$file\" /></a>
					";
					}
				
				}
				closedir($handle);
			}

		} // action == ""
		elseif($action == "upload_image"){

			// Sjekk filen
			$file_name = basename($_FILES['inp_image']['name']);
			$file_exp = explode('.', $file_name); 
			$file_type = $file_exp[count($file_exp) -1]; 
			$file_type = strtolower("$file_type");

			// Finnes mappen?
			$upload_path = "$root/_uploads/workout_plans/$l/weekly/$get_current_workout_weekly_title_clean";

			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/workout_plans"))){
				mkdir("$root/_uploads/workout_plans");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l"))){
				mkdir("$root/_uploads/workout_plans/$l");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l/weekly"))){
				mkdir("$root/_uploads/workout_plans/$l/weekly");
			}
			if(!(is_dir("$root/_uploads/workout_plans/$l/weekly/$get_current_workout_weekly_title_clean"))){
				mkdir("$root/_uploads/workout_plans/$l/weekly/$get_current_workout_weekly_title_clean");
			}

			// Sett variabler
			$new_name = $get_current_workout_weekly_id . ".$file_type";

			$target_path = $upload_path . "/" . $new_name;

			// Sjekk om det er en OK filendelse
			if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){


				// Do I already have a image of that type? Then delete the old image..
				if($get_current_workout_weekly_image_file != "" && file_exists("$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file")){
					$check_for_template_image = substr("$get_current_workout_weekly_image_file", 0, 1);
					if($check_for_template_image != "t"){
						unlink("$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file");
					}
				}

					

				if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {
					// Sjekk om det faktisk er et bilde som er lastet opp
					list($width,$height) = getimagesize($target_path);
					if(is_numeric($width) && is_numeric($height)){

						// Check that file is big enough
						if($width < 1279){
							unlink($target_path);
							$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}
						if($height < 719){
							unlink($target_path);
							$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
							header("Location: $url");
							exit;
						}

						


						// image path							
						$inp_image_path  = "_uploads/workout_plans/$l/weekly/$get_current_workout_weekly_title_clean";
						$inp_image_path_mysql = quote_smart($link, $inp_image_path);

						// image file
						$inp_image_file = $new_name;
						$inp_image_file_mysql = quote_smart($link, $inp_image_file);

					
						// Dette bildet er OK
						// Resize it
						$inp_new_x = 1280;
						$inp_new_y = 720;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_image_path/$inp_image_file", "$root/$inp_image_path/$inp_image_file");

						// Thumb 400x225
						$inp_thumb_400x225 = $get_current_workout_weekly_id . "_thumb_400x225" . ".$file_type";
						$inp_thumb_400x225_mysql = quote_smart($link, $inp_thumb_400x225);
						$inp_new_x = 400;
						$inp_new_y = 225;
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$inp_image_path/$inp_image_file", "$root/$inp_image_path/$inp_thumb_400x225");

						
						// Update MySQL
						$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET 
										workout_weekly_image_path=$inp_image_path_mysql,
										workout_weekly_image_file=$inp_image_file_mysql,
										workout_weekly_image_thumb_400x225=$inp_thumb_400x225_mysql WHERE workout_weekly_id=$weekly_id_mysql");


						// Make sure workout plan exists in feed
						include("new_workout_plan_weekly_include_insert_into_feed.php");


						// Get first session id
						$query = "SELECT workout_session_id FROM $t_workout_plans_sessions WHERE workout_session_user_id=$my_user_id_mysql AND workout_session_weekly_id=$weekly_id_mysql ORDER BY workout_session_id ASC LIMIT 0,1";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_workout_session_id) = $row;
	

						// Header
						$url = "new_workout_plan_weekly_step_3_sessions.php?weekly_id=$get_current_workout_weekly_id&action=add_exercise_to_session&session_id=$get_workout_session_id&l=$l&ft=success&fm=image_uploaded";
						header("Location: $url");
						exit;
					}
					else{
						// Dette er en fil som har fått byttet filendelse...
						unlink("$target_path");
						$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=file_is_not_an_image";
						header("Location: $url");
						exit;
					}
				}
				else{
					switch ($_FILES['inp_image'] ['error']){
					case 1:
						$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 2:
						$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
						break;
					case 3:
						$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=only_parts_uploaded";
						header("Location: $url");
						exit;
						break;
					case 4:
						$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=no_file_uploaded";
						header("Location: $url");
						exit;
						break;
					}
				} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
			}
			else{
				$url = "new_workout_plan_weekly_step_2_template_image.php?weekly_id=$weekly_id&l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
				header("Location: $url");
				exit;
			} // file type end
		} // upload image
		elseif($action == "use_template"){
			// Period
			$image_file = $_GET['image_file'];
			$image_file = output_html($image_file);	

			$workout_weekly_image_file = "$image_file." . "jpg";
			$workout_weekly_image_file_mysql = quote_smart($link, $workout_weekly_image_file);

			$workout_weekly_image_thumb = "$image_file" . "_thumb_400x225." . "jpg";
			$workout_weekly_image_thumb_mysql = quote_smart($link, $workout_weekly_image_thumb);

			// Update
			$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET 
							workout_weekly_image_path='workout_plans/_gfx/image_templates',
							workout_weekly_image_file=$workout_weekly_image_file_mysql,
							workout_weekly_image_thumb_400x225=$workout_weekly_image_thumb_mysql
							 WHERE workout_weekly_id=$weekly_id_mysql") or die(mysqli_error($link));


			// Make sure workout plan exists in feed
			include("new_workout_plan_weekly_include_insert_into_feed.php");
			
			// Get first session id
			$query = "SELECT workout_session_id FROM $t_workout_plans_sessions WHERE workout_session_user_id=$my_user_id_mysql AND workout_session_weekly_id=$weekly_id_mysql ORDER BY workout_session_id ASC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_workout_session_id) = $row;
	
			// Header
			$url = "new_workout_plan_weekly_step_3_sessions.php?weekly_id=$get_current_workout_weekly_id&action=add_exercise_to_session&session_id=$get_workout_session_id&l=$l&ft=success&fm=template_image_used";
			header("Location: $url");
			exit;

		} // use template
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>