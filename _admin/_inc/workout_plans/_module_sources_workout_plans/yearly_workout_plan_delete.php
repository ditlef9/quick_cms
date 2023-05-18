<?php 
/**
*
* File: workout_plans/yearly_workout_plan_delete.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['yearly_id'])){
	$yearly_id = $_GET['yearly_id'];
	$yearly_id = output_html($yearly_id);
}
else{
	$yearly_id = "";
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
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get workout plan yearly
	$yearly_id_mysql = quote_smart($link, $yearly_id);
	$query = "SELECT workout_yearly_id, workout_yearly_user_id, workout_yearly_language, workout_yearly_title, workout_yearly_title_clean, workout_yearly_introduction, workout_yearly_goal, workout_yearly_text, workout_yearly_year, workout_yearly_image_path, workout_yearly_image_file, workout_yearly_created, workout_yearly_updated, workout_yearly_unique_hits, workout_yearly_unique_hits_ip_block, workout_yearly_comments, workout_yearly_likes, workout_yearly_dislikes, workout_yearly_rating, workout_yearly_ip_block, workout_yearly_user_ip, workout_yearly_notes FROM $t_workout_plans_yearly WHERE workout_yearly_id=$yearly_id_mysql AND workout_yearly_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_yearly_id, $get_current_workout_yearly_user_id, $get_current_workout_yearly_language, $get_current_workout_yearly_title, $get_current_workout_yearly_title_clean, $get_current_workout_yearly_introduction, $get_current_workout_yearly_goal, $get_current_workout_yearly_text, $get_current_workout_yearly_year, $get_current_workout_yearly_image_path, $get_current_workout_yearly_image_file, $get_current_workout_yearly_created, $get_current_workout_yearly_updated, $get_current_workout_yearly_unique_hits, $get_current_workout_yearly_unique_hits_ip_block, $get_current_workout_yearly_comments, $get_current_workout_yearly_likes, $get_current_workout_yearly_dislikes, $get_current_workout_yearly_rating, $get_current_workout_yearly_ip_block, $get_current_workout_yearly_user_ip, $get_current_workout_yearly_notes) = $row;
	
	

	if($get_workout_yearly_id == ""){
		echo"<p>Workout yearly not found.</p>";
	}
	else{

		if($process == "1"){
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_workout_plans_yearly WHERE workout_yearly_id=$yearly_id_mysql");



			// Delete image
			if(file_exists("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file")) {
				unlink("../$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file");
			}

			// Search engine index
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='workout_plans' AND index_reference_name='workout_yearly_id' AND index_reference_id=$get_workout_yearly_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}


			// Header
			$url = "my_workout_plans.php?duration_type=$duration_type&l=$l&ft=success&fm=yearly_workoutplan_deleted";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$get_current_workout_yearly_title</h1>
	

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"my_workout_plans.php?duration_type=$duration_type&amp;l=$l\">$l_my_workout_plans</a>
			&gt;
			<a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Navigation -->
			<div class=\"tabs\" style=\"margin-top: 6px;\">
				<ul>
					<li><a href=\"yearly_workout_plan_view.php?yearly_id=$yearly_id&amp;l=$l\">$l_view</a></li>
					<li><a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_edit</a></li>
					<li><a href=\"yearly_workout_plan_edit_image.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"margin-bottom: 6px;\"></div>
		<!-- //Navigation -->

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


		<!-- Form -->
			
			<h2>$l_delete</h2>


			<p>
			$l_are_you_sure?
			</p>

			<p>
			<a href=\"yearly_workout_plan_delete.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
			<a href=\"yearly_workout_plan_edit.php?yearly_id=$yearly_id&amp;duration_type=$duration_type&amp;l=$l&amp;process=1\" class=\"btn btn_defaul\">$l_cancel</a>
			</p>
	
		<!-- //Form -->
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