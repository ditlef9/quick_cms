<?php 
/**
*
* File: exercise/edit_exercise_muscles_that_are_beeing_trained.php
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
include("$root/_admin/_translations/site/$l/exercises/ts_edit_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
}
if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);



if(isset($_GET['muscle_id'])){
	$muscle_id = $_GET['muscle_id'];
	$muscle_id = strip_tags(stripslashes($muscle_id));
}
else{
	$muscle_id = "";
}
if(isset($_GET['main_group_id'])){
	$main_group_id = $_GET['main_group_id'];
	$main_group_id = strip_tags(stripslashes($main_group_id));
}
else{
	$main_group_id = "";
}
if(isset($_GET['sub_group_id'])){
	$sub_group_id = $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}

if(isset($_GET['part_of_id'])){
	$part_of_id = $_GET['part_of_id'];
	$part_of_id = strip_tags(stripslashes($part_of_id));
}
else{
	$part_of_id = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_exercise - $l_exercises";
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
		
		if($action == "add"){
			// Delete muscle image
			$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_exercise_id'";
			$result_muscle = mysqli_query($link, $query_muscle);
			$row_muscle = mysqli_fetch_row($result_muscle);
			list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file) = $row_muscle;
			if($get_exercise_muscle_image_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_exercise_id'");
			}

			// Get muscle
			$muscle_id_mysql = quote_smart($link, $muscle_id);
			$query = "SELECT muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_group_id_main, muscle_group_id_sub, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_unique_hits, muscle_unique_hits_ip_block FROM $t_muscles WHERE muscle_id='$muscle_id_mysql'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_muscle_id, $get_current_muscle_user_id, $get_current_muscle_latin_name, $get_current_muscle_latin_name_clean, $get_current_muscle_simple_name, $get_current_muscle_group_id_main, $get_current_muscle_group_id_sub, $get_current_muscle_text, $get_current_muscle_image_path, $get_current_muscle_image_file, $get_current_muscle_video_path, $get_current_muscle_video_file, $get_current_muscle_unique_hits, $get_current_muscle_unique_hits_ip_block) = $row;

			if($get_current_muscle_id != ""){
				// Do I have it already?

				
				$query = "SELECT exercise_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id AND exercise_muscle_muscle_id=$get_current_muscle_id AND exercise_muscle_type='assistant'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_exercise_muscle_id) = $row;

				if($get_exercise_muscle_id == ""){
					// Insert
					mysqli_query($link, "INSERT INTO $t_exercise_index_muscles
					(exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type) 
					VALUES 
					(NULL, '$get_exercise_id', '$get_current_muscle_id', 'assistant')")
					or die(mysqli_error($link));

					$ft = "success";
					$fm = "muscle_added";
				}
			}

		} // process
		if($action == "remove"){
			// Delete muscle image
			$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_exercise_id'";
			$result_muscle = mysqli_query($link, $query_muscle);
			$row_muscle = mysqli_fetch_row($result_muscle);
			list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file) = $row_muscle;
			if($get_exercise_muscle_image_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_exercise_id'");
			}

			// Get muscle
			$muscle_id_mysql = quote_smart($link, $muscle_id);
			$query = "SELECT muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_group_id_main, muscle_group_id_sub, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_unique_hits, muscle_unique_hits_ip_block FROM $t_muscles WHERE muscle_id='$muscle_id_mysql'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_muscle_id, $get_current_muscle_user_id, $get_current_muscle_latin_name, $get_current_muscle_latin_name_clean, $get_current_muscle_simple_name, $get_current_muscle_group_id_main, $get_current_muscle_group_id_sub, $get_current_muscle_text, $get_current_muscle_image_path, $get_current_muscle_image_file, $get_current_muscle_video_path, $get_current_muscle_video_file, $get_current_muscle_unique_hits, $get_current_muscle_unique_hits_ip_block) = $row;

			if($get_current_muscle_id != ""){
				// Do I have it already?

				
				$query = "SELECT exercise_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id AND exercise_muscle_muscle_id=$get_current_muscle_id AND exercise_muscle_type='assistant'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_exercise_muscle_id) = $row;

				if($get_exercise_muscle_id != ""){
					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id AND exercise_muscle_muscle_id=$get_current_muscle_id AND exercise_muscle_type='assistant'");

					$ft = "success";
					$fm = "muscle_removed";
				}
				else{
					$ft = "warning";
					$fm = "muscle_not_found";
				}
			}
			else{
				$ft = "warning";
				$fm = "muscle_not_found_in_muscles_database";

				// Delete all
				$result = mysqli_query($link, "DELETE FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id AND exercise_muscle_type='assistant'");

			}

		} // remove
	
		echo"
		<h1>$l_edit_assistant_muscles</h1>
	



		<!-- Where am I? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
			&gt;
			<a href=\"$root/exercises/my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_my_exercises</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$get_exercise_title</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_assistant_muscles</a>
			</p>
		<!-- //Where am I? -->
	

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


		<!-- Used muscles -->
			<div class=\"left\" style=\"margin-right: 30px;\">
				<p><b>$l_assistant_muscles</b><br />
				$l_click_on_a_muscle_to_remove_it
				</p>
				<table class=\"hor-zebra\">
				 <tbody>";

				$query = "SELECT exercise_muscle_id, exercise_muscle_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_exercise_id' AND exercise_muscle_type='assistant'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_muscle_id) = $row;


					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_exercise_muscle_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					


					echo"
					  <tr>
					   <td style=\"padding-left: 8px;\">
						<span>
						<a href=\"edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;main_group_id=$main_group_id&amp;sub_group_id=$sub_group_id&amp;muscle_id=$get_exercise_muscle_muscle_id&amp;l=$l&amp;action=remove\">$get_muscle_translation_simple_name</a>
						</span>
					   </td>
					  </tr>
					";
					
				}
				echo"
				 </tbody>
				</table>
			</div>
		<!-- //Used muscles -->

		<!-- Used muscles -->
			<div class=\"left\" style=\"\">
				<p><b>$l_available_muscles</b><br />
				$l_click_on_a_muscle_to_add_it_to_assistant_muscles
				</p>

				
				<table class=\"hor-zebra\">
				";

				// Get main groups
				$query = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_muscle_group_id, $get_main_muscle_group_name) = $row;

					// Translation
					$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;



					echo"	
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<a href=\"edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l&amp;main_group_id=$get_main_muscle_group_id&amp;l=$l\""; if($main_group_id == "$get_main_muscle_group_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_main_muscle_group_translation_name</a>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					";
					if($main_group_id == "$get_main_muscle_group_id"){

						// Get sub categories
						$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_main_muscle_group_id'";
						$result_sub = mysqli_query($link, $query_sub);
						while($row_sub = mysqli_fetch_row($result_sub)) {
							list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
							// Translation
							$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;

		
							echo"
							  <tr>
							   <td style=\"padding-left: 8px;\">
								<a href=\"edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;l=$l\""; if($sub_group_id == "$get_sub_muscle_group_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_sub_muscle_group_translation_name</a>
							   </td>
							  </tr>
							";

							if($sub_group_id == "$get_sub_muscle_group_id"){
								echo"
								  <tr>
								   <td class=\"odd\" style=\"padding-left: 16px;\">
									<span>";


								// Get muscles
								$muscles_count = 0;
								$query_m = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_group_id_sub='$get_sub_muscle_group_id' AND muscle_part_of_id='0' ORDER BY muscle_latin_name ASC";
								$result_m = mysqli_query($link, $query_m);
								while($row_m = mysqli_fetch_row($result_m)) {
									list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_image_path, $get_muscle_image_file) = $row_m;

									// Translation
									$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					
									if($muscles_count != 0){
										echo" &middot;";
									}
					
									echo"
									<a href=\"edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;l=$l&amp;action=add\">$get_muscle_translation_simple_name</a>
									";


									$muscles_count++;
								}

								// Get part ofs
								$query_p = "SELECT muscle_part_of_id, muscle_part_of_name FROM $t_muscle_part_of WHERE muscle_part_of_muscle_group_id_sub='$get_sub_muscle_group_id'";
								$result_p = mysqli_query($link, $query_p);
								while($row_p = mysqli_fetch_row($result_p)) {
									list($get_muscle_part_of_id, $get_muscle_part_of_name) = $row_p;
						
									// Get translation
									$query_translation = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_name FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$l_mysql";
									$result_translation = mysqli_query($link, $query_translation);
									$row_translation = mysqli_fetch_row($result_translation);
									list($get_muscle_part_of_translation_id, $get_muscle_part_of_translation_name) = $row_translation;
									

									if($muscles_count != 0){
										echo" &middot;";
									}

									echo"
									$get_muscle_part_of_translation_name
									(";

					
				
					
									$muscles_count_inside = 0;
									$query_m = "SELECT muscle_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_image_path, muscle_image_file FROM $t_muscles WHERE muscle_group_id_sub='$get_sub_muscle_group_id' AND muscle_part_of_id != '0' ORDER BY muscle_latin_name ASC";
									$result_m = mysqli_query($link, $query_m);
									while($row_m = mysqli_fetch_row($result_m)) {
										list($get_muscle_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_image_path, $get_muscle_image_file) = $row_m;

										// Translation
										$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_muscle_id' AND muscle_translation_language=$l_mysql";
										$result_translation = mysqli_query($link, $query_translation);
										$row_translation = mysqli_fetch_row($result_translation);
										list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					
										if($muscles_count_inside != 0){
											echo" &middot;";
										}
					
										echo"
										<a href=\"edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;main_group_id=$get_main_muscle_group_id&amp;sub_group_id=$get_sub_muscle_group_id&amp;muscle_id=$get_muscle_id&amp;l=$l&amp;action=add\">";
										if($get_muscle_translation_short_name == ""){
											if($get_muscle_short_name == ""){
												echo"$get_muscle_simple_name";
											}
											else{
												echo"$get_muscle_short_name";
											}
										}
										else{
											echo"$get_muscle_translation_short_name";
										}
										echo"</a>
										";

										$muscles_count_inside++;
									}
									echo")
									";

									$muscles_count++;
								} // part of


								echo"</span>
								   </td>
								  </tr>
								";
					
							} // sub open

						} // get sub
					} // main open

					echo"
					 </tbody>
					";
				}

				echo"
				</table>

			</div>
			<div class=\"clear\"></div>
		<!-- //Used muscles -->
		";
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