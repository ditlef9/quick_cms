<?php 
/**
*
* File: exercise/edit_exercise.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

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
		die;
	}
	else{

		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_edit_exercise $get_exercise_title - $l_exercises";
		include("$root/_webdesign/header.php");



		echo"
		<h1>$get_exercise_title</h1>
	

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



		<!-- Where am I? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
			&gt;
			<a href=\"$root/exercises/my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_my_exercises</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$get_exercise_title</a>
			</p>
		<!-- //Where am I? -->
	

		<!-- Edit menu -->
			<div class=\"vertical\">
				<ul>
 
					<li><a href=\"$root/exercises/view_exercise.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_view_exercise</a></li>
					<li><a href=\"$root/exercises/edit_exercise_title.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_title</a></li>
					<li><a href=\"$root/exercises/edit_exercise_text.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_text</a></li>
					<li><a href=\"$root/exercises/edit_exercise_preparation.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_preparation</a></li>
					<li><a href=\"$root/exercises/edit_exercise_guide.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_guide</a></li>
					<li><a href=\"$root/exercises/edit_exercise_important.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_important</a></li>
					<li><a href=\"$root/exercises/edit_exercise_muscle_group.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_muscle_group</a></li>
					<li><a href=\"$root/exercises/edit_exercise_muscles_that_are_beeing_trained.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_muscles_that_are_beeing_trained</a></li>
					<li><a href=\"$root/exercises/edit_exercise_assistant_muscles.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_assistant_muscles</a></li>
					<li><a href=\"$root/exercises/edit_exercise_equipment.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_equipment</a></li>
					<li><a href=\"$root/exercises/edit_exercise_images.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_images</a></li>
					<li><a href=\"$root/exercises/edit_exercise_video.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_video</a></li>
					<li><a href=\"$root/exercises/edit_exercise_categorization.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_categorization</a></li>
					<li><a href=\"$root/exercises/edit_exercise_tags.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_tags</a></li>
				</ul>
			</div>
		<!-- //Edit menu -->
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