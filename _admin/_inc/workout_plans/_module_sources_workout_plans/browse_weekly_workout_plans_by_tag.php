<?php
/**
*
* File: workout_plans/browse_weekly_workout_plans_by_tag.php
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

if(isset($_GET['tag'])) {
	$tag = $_GET['tag'];
	$tag = strip_tags(stripslashes($tag));
}
else{
	$tag = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "#$tag - $l_workout_plans";
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_workout_plans #$tag</h1>
<!-- //Headline and language -->


<!-- Quick menu -->
	<div style=\"height:10px;\"></div>
	<p>
	<a href=\"$root/workout_plans/my_workout_plans.php?l=$l\" class=\"btn_default\">$l_my_workout_plans</a>
	<a href=\"$root/workout_plans/new_workout_plan.php?l=$l\" class=\"btn_default\">$l_new_workout_plan</a>
	</p>
	<div style=\"clear:both;height:10px;\"></div>
<!-- //Quick menu -->


<!-- Tags -->
	<h2>$l_browse_by_tags</h2>
	<p>
	";
	$query_w = "SELECT tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans, tag_unique_hits, tag_unique_hits_ipblock FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_language=$l_mysql ORDER BY tag_unique_hits DESC LIMIT 0,20";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_tag_unique_id, $get_tag_unique_language, $get_tag_unique_title, $get_tag_unique_title_clean, $get_tag_unique_no_of_workout_plans, $get_tag_unique_hits, $get_tag_unique_hits_ipblock) = $row_w;

		echo"
		<a href=\"$root/workout_plans/browse_weekly_workout_plans_by_tag.php?tag=$get_tag_unique_title_clean&amp;l=$l\" class=\""; 
		if($tag == "$get_tag_unique_title_clean"){ echo"btn"; } else{ echo"btn_default"; } echo"\">$get_tag_unique_title</a>
		";

		// Update counter
		if($tag == "$get_tag_unique_title_clean"){
			
			$inp_tag_unique_hits = $get_tag_unique_hits+1;

			$inp_my_ip = $_SERVER['REMOTE_ADDR'];
			$inp_my_ip = output_html($inp_my_ip);
			$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

			if($get_tag_unique_hits_ipblock != "$inp_my_ip"){
				$inp_tag_unique_hits = $get_tag_unique_hits+1;
				$result_u = mysqli_query($link, "UPDATE $t_workout_plans_weekly_tags_unique SET tag_unique_hits=$inp_tag_unique_hits, tag_unique_hits_ipblock=$inp_my_ip_mysql  WHERE tag_unique_id=$get_tag_unique_id");


			}
		}


	} // browse tags
	echo"
	</p>
<!-- //Tags -->

<!-- Show last workout plans -->
	
	";	
	//  
	$x = 0;
	$tag_mysql = quote_smart($link, $tag);
	$query = "SELECT tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id FROM $t_workout_plans_weekly_tags WHERE tag_language=$l_mysql AND tag_title_clean=$tag_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_tag_id, $get_tag_weekly_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row;

		// Workout plans
		$query_w = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_title, workout_weekly_title_clean, workout_weekly_updated, workout_weekly_introduction, workout_weekly_image_path, workout_weekly_image_thumb_big, workout_weekly_image_thumb_medium, workout_weekly_image_file FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_tag_weekly_id";
		$result_w = mysqli_query($link, $query_w);
		$row_w = mysqli_fetch_row($result_w);
		list($get_workout_weekly_id, $get_workout_weekly_user_id, $get_workout_weekly_period_id, $get_workout_weekly_title, $get_workout_weekly_title_clean, $get_workout_weekly_updated, $get_workout_weekly_introduction, $get_workout_weekly_image_path, $get_workout_weekly_image_thumb_big, $get_workout_weekly_image_thumb_medium, $get_workout_weekly_image_file) = $row_w;

		if($get_workout_weekly_image_file != "" && file_exists("$root/$get_workout_weekly_image_path/$get_workout_weekly_image_file")){
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
					
					";
					// Image: 950 x 640
					// Big: 400 x 269
					// Medium: 145 x 98
					if(!(file_exists("$root/$get_workout_weekly_image_path/$get_workout_weekly_image_thumb_big")) OR $get_workout_weekly_image_thumb_big == ""){
						$ext = get_extension("$get_workout_weekly_image_file");
						$get_workout_weekly_image_thumb_big = $get_workout_weekly_title_clean . "_" . $get_workout_weekly_id . "_thumb_400x269." . $ext;
						$get_workout_weekly_image_thumb_big_mysql = quote_smart($link, $get_workout_weekly_image_thumb_big);
						resize_crop_image("400", "269", "$root/$get_workout_weekly_image_path/$get_workout_weekly_image_file", "$root/$get_workout_weekly_image_path/$get_workout_weekly_image_thumb_big");
						$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET workout_weekly_image_thumb_big=$get_workout_weekly_image_thumb_big_mysql WHERE workout_weekly_id=$get_workout_weekly_id") or die(mysqli_error($link));
						echo"<div class=\"info\"><p>Created big thumb</p>UPDATE $t_workout_plans_weekly SET workout_weekly_image_thumb_big=$get_workout_weekly_image_thumb_big_mysql WHERE workout_weekly_id=$get_workout_weekly_id</div>";
					}

					echo"
					<a href=\"weekly_workout_plan_view";
					if($get_stats_user_agent_type == "mobile"){ echo"_mobile"; } 
					echo".php?weekly_id=$get_workout_weekly_id&amp;l=$l\"><img src=\"$root/$get_workout_weekly_image_path/$get_workout_weekly_image_thumb_big\" alt=\"$get_workout_weekly_image_thumb_big\" /></a><br />
					<a href=\"weekly_workout_plan_view";
					if($get_stats_user_agent_type == "mobile"){ echo"_mobile"; } 
					echo".php?weekly_id=$get_workout_weekly_id&amp;l=$l\" class=\"h2\">$get_workout_weekly_title</a>
					</p>
					<p class=\"workout_introduction\">$get_workout_weekly_introduction</p>
				</div>
			";

			
			if($x == 1){ 
				$x = -1;
			}

			$x++;
		} // image
		

	} // tags
	echo"
<!-- //Show last workout plans -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>