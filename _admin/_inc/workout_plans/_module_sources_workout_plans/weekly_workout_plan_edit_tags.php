<?php 
/**
*
* File: workout_plans/weekly_workout_plan_tags.php
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
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}

if(isset($_GET['duration_type'])){
	$duration_type = $_GET['duration_type'];
	$duration_type = strip_tags(stripslashes($duration_type));
}
else{
	$duration_type = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_workout_plan - $l_workout_plans";
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
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;


	// Get workout plan weekly
	$weekly_id_mysql = quote_smart($link, $weekly_id);
	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;


	if($get_current_workout_weekly_id == ""){
		echo"<p>Weekly not found.</p>";
	}
	else{
		// User check
		if($get_current_workout_weekly_user_id != "$get_my_user_id" && $get_my_user_rank != "admin" && $get_my_user_rank != "moderator"){
			echo"
			<h1>Server error 403</h1>

			<p>Access denied. Only the owner, administrator or moderator can edit.</p>
			";
		}
		else{
		if($action == ""){
			if($process == "1"){

			$inp_tag = $_POST['inp_tag'];
			$inp_tag = output_html($inp_tag);
			$inp_tag_mysql = quote_smart($link, $inp_tag);
			if(empty($inp_tag)){
				$url = "weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
				$url = $url . "&ft=error&fm=missing_tag";
				header("Location: $url");
				exit;
			}

			$inp_tag_clean = clean($inp_tag);
			$inp_tag_clean_mysql = quote_smart($link, $inp_tag_clean);
			
			
			// Check if it exits
			$query = "SELECT tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id FROM $t_workout_plans_weekly_tags WHERE tag_weekly_id=$weekly_id_mysql AND tag_title=$inp_tag_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_tag_id, $get_tag_weekly_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row;
			if($get_tag_id != ""){
				$url = "weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
				$url = $url . "&ft=error&fm=tag_already_exists";
				header("Location: $url");
				exit;
			}

			// Insert
			mysqli_query($link, "INSERT INTO $t_workout_plans_weekly_tags
			(tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id) 
			VALUES 
			(NULL, $weekly_id_mysql, $l_mysql, $inp_tag_mysql, $inp_tag_clean_mysql, $my_user_id_mysql)")
			or die(mysqli_error($link));


			// Unique
			$query = "SELECT tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_language=$l_mysql AND tag_unique_title_clean=$inp_tag_clean_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_tag_unique_id, $get_tag_unique_language, $get_tag_unique_title, $get_tag_unique_title_clean, $get_tag_unique_no_of_workout_plans) = $row;
			if($get_tag_unique_id == ""){
				mysqli_query($link, "INSERT INTO $t_workout_plans_weekly_tags_unique 
				(tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans) 
				VALUES 
				(NULL, $l_mysql, $inp_tag_mysql, $inp_tag_clean_mysql, 1)")
				or die(mysqli_error($link));
			}
			else{
				$inp_counter = $get_tag_unique_no_of_workout_plans+1;
				$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly_tags_unique SET tag_unique_no_of_workout_plans='$inp_counter' WHERE tag_unique_id=$get_tag_unique_id");
			}


			// Header
			$url = "weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
				$url = $url . "&ft=success&fm=tag_added";
			header("Location: $url");
			exit;

			}

			echo"
			<h1>$get_current_workout_weekly_title</h1>
	

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
				&gt;
				<a href=\"weekly_workout_plan_edit.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
				&gt;
				<a href=\"weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_tags</a>
				</p>
			<!-- //Where am I ? -->

			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->


			<!-- New tag form -->

				<!-- Focus -->
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_tag\"]').focus();
					});
				</script>
				<!-- //Focus -->


				<form method=\"post\" action=\"weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
				<p><b>$l_tag:</b><br />
				<input type=\"text\" name=\"inp_tag\" size=\"10\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				<input type=\"submit\" value=\"$l_add\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
				</form>
			<!-- //New tag form -->


			<!-- Tags -->
				<p><b>$l_tags</b><br />
				";
				$query = "SELECT tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id FROM $t_workout_plans_weekly_tags WHERE tag_weekly_id=$weekly_id_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_tag_id, $get_tag_weekly_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row;
					echo"
					$get_tag_title <a href=\"weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&amp;action=delete_tag&amp;tag_id=$get_tag_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a><br />
					";

				}
				echo"
				</p>
			<!-- //Tags -->
			";
		} // action == ""
		elseif($action == "delete_tag"){
			if(isset($_GET['tag_id'])){
				$tag_id = $_GET['tag_id'];
				$tag_id = strip_tags(stripslashes($tag_id));
			}
			else{
				$tag_id = "";
			}
			if($process == "1"){

				// Check if it exits
				$query = "SELECT tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id FROM $t_workout_plans_weekly_tags WHERE tag_id=$tag_id AND tag_weekly_id=$weekly_id_mysql AND tag_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_tag_id, $get_tag_weekly_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row;
				if($get_tag_id == ""){
					$url = "weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
					$url = $url . "&ft=error&fm=tag_not_found";
					header("Location: $url");
					exit;
				}
				else{
					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_workout_plans_weekly_tags WHERE tag_id=$tag_id AND tag_weekly_id=$weekly_id_mysql AND tag_user_id=$my_user_id_mysql");



					// Unique
					$inp_tag_title_clean_mysql = quote_smart($link, $get_tag_title_clean);
					$query = "SELECT tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_language=$l_mysql AND tag_unique_title_clean=$inp_tag_title_clean_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_tag_unique_id, $get_tag_unique_language, $get_tag_unique_title, $get_tag_unique_title_clean, $get_tag_unique_no_of_workout_plans) = $row;
					if($get_tag_id != ""){
						$inp_counter = $get_tag_unique_no_of_workout_plans-1;
						if($inp_counter == "0"){
							$result = mysqli_query($link, "DELETE FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_id=$get_tag_unique_id");
						}
						else{
							$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly_tags_unique SET tag_unique_no_of_workout_plans='$inp_counter' WHERE tag_unique_id=$get_tag_unique_id");
						}
					}



					$url = "weekly_workout_plan_edit_tags.php?weekly_id=$weekly_id&duration_type=$duration_type=&l=$l";
					$url = $url . "&ft=success&fm=tag_deleted";
					header("Location: $url");
					exit;
				}
			} // process == 1
		} // action == delete tag
		} // access
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