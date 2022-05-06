<?php 
/**
*
* File: recipes/view_recipe_missing_main_ingredient_id.php
* Version 1
* Date 11:40 12.02.2022
* Copyright (c) 2022 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Content ---------------------------------------------------------------------------------- */
$year = date("Y");
$note = "Missing main ingredient warning sent $year";
if($get_recipe_id == ""){
	echo"
	<h4>Recipe not found</h4>
	";
}
else{
	if($get_recipe_notes != "$note"){
		// Logo
		include("$root/_admin/_data/logo.php");

		// Update recipe
		$note_mysql = quote_smart($link, $note);
		mysqli_query($link, "UPDATE $t_recipes SET recipe_notes=$note_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));

		// Include publishers language
		include("$root/_admin/_translations/site/$get_recipe_language/recipes/ts_view_recipe_missing_main_ingredient_id.php");
		
		// Get publishers user
		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$get_recipe_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_owner_user_id, $get_owner_user_email, $get_owner_user_name, $get_owner_user_alias, $get_owner_user_password, $get_owner_user_password_replacement, $get_owner_user_password_date, $get_owner_user_salt, $get_owner_user_security, $get_owner_user_rank, $get_owner_user_verified_by_moderator, $get_owner_user_first_name, $get_owner_user_middle_name, $get_owner_user_last_name, $get_owner_user_language, $get_owner_user_country_id, $get_owner_user_country_name, $get_owner_user_city_name, $get_owner_user_timezone_utc_diff, $get_owner_user_timezone_value, $get_owner_user_measurement, $get_owner_user_date_format, $get_owner_user_gender, $get_owner_user_height, $get_owner_user_dob, $get_owner_user_registered, $get_owner_user_registered_time, $get_owner_user_newsletter, $get_owner_user_privacy, $get_owner_user_views, $get_owner_user_views_ipblock, $get_owner_user_points, $get_owner_user_points_rank, $get_owner_user_likes, $get_owner_user_dislikes, $get_owner_user_status, $get_owner_user_login_tries, $get_owner_user_last_online, $get_owner_user_last_online_time, $get_owner_user_last_ip, $get_owner_user_synchronized, $get_owner_user_notes, $get_owner_user_marked_as_spammer) = $row;

		// Send email
		$host = $_SERVER['HTTP_HOST'];
		$subject = "$l_recipe $get_recipe_title $l_is_missing_main_ingredient_lowercase";
			
		$message = "<html>\n";
		$message = $message. "<head>\n";
		$message = $message. "  <title>$subject</title>\n";
		$message = $message. " </head>\n";
		$message = $message. "<body>\n";

		if($logoFileSav != "" && file_exists("$root/$logoPathSav/$logoFileSav")){
			$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
		}
		$message = $message . "<h1>$l_dear $get_owner_user_name</h1>\n\n";
		$message = $message . "<p>$l_your_recipe\n";
		$message = $message . "<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\">$get_recipe_title</a>\n";
		$message = $message . "$l_is_missing_main_ingredient_lowercase.\n";
		$message = $message . "$l_you_can_easily_add_the_recipes_main_ingredient_by_editing_the_recipe.</p>\n";

		$message = $message . "<p>\n";
		$message = $message . "<a href=\"$configSiteURLSav/recipes/edit_recipe_categorization.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language#main_ingredient\" style=\"padding: 4px 10px 4px 10px; margin: 0 3px; background: #fff;border: #cccccc 1px solid; border-radius: 5px;\">$l_edit_recipe</a>\n";
		$message = $message . "</p>\n";

		$message = $message . "<p>--<br />\n";
		$message = $message . "$l_yours_sincerely<br />\n";
		$message = $message . "$configWebsiteTitleSav<br />\n";
		$message = $message . "<a href=\"$configSiteURLSav\">$configSiteURLSav</a>\n";
		$message = $message . "</p>";
		$message = $message. "</body>\n";
		$message = $message. "</html>\n";


		// Preferences for Subject field
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=utf-8';
		$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
		if($configMailSendActiveSav == "1"){
			mail($get_owner_user_email, $subject, $message, implode("\r\n", $headers));
		}
				

		echo"<p>$message </p>";

	} // mail not sent
} // isset recipe
?>