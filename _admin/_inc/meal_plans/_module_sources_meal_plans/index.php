<?php
/**
*
* File: meal_plans/index.php
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
include("_tables_meal_plans.php");

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
$website_title = "$l_meal_plans";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_meal_plans</h1>
<!-- //Headline and language -->

<!-- Meal plan Quick menu -->
	<div style=\"height:10px;\"></div>
	<p>
	<a href=\"$root/meal_plans/my_meal_plans.php?l=$l\" class=\"btn_default\">$l_my_meal_plans</a>
	<a href=\"$root/meal_plans/new_meal_plan.php?l=$l\" class=\"btn_default\">$l_new_meal_plan</a>
	</p>
	<div style=\"clear:both;height:10px;\"></div>
<!-- //Meal plan Quick menu -->


<!-- Show last meal plans -->
	
	";	
	//  
	$x = 0;

	$query_w = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_title, meal_plan_number_of_days, meal_plan_introduction, meal_plan_image_path, meal_plan_image_thumb_74x50, meal_plan_image_thumb_400x269, meal_plan_image_file FROM $t_meal_plans WHERE meal_plan_language=$l_mysql ORDER BY meal_plan_views DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_meal_plan_id, $get_meal_plan_user_id, $get_meal_plan_title, $get_meal_plan_number_of_days, $get_meal_plan_introduction, $get_meal_plan_image_path, $get_meal_plan_image_thumb_74x50, $get_meal_plan_image_thumb_400x269, $get_meal_plan_image_file) = $row_w;

		if($get_meal_plan_image_file != ""){
			// User
			$query_u = "SELECT user_id, user_name, user_alias FROM $t_users WHERE user_id='$get_meal_plan_user_id'";
			$result_u = mysqli_query($link, $query_u);
			$row_u = mysqli_fetch_row($result_u);
			list($get_user_id, $get_user_name, $get_user_alias) = $row_u;


			// Introduction
			$get_meal_plan_introduction_len = strlen($get_meal_plan_introduction);
			if($get_meal_plan_introduction_len > 170){
				$get_meal_plan_introduction = substr($get_meal_plan_introduction, 0, 170);
				$get_meal_plan_introduction = $get_meal_plan_introduction . "...";
			}


			if($x == 0){
				echo"
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
					if($get_meal_plan_image_file != "" && file_exists("$root/$get_meal_plan_image_path/$get_meal_plan_image_file")){
						// 950 x 640

						// Thumb
						// Image original image size = 950 x 640
						if(!(file_exists("$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_400x269")) && $get_meal_plan_image_thumb_400x269 != ""){
						$inp_new_x = 400;
						$inp_new_y = 269;
							echo"<div class=\"info\"><p>Create thumb <a href=\"$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_400x269\">$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_400x269</a></p></div>\n";
							resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_meal_plan_image_path/$get_meal_plan_image_file", "$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_400x269");
						}

						echo"
						<a href=\"meal_plan_view_$get_meal_plan_number_of_days";
						if($get_meal_plan_number_of_days == "1"){ 
							echo"_mobile"; } echo".php?meal_plan_id=$get_meal_plan_id&amp;l=$l\"><img src=\"$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_400x269\" alt=\"$get_meal_plan_image_thumb_400x269\" /></a>
						\n";
					}
					echo"<br />
					<a href=\"meal_plan_view_$get_meal_plan_number_of_days.php?meal_plan_id=$get_meal_plan_id&amp;l=$l\" class=\"h2\">$get_meal_plan_title</a>
					</p>
					<p class=\"meal_plan_introduction\">$get_meal_plan_introduction</p>
				</div>
			";

			
			if($x == 1){
				echo"
				<div class=\"clear\"></div>
				<hr />
				";
				$x = -1;
			}

			$x++;
		} // image
	} // loop

	if($x == 1){
		echo"
				<div class=\"left_right_right\"></div>
				<div class=\"clear\"></div>
		";
	}


	echo"
<!-- //Show last meal plans -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>