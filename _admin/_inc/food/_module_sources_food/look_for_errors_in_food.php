<?php
/**
*
* File: _food/open_sub_category_nutritional_facts_eu.php
* Version 1.0.0.
* Date 09:51 10.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
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
include("$root/_admin/_data/logo.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_open_sub_category_nutritional_facts_x.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

$website_title = "$l_look_for_errors_in_food - $get_current_title_value";


/*- Headers ---------------------------------------------------------------------------------- */
include("$root/_webdesign/header.php");


echo"
<!-- Headline, buttons, search -->
	<div class=\"food_float_left\">
		
		<!-- Headline -->
			<h1>$l_look_for_errors_in_food</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$get_current_title_value</a>
			&gt;
			<a href=\"look_for_errors_in_food.php?l=$l\">$l_look_for_errors_in_food</a>
			</p>
			<!-- //Where am I ? -->

	</div>
	<div class=\"food_float_right\">
		<!-- Food menu -->
			<p>
			<a href=\"$root/food/my_food.php?l=$l\" class=\"btn_default\">$l_my_food</a>
			<a href=\"$root/food/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
			</p>
		<!-- //Food menu -->
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->

<!-- List all food with errors -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th>
		<span>$l_name</span>
	   </th>
	   <th>
		<span>$l_net_content</span>
	   </th>
	   <th>
		<span>$l_pcs</span>
	   </th>
	   <th>
		<span>$l_error</span>
	   </th>
	  </tr>
	 </thead>
	 <tbody>";

	// Email with errors
	$email_with_errors_body = "";
	$error_count = 0;

	// Get food
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

		// Errors
		$email_with_errors_body_current = "";
		if($get_food_net_content_metric == "" OR $get_food_net_content_metric == "0"){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing net content metric<br /></span>\n";
		}
		if($get_food_net_content_us == "" OR $get_food_net_content_us == "0"){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing net content us<br /></span>\n";
		}
		if($get_food_serving_size_metric == "" OR $get_food_serving_size_metric == "0"){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing serving size metric<br /></span>\n";
		}
		if(($get_food_serving_size_us == "" OR $get_food_serving_size_us == "0") && $get_food_serving_size_metric != "1"){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing serving size us<br /></span>\n";
		}
		if($get_food_serving_size_pcs == "" OR $get_food_serving_size_pcs == "0"){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing serving size pcs<br /></span>\n";
		}
		if($get_food_energy_metric == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing energy metric<br /></span>\n";
		}
		if($get_food_trans_fat_metric == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing trans fat metric<br /></span>\n";
		}

		// Energy per 8 US
		if($get_food_energy_us == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing energy us<br /></span>\n";
		}
		if($get_food_trans_fat_us == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing trans fat us<br /></span>\n";
		}

		// Energy calculated metric
		if($get_food_energy_calculated_metric == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing energy calculated metric<br /></span>\n";
		}
		if($get_food_polyunsaturated_fat_calculated_metric == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing polyunsaturated fat calculated metric<br /></span>\n";
		}

		// Energy calculated us
		if($get_food_energy_calculated_us == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing energy calculated us<br /></span>\n";
		}
		if($get_food_trans_fat_calculated_us == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing trans fat calculated us<br /></span>\n";
		}


		// Energy net content
		if($get_food_energy_net_content == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing energy net content<br /></span>\n";
		}
		if($get_food_trans_fat_net_content == ""){
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing trans fat net content<br /></span>\n";
		}
		
		// Image
		if(file_exists("$root/$get_food_image_path/$get_food_image_a") && $get_food_image_a != ""){
		}
		else{
			$email_with_errors_body_current = $email_with_errors_body_current  . "<span style=\"color: red;\">Missing image<br /></span>\n";
		}

		if($email_with_errors_body_current != ""){
			$email_with_errors_body_current_sum = md5("$email_with_errors_body_current");
			echo"
			  <tr>
			   <td>
				<span>
				<a href=\"view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\">$get_food_manufacturer_name $get_food_name</a>
				</span>
			   </td>
			   <td>
				<span>$get_food_net_content_metric $get_food_net_content_measurement_metric
				($get_food_net_content_us $get_food_net_content_measurement_us)</span>
			   </td>
			   <td>
				<span>$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement:
				$get_food_serving_size_metric $get_food_serving_size_measurement_metric
				($get_food_serving_size_us $get_food_serving_size_measurement_us)</span>
			   </td>
			   <td>
				$email_with_errors_body_current
				<span class=\"grey\"><br />($email_with_errors_body_current_sum)</span>";
				if(($email_with_errors_body_current_sum == "e3787c66cd8832559ac986809023a9ae" OR $email_with_errors_body_current_sum == "ddd4253efd64b06d0d89801186165769" OR $email_with_errors_body_current_sum == "1a1c96474d3f525a92b5fecc16e5546d" OR $email_with_errors_body_current_sum == "e3787c66cd8832559ac986809023a9ae") && $error_count < 5){
					// Missing trans fat metric
					// Missing trans fat us
					// Missing trans fat calculated us
					// Missing trans fat net content
					$time = time();
					echo"
					<span>Trying autofix!</span>
					<iframe src=\"edit_food_general.php?food_id=$get_food_id&amp;l=$l&amp;autosubmit_general_form=1\" width=\"100%\" height=\"200\"></iframe>
					<meta http-equiv=\"refresh\" content=\"6;url=look_for_errors_in_food.php?time=$time&amp;l=$l\">
					
					";
				}
				echo"
			   </td>
			  </tr>
			";


			if($error_count < 5){
				$email_with_errors_body = $email_with_errors_body . "
				  <tr>
				   <td>
					<span>
					<a href=\"$configSiteURLSav/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\">$get_food_manufacturer_name $get_food_name</a>
					</span>
				   </td>
				   <td>
					<span>$get_food_net_content_metric $get_food_net_content_measurement_metric
					($get_food_net_content_us $get_food_net_content_measurement_us)</span>
				   </td>
				   <td>
					<span>$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement:
					$get_food_serving_size_metric $get_food_serving_size_measurement_metric
					($get_food_serving_size_us $get_food_serving_size_measurement_us)</span>
				   </td>
				   <td>
					$email_with_errors_body_current
				   </td>
				   <td>
					<span>
					<a href=\"$configSiteURLSav/food/edit_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\">$l_fix</a>
					</span>
				   </td>
				  </tr>
				";
			}
			$error_count = $error_count+1;
		}
	} // while
	echo"
	 </tbody>
	</table>

	<p>$l_number_of_errors: $error_count</p>

	";
	// Email
	if($error_count > 0){
		// Who is moderator of the week?
		$week = date("W");
		$year = date("Y");

				
		$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
		if($get_moderator_user_id == ""){
			// Create moderator of the week
			include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
		}
		
		$tmp_file = "look_for_errors_in_food_" . $week . "_" . $year . ".txt";
		if(!(file_exists("$root/_cache/$tmp_file"))){
			$myfile = fopen("$root/_cache/$tmp_file", "w") or die("Unable to open file!");
			fwrite($myfile, "");
			fclose($myfile);
			echo"<p>Email to $get_moderator_user_name</p>";

			// Send e-mail to moderators that there is a new user
			$subject = "$error_count food needs fixing at $configWebsiteTitleSav";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			if($logoFileSav != "" && file_exists("$root/$logoPathSav/$logoFileSav")){
				$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
			}
			$message = $message . "<h1>Dear $get_moderator_user_name</h1>\n";
			$message = $message . "<p>$error_count food needs fixing. The following foods have been assigned to you because you are a moderator at $configWebsiteTitleSav.</p>\n\n";
			$message = $message . "\n";
			$message = $message . "	<table class=\"hor-zebra\">\n";
			$message = $message . "	 <thead>\n";
			$message = $message . "	  <tr>\n";
			$message = $message . "	   <th>\n";
			$message = $message . "		<span>$l_name</span>\n";
			$message = $message . "	   </th>\n";
			$message = $message . "	   <th>\n";
			$message = $message . "		<span>$l_net_content</span>\n";
			$message = $message . "	   </th>\n";
			$message = $message . "	   <th>\n";
			$message = $message . "		<span>$l_pcs</span>\n";
			$message = $message . "	   </th>\n";
			$message = $message . "	   <th>\n";
			$message = $message . "		<span>$l_error</span>\n";
			$message = $message . "	   </th>\n";
			$message = $message . "	   <th>\n";
			$message = $message . "		<span>$l_actions</span>\n";
			$message = $message . "	   </th>\n";
			$message = $message . "	  </tr>\n";
			$message = $message . "	 </thead>\n";
			$message = $message . "	 <tbody>\n";
			$message = $message . "$email_with_errors_body\n";
			$message = $message . "	 </tbody>\n";
			$message = $message . "	</table>\n";
			
			$message = $message . "<p>\n\n--<br />\n\n";
			$message = $message . "Yours sincerely<br />\n\n";
			$message = $message . "$configWebsiteWebmasterSav at $configWebsiteTitleSav<br />\n";
			$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$l\">$configSiteURLSav</a></p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";

			// Preferences for Subject field
			$headers_mail[] = 'MIME-Version: 1.0';
			$headers_mail[] = 'Content-type: text/html; charset=utf-8';
			$headers_mail[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			if($configMailSendActiveSav == "1"){
				mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers_mail));
			}

		} // Tmp file doesnt exists
	} // email
	echo"
<!-- //List all food with errors -->
";	

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>