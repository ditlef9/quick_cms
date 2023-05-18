<?php
/**
*
* File: workout_plans/workout_plans_without_image.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_weekly_workout_plans_without_image";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_weekly_workout_plans_without_image</h1>
<!-- //Headline and language -->



<!-- Show last workout plans -->
	
	";	
	//  
	$x = 0;

	$query_w = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_title, workout_weekly_updated, workout_weekly_introduction, workout_weekly_image_path, workout_weekly_image_file FROM $t_workout_plans_weekly WHERE workout_weekly_language=$l_mysql AND workout_weekly_image_file=''";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_workout_weekly_id, $get_workout_weekly_user_id, $get_workout_weekly_period_id, $get_workout_weekly_title, $get_workout_weekly_updated, $get_workout_weekly_introduction, $get_workout_weekly_image_path, $get_workout_weekly_image_file) = $row_w;

		// User
		$query_u = "SELECT user_id, user_name, user_alias FROM $t_users WHERE user_id='$get_workout_weekly_user_id'";
		$result_u = mysqli_query($link, $query_u);
		$row_u = mysqli_fetch_row($result_u);
		list($get_user_id, $get_user_name, $get_user_alias) = $row_u;

		// Date
		$year = substr($get_workout_weekly_updated, 0, 4);
		$month = substr($get_workout_weekly_updated, 5, 2);
		$day = substr($get_workout_weekly_updated, 8, 2);

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

		// Introduction
		$get_workout_weekly_introduction_len = strlen($get_workout_weekly_introduction);
		if($get_workout_weekly_introduction_len > 170){
			$get_workout_weekly_introduction = substr($get_workout_weekly_introduction, 0, 170);
			$get_workout_weekly_introduction = $get_workout_weekly_introduction . "...";
		}


		if($x == 0){
			echo"
			<div class=\"clear\"></div>
			<div class=\"left_right_left\">
			";
		}
		elseif($x == 1){
			echo"
			<div class=\"left_right_right\">
			";
		}

		echo"
				<p style=\"padding-bottom:0;margin-bottom:0;\">
				<a href=\"weekly_workout_plan_view.php?weekly_id=$get_workout_weekly_id&amp;l=$l\" class=\"h2\">$get_workout_weekly_title</a><br />
				$get_workout_weekly_introduction	
				</p>
			</div>
		";

			
		if($x == 1){ 
			$x = -1;
		}

		$x++;
	} // loop
	echo"
<!-- //Show last workout plans -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>