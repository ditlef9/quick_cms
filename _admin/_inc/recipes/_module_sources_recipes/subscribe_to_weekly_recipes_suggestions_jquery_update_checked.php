<?php 
/**
*
* File: recipes/subscribe_to_weekly_recipes_suggestions_jquery_update_checked.php
* Version 1.0.0
* Date 14:12 12.02.2022
* Copyright (c) 2022 Localhost
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
// include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");

/*- Variables ------------------------------------------------------------------------- */

$tabindex = 0;
$l_mysql = quote_smart($link, $l);



/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// dates
	$hms = date("H:m:s");

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_registered_date_saying, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;
	
	// Get language
	$l = $_SESSION['l'];
	$l = output_html($l);
	$l_mysql = quote_smart($link, $l);


	// Get my subscription
	$query = "SELECT subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, subscription_send_email, subscription_post_blog FROM $t_recipes_weekly_subscriptions WHERE subscription_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_subscription_id, $get_current_subscription_user_id, $get_current_subscription_user_email, $get_current_subscription_user_name, $get_current_subscription_language, $get_current_subscription_send_email, $get_current_subscription_post_blog) = $row;


	if($get_current_subscription_id == ""){
		echo"<p>Subscription not found</p>";
	}
	else{
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);

		$inp_value = $_POST['inp_value'];
		$inp_value = output_html($inp_value);

		if(empty($inp_name) OR $inp_value == ""){
			echo"Name or value is empty\n";
		}
		else{
			$inp_name_array = explode("_", $inp_name);

			$inp_day_no = $inp_name_array[2];
			$inp_category_id = $inp_name_array[4];
			$inp_ingredient_id = $inp_name_array[6];

			if(!(is_numeric($inp_day_no)) OR !(is_numeric($inp_category_id)) OR !(is_numeric($inp_ingredient_id)) OR !(is_numeric($inp_value))){
				echo"<div class=\"error\"><p>Some of the fields are not numeric.</p></div>\n";
			}
			else{

				$inp_day_no_mysql = quote_smart($link, $inp_day_no);
				$inp_category_id_mysql = quote_smart($link, $inp_category_id);
				$inp_ingredient_id_mysql = quote_smart($link, $inp_ingredient_id);
				$inp_value_mysql = quote_smart($link, $inp_value);
			
				// Check if exists
				$query = "SELECT checked_id, checked_subscription_id, checked_user_id, checked_day_no, checked_category_id, checked_category_name, checked_ingredient_id, checked_ingredient_title FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_subscription_id=$get_current_subscription_id AND checked_day_no=$inp_day_no_mysql AND checked_category_id=$inp_category_id_mysql AND checked_ingredient_id=$inp_ingredient_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_checked_id, $get_checked_subscription_id, $get_checked_user_id, $get_checked_day_no, $get_checked_category_id, $get_checked_category_name, $get_checked_ingredient_id, $get_checked_ingredient_title) = $row;

				if($get_checked_id == ""){
					// Get ingredient
					$query = "SELECT ingredient_id, ingredient_title, ingredient_title_clean, ingredient_category_id, ingredient_category_name FROM $t_recipes_main_ingredients WHERE ingredient_id=$inp_ingredient_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_ingredient_id, $get_ingredient_title, $get_ingredient_title_clean, $get_ingredient_category_id, $get_ingredient_category_name) = $row;

					// Get translation of this category name
					$query = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_ingredient_category_id AND category_translation_language=$l_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_category_translation_id, $get_category_translation_title) = $row;


					$inp_category_name_mysql = quote_smart($link, $get_category_translation_title);
					$inp_ingredient_title_mysql = quote_smart($link, $get_ingredient_title);
					
					// Insert
					mysqli_query($link, "INSERT INTO $t_recipes_weekly_subscriptions_checked_ingredients 
					(checked_id, checked_subscription_id, checked_user_id, checked_day_no, checked_category_id, 
					checked_category_name, checked_ingredient_id, checked_ingredient_title) 
					VALUES 
					(NULL, $get_current_subscription_id, $my_user_id_mysql, $inp_day_no_mysql, $inp_category_id_mysql, 
					$inp_category_name_mysql, $inp_ingredient_id_mysql, $inp_ingredient_title_mysql)")
					or die(mysqli_error($link));
					echo"&#10004; Stored";
					
				}
				else{
					// Delete
					mysqli_query($link, "DELETE FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_id=$get_checked_id") or die(mysqli_error($link));
					echo"&#9249; Removed";
				}
			} // is numeric
		} // name or value exists
	} // Subscription found
}
else{
	echo"<p>Not logged in</p>";
}


?>