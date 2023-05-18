<?php 
/**
*
* File: meal_plans/meal_plan_delete.php
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


/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/meal_plans/ts_new_meal_plan.php");
include("$root/_admin/_translations/site/$l/meal_plans/ts_meal_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['meal_plan_id'])){
	$meal_plan_id = $_GET['meal_plan_id'];
	$meal_plan_id = output_html($meal_plan_id);
}
else{
	$meal_plan_id = "";
}
if(isset($_GET['entry_day_number'])){
	$entry_day_number = $_GET['entry_day_number'];
	$entry_day_number = output_html($entry_day_number);
}
else{
	$entry_day_number = "";
}
if(isset($_GET['entry_meal_number'])){
	$entry_meal_number = $_GET['entry_meal_number'];
	$entry_meal_number = output_html($entry_meal_number);
}
else{
	$entry_meal_number = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_meal_plan - $l_meal_plans";
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

	// Get meal_plan
	$meal_plan_id_mysql = quote_smart($link, $meal_plan_id);
	$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql AND meal_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
	

	if($get_current_meal_plan_id == ""){
		echo"<p>Meal plan not found.</p>";
	}
	else{

		if($process == 1){
			
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				$url = "meal_plan_edit_info.php?l=$l";
				$url = $url . "&ft=error&fm=missing_title";
				header("Location: $url");
				exit;
			}
		
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			$inp_number_of_days = $_POST['inp_number_of_days'];
			$inp_number_of_days = output_html($inp_number_of_days);
			$inp_number_of_days_mysql = quote_smart($link, $inp_number_of_days);
			
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");

			// Update
			$result = mysqli_query($link, "UPDATE $t_meal_plans SET 
							meal_plan_title=$inp_title_mysql,
							meal_plan_title_clean=$inp_title_clean_mysql,
							meal_plan_number_of_days=$inp_number_of_days_mysql,
							meal_plan_introduction=$inp_introduction_mysql
							 WHERE meal_plan_id=$meal_plan_id_mysql AND meal_plan_user_id=$my_user_id_mysql") or die(mysqli_error($link));

 			// Search engine
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='meal_plans' AND index_reference_name='meal_plan_id' AND index_reference_id=$get_current_meal_plan_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$inp_index_title = "$inp_title | $l_meal_plans";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
							index_title=$inp_title_mysql,
							index_short_description=$inp_introduction_mysql, 
							index_updated_datetime='$datetime', 
							index_updated_datetime_print='$datetime_saying'
							 WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			$url = "meal_plan_edit_info.php?meal_plan_id=$get_current_meal_plan_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_meal_plan_title</h1>
	

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

		<!-- Where am I ? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
			&gt;
			<a href=\"meal_plan_view_$get_current_meal_plan_number_of_days.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$get_current_meal_plan_title</a>
			&gt;
			<a href=\"meal_plan_edit.php?meal_plan_id=$get_current_meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a>
			&gt;
			<a href=\"meal_plan_edit_info.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$l_info</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Edit menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a></li>
					<li><a href=\"meal_plan_edit_info.php?meal_plan_id=$meal_plan_id&amp;l=$l\" class=\"selected\">$l_info</a></li>
					<li><a href=\"meal_plan_edit_text.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_text</a></li>
					<li><a href=\"meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Edit menu -->

		<!-- Edit info form -->
			<script>
				\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<form method=\"post\" action=\"meal_plan_edit_info.php?meal_plan_id=$meal_plan_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>$l_title*:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_meal_plan_title\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>$l_introduction*:</b><br />
			<textarea name=\"inp_introduction\" rows=\"5\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_meal_plan_introduction = str_replace("<br />", "\n", $get_current_meal_plan_introduction);
			echo"$get_current_meal_plan_introduction</textarea>
			</p>

			<p><b>$l_number_of_days*:</b><br />
			<select name=\"inp_number_of_days\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"1\""; if($get_current_meal_plan_number_of_days == "1"){ echo" selected=\"selected\""; } echo">$l_one_day</option>
			<option value=\"7\""; if($get_current_meal_plan_number_of_days == "7"){ echo" selected=\"selected\""; } echo">$l_full_week</option>
			</select>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Edit info form -->
		";
	} // meal found
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