<?php 
/**
*
* File: workout_plans/yearly_workout_plan_view.php
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
include("$root/_admin/_translations/site/$l/workout_plans/ts_my_workout_plans.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['yearly_id'])){
	$yearly_id = $_GET['yearly_id'];
	$yearly_id = output_html($yearly_id);
}
else{
	$yearly_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);



// Get workout plan yearly
$yearly_id_mysql = quote_smart($link, $yearly_id);
$query = "SELECT workout_yearly_id, workout_yearly_user_id, workout_yearly_language, workout_yearly_title, workout_yearly_title_clean, workout_yearly_introduction, workout_yearly_goal, workout_yearly_text, workout_yearly_year, workout_yearly_image_path, workout_yearly_image_file, workout_yearly_created, workout_yearly_updated, workout_yearly_unique_hits, workout_yearly_unique_hits_ip_block, workout_yearly_comments, workout_yearly_likes, workout_yearly_dislikes, workout_yearly_rating, workout_yearly_ip_block, workout_yearly_user_ip, workout_yearly_notes FROM $t_workout_plans_yearly WHERE workout_yearly_id=$yearly_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_workout_yearly_id, $get_current_workout_yearly_user_id, $get_current_workout_yearly_language, $get_current_workout_yearly_title, $get_current_workout_yearly_title_clean, $get_current_workout_yearly_introduction, $get_current_workout_yearly_goal, $get_current_workout_yearly_text, $get_current_workout_yearly_year, $get_current_workout_yearly_image_path, $get_current_workout_yearly_image_file, $get_current_workout_yearly_created, $get_current_workout_yearly_updated, $get_current_workout_yearly_unique_hits, $get_current_workout_yearly_unique_hits_ip_block, $get_current_workout_yearly_comments, $get_current_workout_yearly_likes, $get_current_workout_yearly_dislikes, $get_current_workout_yearly_rating, $get_current_workout_yearly_ip_block, $get_current_workout_yearly_user_ip, $get_current_workout_yearly_notes) = $row;
	
	
if($get_current_workout_yearly_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404";
	include("$root/_webdesign/header.php");
	echo"<p>Workout yearly not found.</p>";

	include("$root/_webdesign/footer.php");
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_workout_yearly_title - $l_workout_plans";
	include("$root/_webdesign/header.php");



	/*- Content ---------------------------------------------------------------------------------- */
	echo"
	<h1>$get_current_workout_yearly_title</h1>
	
	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_my_workout_plans</a>
		&gt;
		<a href=\"yearly_workout_plan_view.php?yearly_id=$yearly_id&amp;l=$l\">$get_current_workout_yearly_title</a>
		</p>
	<!-- //Where am I ? -->


	<!-- Intro and image -->

		<p>";
		if($get_current_workout_yearly_image_file != "" && file_exists("$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file")){
			echo"
			<img src=\"$root/$get_current_workout_yearly_image_path/$get_current_workout_yearly_image_file\" alt=\"$get_current_workout_yearly_image_file\" /><br />
			$get_current_workout_yearly_introduction";
		}
		echo"
		</p>
	<!-- //Intro and  image -->

	<!-- Goal -->
		<h2>$l_goal:</h2>
		$get_current_workout_yearly_goal
	<!-- Goal -->


	<!-- Period plans -->
		<h2>$l_period</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_title</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_from</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_to</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_updated</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


		";
		$query_p = "SELECT workout_period_id, workout_period_title, workout_period_introduction, workout_period_from, workout_period_to, workout_period_updated FROM $t_workout_plans_period WHERE workout_period_user_id=$my_user_id_mysql AND workout_period_yearly_id=$get_current_workout_yearly_id AND workout_period_language=$l_mysql";
		$result_p = mysqli_query($link, $query_p);
		while($row_p = mysqli_fetch_row($result_p)) {
			list($get_workout_period_id, $get_workout_period_title, $get_workout_period_introduction, $get_workout_period_from, $get_workout_period_to, $get_workout_period_updated) = $row_p;
				
			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}


			// Date
			$year = substr($get_workout_period_updated, 0, 4);
			$month = substr($get_workout_period_updated, 5, 2);
			$day = substr($get_workout_period_updated, 8, 2);

			if($day < 10){
				$day = substr($day, 1, 1);
			}
		
			if($month == "01"){
				$month_saying = $l_january;
			}
			elseif($month == "02"){
				$month_saying = $l_february;
			}
			elseif($month == "03"){
				$month_saying = $l_march;
			}
			elseif($month == "04"){
				$month_saying = $l_april;
			}
			elseif($month == "05"){
				$month_saying = $l_may;
			}
			elseif($month == "06"){
				$month_saying = $l_june;
			}
			elseif($month == "07"){
				$month_saying = $l_july;
			}
			elseif($month == "08"){
				$month_saying = $l_august;
			}
			elseif($month == "09"){
				$month_saying = $l_september;
			}
			elseif($month == "10"){
				$month_saying = $l_october;
			}
			elseif($month == "11"){
				$month_saying = $l_november;
			}
			else{
				$month_saying = $l_december;
			}


			echo"
			<tr>
			  <td class=\"$style\">
				<span><a href=\"period_workout_plan_view.php?period_id=$get_workout_period_id\">$get_workout_period_title</a><br />
				$get_workout_period_introduction</span>
			  </td>
		 	  <td class=\"$style\">
				<span>$get_workout_period_from</span>
			  </td>
		 	  <td class=\"$style\">
				<span>$get_workout_period_to</span>
			  </td>
			  <td class=\"$style\">
				<span>$day $month_saying $year</span>
			  </td>
			";
		} // while period
	echo"
	 </tbody>
	</table>
	";
} // found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>