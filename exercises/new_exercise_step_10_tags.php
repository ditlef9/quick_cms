<?php 
/**
*
* File: exercise/new_exercise_step_10_tags.php
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
include("_tables_exercises.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");
include("$root/_admin/_translations/site/$l/exercises/ts_edit_exercise_tags.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
}
if(isset($_GET['tag_id'])){
	$tag_id = $_GET['tag_id'];
	$tag_id = output_html($tag_id);
}
else{
	$tag_id = "";
}



$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_exercises - $l_new_exercise -";
$website_title = "$l_new_exercise - $l_exercises";
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

	// Get exercise
	$exercise_id_mysql = quote_smart($link, $exercise_id);
	$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql AND exercise_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason) = $row;
	

	

	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{

		if($action == ""){

			if($process == 1){
			
				$inp_tag = $_POST['inp_tag'];
				$inp_tag = strtolower($inp_tag);
				$inp_tag = output_html($inp_tag);
				$inp_tag_mysql = quote_smart($link, $inp_tag);

				$inp_tag_clean = clean($inp_tag);
				$inp_tag_clean_mysql = quote_smart($link, $inp_tag_clean);



				// Check if I have this tag already
				$query = "SELECT tag_id FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_exercise_id AND tag_text=$inp_tag_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_tag_id) = $row;


				if($get_tag_id != ""){
					// Already have it
					$ft = "error";
					$fm = "you_already_have_that_tag";
				
					$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					header("Location: $url");
					exit;

				}


	
				// Insert
				$inp_l_mysql = quote_smart($link, $get_exercise_language);
				mysqli_query($link, "INSERT INTO $t_exercise_index_tags
				(tag_id, tag_exercise_id, tag_language, tag_text, tag_clean) 
				VALUES 
				(NULL, $get_exercise_id, $inp_l_mysql, $inp_tag_mysql, $inp_tag_clean_mysql)")
				or die(mysqli_error($link)); 

			
				// Check if it exists in tags_cloud
				$query = "SELECT cloud_id, cloud_occurrences FROM $t_exercise_tags_cloud WHERE cloud_language=$inp_l_mysql AND cloud_clean=$inp_tag_clean_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_cloud_id, $get_cloud_occurrences) = $row;
				if($get_cloud_id == ""){
					// First occorence
					mysqli_query($link, "INSERT INTO $t_exercise_tags_cloud
					(cloud_id, cloud_language, cloud_text, cloud_clean, cloud_occurrences) 
					VALUES 
					(NULL, $inp_l_mysql, $inp_tag_mysql, $inp_tag_clean_mysql, 1)")
					or die(mysqli_error($link)); 
				}
				else{
					$inp_occurrences = $get_cloud_occurrences+1;
					mysqli_query($link, "UPDATE $t_exercise_tags_cloud SET cloud_occurrences=$inp_occurrences WHERE cloud_id=$get_cloud_id") or die(mysqli_error($link)); 
				}


				
				$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&l=$l";
				$url = $url . "&ft=success&fm=tag_added";
				header("Location: $url");
				exit;

			}
			echo"
			<h1>$l_tags</h1>
	



			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "tag_added"){
					$fm = "$l_tag_added";
				}
				elseif($fm == "you_already_have_that_tag"){
					$fm = "$l_you_already_have_that_tag";
				}
				elseif($fm == "equipment_saved"){
					$fm = "$l_equipment_saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Add tag -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_tag\"]').focus();
			});
			</script>
	
			<form method=\"post\" action=\"new_exercise_step_10_tags.php?exercise_id=$exercise_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>$l_new_tag:</b><br />
			<input type=\"text\" name=\"inp_tag\" value=\"\" size=\"15\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			<input type=\"submit\" value=\"$l_add_tag\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
			<!-- //Add tag -->

			<!-- Tags -->";
				// Count tags
				$query = "SELECT count(tag_id) FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_exercise_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_tags) = $row;
	

				if($get_count_tags != "0"){

					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_tags ($get_count_tags):</b></p>
					<table>";
					$query = "SELECT tag_id, tag_text FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_exercise_id";
					$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
						list($get_tag_id, $get_tag_text) = $row;
						echo"
						 <tr>
						  <td style=\"padding: 2px 4px 3px 0px;vertical-align:top;\">
							<span>$get_tag_text</span>
						  </td>
						  <td style=\"padding: 4px 4px 3px 0px;vertical-align:top;\">
							 <span><a href=\"new_exercise_step_10_tags.php?exercise_id=$exercise_id&amp;action=delete_tag&amp;tag_id=$get_tag_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a></span>
						  </td>
						 </tr>";
					}
					echo"
					</table>
					";
				}
				echo"
			<!-- //Tags -->

			<!-- Next step -->
				<p><a href=\"new_exercise_step_11_images.php?exercise_id=$exercise_id&amp;action=upload_image&amp;l=$l\" class=\"btn_default\">$l_next_step</a></p>
			<!-- //Next step -->
			";

		} // action == blank
		elseif($action == "delete_tag"){
			// Fetch tag
			$tag_id_mysql = quote_smart($link, $tag_id);
			$query = "SELECT tag_id, tag_text, tag_clean FROM $t_exercise_index_tags WHERE tag_id=$tag_id_mysql AND tag_exercise_id=$get_exercise_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_tag_id, $get_current_tag_text, $get_current_tag_clean) = $row;
			if($get_current_tag_id == ""){
				$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&main_muscle_group_id=$get_exercise_muscle_group_id_main&type_id=$get_exercise_type_id&l=$l";
				$url = $url . "&ft=info&fm=tag_not_found";
				header("Location: $url");
				exit;
			}
			else{
				// Delete 
				$inp_l_mysql = quote_smart($link, $get_exercise_language);
				mysqli_query($link, "DELETE FROM $t_exercise_index_tags WHERE tag_id=$get_current_tag_id")
				or die(mysqli_error($link)); 


				// Check if it exists in tags_cloud
				$inp_tag_clean_mysql = quote_smart($link, $get_current_tag_clean);
				$query = "SELECT cloud_id, cloud_occurrences FROM $t_exercise_tags_cloud WHERE cloud_language=$inp_l_mysql AND cloud_clean=$inp_tag_clean_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_cloud_id, $get_cloud_occurrences) = $row;
				if($get_cloud_id != ""){
					$inp_occurrences = $get_cloud_occurrences-1;
					mysqli_query($link, "UPDATE $t_exercise_tags_cloud SET cloud_occurrences=$inp_occurrences WHERE cloud_id=$get_cloud_id") or die(mysqli_error($link)); 
				}

			
				$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&main_muscle_group_id=$get_exercise_muscle_group_id_main&type_id=$get_exercise_type_id&l=$l";
				$url = $url . "&ft=success&fm=tag_deleted";
				header("Location: $url");
				exit;


			} // tag found


		} // delete_tag
	
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