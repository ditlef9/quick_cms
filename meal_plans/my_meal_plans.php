<?php 
/**
*
* File: meal_plans/my_meal_plans.php
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
include("_tables_meal_plans.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_meal_plans - $l_meal_plans";
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


	
	echo"
	<h1>$l_my_meal_plans</h1>
	

	<!-- Selector -->

	<div class=\"right\" style=\"text-align: right;\">

		<script>
			\$(function(){
				\$('#inp_language_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
		</script>

		<form method=\"get\" action=\"cc\" enctype=\"multipart/form-data\">
			<p>

			<select name=\"inp_language_select\" id=\"inp_language_select\">
				<option value=\"my_meal_plans.php?l=$l\">- $l_language -</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;



					echo"		";
					echo"<option value=\"my_meal_plans.php?l=$get_language_active_iso_two\""; if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";

				}
			echo"
			</select>

			</p>
        	</form>
	</div>
	<!-- //Selector -->

	<!-- List my meal plans -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_title</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_cal</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_fat</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_carbs</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_proteins</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_views</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_likes</span>
		   </th>
		   <th scope=\"col\" style=\"text-align: center;\">
			<span>$l_comments</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
	";
	
	$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_thumb_74x50, meal_plan_image_thumb_400x269, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_user_id=$my_user_id_mysql AND meal_plan_language=$l_mysql ORDER BY meal_plan_id DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_meal_plan_id, $get_meal_plan_user_id, $get_meal_plan_language, $get_meal_plan_title, $get_meal_plan_title_clean, $get_meal_plan_number_of_days, $get_meal_plan_introduction, $get_meal_plan_total_energy_without_training, $get_meal_plan_total_fat_without_training, $get_meal_plan_total_carb_without_training, $get_meal_plan_total_protein_without_training, $get_meal_plan_total_energy_with_training, $get_meal_plan_total_fat_with_training, $get_meal_plan_total_carb_with_training, $get_meal_plan_total_protein_with_training, $get_meal_plan_average_kcal_without_training, $get_meal_plan_average_fat_without_training, $get_meal_plan_average_carb_without_training, $get_meal_plan_average_protein_without_training, $get_meal_plan_average_kcal_with_training, $get_meal_plan_average_fat_with_training, $get_meal_plan_average_carb_with_training, $get_meal_plan_average_protein_with_training, $get_meal_plan_created, $get_meal_plan_updated, $get_meal_plan_user_ip, $get_meal_plan_image_path, $get_meal_plan_image_thumb_74x50, $get_meal_plan_image_thumb_400x269, $get_meal_plan_image_file, $get_meal_plan_views, $get_meal_plan_views_ip_block, $get_meal_plan_likes, $get_meal_plan_dislikes, $get_meal_plan_rating, $get_meal_plan_rating_ip_block, $get_meal_plan_comments) = $row;

		if(isset($style) && $style == "odd"){
			$style = "";
		}
		else{
			$style = "odd";
		}

		// Date
		$year = substr($get_meal_plan_updated, 0, 4);
		$month = substr($get_meal_plan_updated, 5, 2);
		$day = substr($get_meal_plan_updated, 8, 2);

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
		  <td class=\"$style\">";

			if($get_meal_plan_image_file != "" && file_exists("$root/$get_meal_plan_image_path/$get_meal_plan_image_file")){
				// Thumb
				// Image original image size = 950 x 640
				if(!(file_exists("$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_74x50")) && $get_meal_plan_image_thumb_74x50 != ""){
					$inp_new_x =74;
					$inp_new_y = 50;
					echo"<div class=\"info\"><p>Create thumb <a href=\"$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_74x50\">$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_74x50</a></p></div>\n";
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_meal_plan_image_path/$get_meal_plan_image_file", "$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_74x50");
				}

				echo"
				<div style=\"float: left;padding-right: 10px;\">
					<a href=\"meal_plan_view_$get_meal_plan_number_of_days.php?meal_plan_id=$get_meal_plan_id&amp;l=$l\"><img src=\"$root/$get_meal_plan_image_path/$get_meal_plan_image_thumb_74x50\" alt=\"$get_meal_plan_image_thumb_74x50\" /></a><br />
				</div>";
			}
			echo"
			<div style=\"float: left;\">
				<span>
				<a href=\"meal_plan_view_$get_meal_plan_number_of_days.php?meal_plan_id=$get_meal_plan_id&amp;l=$l\" style=\"font-weight: bold;\">$get_meal_plan_title</a><br />

				$get_meal_plan_introduction</span>
			</div>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_average_kcal_without_training</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_average_fat_without_training</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_average_carb_without_training</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_average_protein_without_training</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_views</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_likes</span>
		  </td>
		  <td class=\"$style\" style=\"text-align: center;\">
			<span>$get_meal_plan_comments</span>
		  </td>
		  <td class=\"$style\">
			<span>$day $month_saying $year</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<span>
			<a href=\"meal_plan_edit.php?meal_plan_id=$get_meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a>
			&middot;
			<a href=\"meal_plan_delete.php?meal_plan_id=$get_meal_plan_id&amp;l=$l\">$l_delete</a>
			</span>
		 </td>
		</tr>
		";

	} // while

	echo"
	 </tbody>
	</table>
	<!-- //List all meal plans -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=meal_plans/my_meal_plans.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>