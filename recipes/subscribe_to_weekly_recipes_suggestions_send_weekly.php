<?php 
/**
*
* File: recipes/subscribe_to_weekly_recipes_suggestions_send_weekly.php
* Version 1.0.0
* Date 14:12 12.02.2022
* Copyright (c) 2022 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Translation ------------------------------------------------------------------------ */


/*- Content ---------------------------------------------------------------------------------- */
if(isset($day_of_week)){

	// Dates
	$date = date("Y-m-d");
	$year = date("Y");
	$week = date("W");
	$next_week_year = date("W/Y", strtotime("+1 week"));
	

	// Test
	// $rand = rand(0,9);
	// $date = "2022-03-0$rand";

	// Logo
	include("$root/_admin/_data/logo.php");

	// Check if sent or not
	$query = "SELECT subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, subscription_send_email, subscription_post_blog, subscription_key, subscription_last_run_date FROM $t_recipes_weekly_subscriptions ORDER BY subscription_last_run_date DESC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_subscription_id, $get_subscription_user_id, $get_subscription_user_email, $get_subscription_user_name, $get_subscription_language, $get_subscription_send_email, $get_subscription_post_blog, $get_subscription_key, $get_subscription_last_run_date) = $row;
	if($get_subscription_id != "" && $date != "$get_subscription_last_run_date"){
		// Update
		mysqli_query($link, "UPDATE $t_recipes_weekly_subscriptions SET subscription_last_run_date='$date' WHERE subscription_id=$get_subscription_id") or die(mysqli_error($link));

		// Language
		$subscription_language_mysql = quote_smart($link, $get_subscription_language);
		include("$root/_admin/_translations/site/$get_subscription_language/recipes/ts_subscribe_to_weekly_recipes_suggestions_send_weekly.php");
		



		$subject = decode_national_letters("$l_recipes_suggestions_for $next_week_year");

		$message = "<html>\n";
		$message = $message. "<head>\n";
		$message = $message. "  <title>$subject</title>\n";
		$message = $message. "  <style>
/*- Misc ----------------------------------------------------------- */
div.clear{
	clear: both;
}
p{
	font-size: 110%;
	margin: 8px 0px 8px 0px;
	padding: 0px 0px 0px 0px;
}

/*- Header -------------------------------------------------------- */
div.recipes_header{
	margin-bottom: 10px;
}
div.recipes_header > h2{
	font-size: 130%;
}
div.recipes_header > p{
	font-size: 120%;
}

/*- Recipes ------------------------------------------------------- */
div.recipes_day_wrapper{
	border-radius: 5px;
	padding: 6px 6px 6px 6px;
	margin-bottom: 10px;
}
h2.recipes_day_title{
	margin-bottom: 0;
	padding-bottom: 0;
}

	/*- Recipes content --------------------------------------- */
	div.recipes_day_row{
		display: flex;
		flex-wrap: wrap;
	}
	div.recipes_day_column_left{
		flex: 20%;
		padding: 0px 10px 0px 0px;
	}
	div.recipes_day_column_left > p > a > img{
		border-radius: 5%;
	}
	div.recipes_day_column_right{
		flex: 80%;
	}
	a.recipe_title{
		text-decoration: none;
		font-size: 110%;
		font-weight: bold;
	}
	
	
	@media screen and (max-width: 52.375em) {
		div.recipes_day_row{
			display: inline;
		}
		p.recipe_introduction{
			padding-bottom: 15px;
		}

	}
\n";

		$message = $message. "  </style>\n";
		$message = $message. " </head>\n";
		$message = $message. "<body>\n";

		// Mail header
		$message = $message . "
		<!-- Header -->
			<div class=\"recipes_header\">\n";

			if($logoFileSav != "" && file_exists("$root/$logoPathSav/$logoFileSav")){
				$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
			}
			$message = $message . "
			<h2>$l_dear $get_subscription_user_name</h2>
				<p>$l_here_are_your_recipe_suggestions_for $next_week_year.</p>
			</div>
		<!-- Header -->\n";
	
		$days_array = array("$l_monday", "$l_tuesday", "$l_wednesday", "$l_thursday", "$l_friday", "$l_saturday", "$l_sunday");
		
		$new_date = new DateTime;
		$new_date->modify('monday next week'); // returns Sunday, Feb 4 2018

		// Mail body
		for($x=0;$x<sizeof($days_array);$x++){
			$day_no = $x+1;
			$date_saying = $new_date->format('j M'); 
			$message = $message . "
			<!-- Recipe day -->
				<h2 class=\"recipes_day_title\">$days_array[$x] $date_saying</h2>

			";

			// Fetch unique categories for this day from users checked ingredients
			$query_c = "SELECT DISTINCT checked_category_id, checked_category_name FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_user_id=$get_subscription_user_id AND checked_day_no=$day_no";
			$result_c = mysqli_query($link, $query_c);
			while($row_c = mysqli_fetch_row($result_c)) {
				list($get_checked_category_id, $get_checked_category_name) = $row_c;
				$message = $message . "
				<h3 class=\"recipes_category_title\">$get_checked_category_name</h3>
				";
	
				// Find unique ingredients
				$ingredients_ids = "";
				$query_i = "SELECT DISTINCT checked_ingredient_id, checked_ingredient_title FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_user_id=$get_subscription_user_id AND checked_day_no=$day_no AND checked_category_id=$get_checked_category_id";
				$result_i = mysqli_query($link, $query_i);
				while($row_i = mysqli_fetch_row($result_i)) {
					list($get_checked_ingredient_id, $get_checked_ingredient_title) = $row_i;

					if($ingredients_ids == ""){
						$ingredients_ids = "$get_checked_ingredient_id";
					}
					else{
						$ingredients_ids = $ingredients_ids . ",$get_checked_ingredient_id";
					}


				} // ingredients unique

				// Find 2 unique recipes per category (example 2 for breakfast and two for dinner)
				$query_r = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image_h_a, recipe_thumb_h_a_278x156 FROM $t_recipes WHERE recipe_category_id=$get_category_id AND recipe_language=$subscription_language_mysql AND recipe_ingredient_id IN ($ingredients_ids) AND recipe_published=1 ORDER BY rand() LIMIT 2";
				
				$result_r = mysqli_query($link, $query_r);
				while($row_r = mysqli_fetch_row($result_r)) {
					list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156) = $row_r;
	
					if($get_recipe_image_h_a != ""){
								$message = $message. "		<div class=\"recipes_day_row\">\n";
								$message = $message. "			<div class=\"recipes_day_column_left\">\n";
								$message = $message. "				<p>\n";
								$message = $message. "				<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$configSiteURLSav/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />\n";
								$message = $message. "				</p>\n";
								$message = $message. "			</div> <!-- //recipes_day_column_left -->\n";

								$message = $message. "			<div class=\"recipes_day_column_right\">\n";
								$message = $message. "				<p class=\"recipe_introduction\">\n";
								$message = $message. "				<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"recipe_title\">$get_recipe_title</a><br />\n";
								$message = $message. "				$get_recipe_introduction\n";
								$message = $message. "				</p>\n";
								$message = $message. "			</div> <!-- //recipes_day_column_right -->\n";
								$message = $message. "		</div> <!-- //recipes_day_row -->\n";
							$message = $message . "		<div class=\"clear\"></div>";
					}
				}

			} // categories unique


			$message = $message . "

				<div class=\"recipes_day_wrapper\">
				</div> <!-- //recipes_day_wrapper -->
			<!-- //Recipe day -->
			";

			$new_date->modify('+1 day'); // next day
		} // days array


		$message = $message . "<p>---<br />\n";
		$message = $message . "$l_yours_sincerely<br />\n";
		$message = $message . "$configWebsiteWebmasterSav<br />\n";
		$message = $message . "$configWebsiteTitleSav<br />\n";
		$message = $message . "</p>\n";
		$message = $message . "<p>\n";
		$message = $message . "<a href=\"$configSiteURLSav/recipes/subscribe_to_weekly_recipes_suggestions_unsubscribe.php?subscription_id=$get_subscription_id&amp;key=$get_subscription_key&amp;l=$get_subscription_language\">$l_unsubscribe</a>\n";
		$message = $message . "</p>\n";
		$message = $message. "</body>\n";
		$message = $message. "</html>\n";


		// Preferences for Subject field
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=utf-8';
		$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
		mail($get_subscription_user_email, $subject, $message, implode("\r\n", $headers));


	} // send
}


?>