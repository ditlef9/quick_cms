<?php 
/**
*
* File: recipes/subscribe_to_weekly_recipes_suggestions.php
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

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_subscribe_to_weekly_recipes_suggestions - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_registered_date_saying, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;
	

	
	// Get my subscription
	$query = "SELECT subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, subscription_send_email, subscription_post_blog FROM $t_recipes_weekly_subscriptions WHERE subscription_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_subscription_id, $get_current_subscription_user_id, $get_current_subscription_user_email, $get_current_subscription_user_name, $get_current_subscription_language, $get_current_subscription_send_email, $get_current_subscription_post_blog) = $row;
	if($get_current_subscription_language == ""){ $get_current_subscription_language = "$l"; } 

	if($action == ""){
		if($process == "1"){
			$inp_send_email = $_POST['inp_send_email'];
			$inp_send_email = output_html($inp_send_email);
			$inp_send_email_mysql = quote_smart($link, $inp_send_email);

			$inp_post_blog = $_POST['inp_post_blog'];
			$inp_post_blog = output_html($inp_post_blog);
			$inp_post_blog_mysql = quote_smart($link, $inp_post_blog);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);

			if($inp_send_email == "0" && $inp_post_blog == "0"){
				// Delete
	
				if($get_current_subscription_id == ""){
					$url = "subscribe_to_weekly_recipes_suggestions.php?l=$l&ft=info&fm=no_changes";
					header("Location: $url");
					exit;
				}

				// Delete
				mysqli_query($link, "DELETE FROM $t_recipes_weekly_subscriptions WHERE subscription_user_id=$my_user_id_mysql") or die(mysqli_error($link));
				mysqli_query($link, "DELETE FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_user_id=$my_user_id_mysql") or die(mysqli_error($link));

				// Header
				$url = "subscribe_to_weekly_recipes_suggestions.php?l=$l&ft=info&fm=subscription_deleted";
				header("Location: $url");
				exit;

			} // Delete
			else{
				// Insert or update
				$year = date("Y");
				$week = date("W");
				$day = date("d");
				$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);

				if($get_current_subscription_id == ""){

					// Generate key
					$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    					$charactersLength = strlen($characters);
    					$key = '';
    					for ($i = 0; $i < 6; $i++) {
        					$key .= $characters[rand(0, $charactersLength - 1)];
    					}
					$inp_key_mysql = quote_smart($link, $key);


					mysqli_query($link, "INSERT INTO $t_recipes_weekly_subscriptions 
					(subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, 
					subscription_send_email, subscription_post_blog, subscription_key) 
					VALUES 
					(NULL, $my_user_id_mysql, $inp_my_user_email_mysql, $inp_my_user_name_mysql, $inp_language_mysql, 
					$inp_send_email_mysql, $inp_post_blog_mysql, $inp_key_mysql)")
					or die(mysqli_error($link));
	
					// Get ID
					$query = "SELECT subscription_id, subscription_user_id, subscription_user_email, subscription_user_name, subscription_language, subscription_send_email, subscription_post_blog FROM $t_recipes_weekly_subscriptions WHERE subscription_user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_subscription_id, $get_current_subscription_user_id, $get_current_subscription_user_email, $get_current_subscription_user_name, $get_current_subscription_language, $get_current_subscription_send_email, $get_current_subscription_post_blog) = $row;



					// Insert two of all dinnars
					$query_m = "SELECT ingredient_id, ingredient_title, ingredient_title_clean, ingredient_category_id, ingredient_category_name FROM $t_recipes_main_ingredients";
					$result_m = mysqli_query($link, $query_m);
					while($row_m = mysqli_fetch_row($result_m)) {
						list($get_ingredient_id, $get_ingredient_title, $get_ingredient_title_clean, $get_ingredient_category_id, $get_ingredient_category_name) = $row_m;

						// Get translation of this category name
						$query = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_ingredient_category_id AND category_translation_language=$inp_language_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_category_translation_id, $get_category_translation_title) = $row;


						$inp_category_name_mysql = quote_smart($link, $get_category_translation_title);
						$inp_ingredient_title_mysql = quote_smart($link, $get_ingredient_title);

						if($get_ingredient_category_name == "Dinner"){
							// Insert for all days
							for($x=0;$x<7;$x++){
								$day_no = $x+1;
								mysqli_query($link, "INSERT INTO $t_recipes_weekly_subscriptions_checked_ingredients 
								(checked_id, checked_subscription_id, checked_user_id, checked_day_no, checked_category_id, 
								checked_category_name, checked_ingredient_id, checked_ingredient_title) 
								VALUES 
								(NULL, $get_current_subscription_id, $my_user_id_mysql, $day_no, $get_ingredient_category_id, 
								$inp_category_name_mysql, $get_ingredient_id, $inp_ingredient_title_mysql)")
								or die(mysqli_error($link));
							}




						} // dinner
					}


				}
				else{
					mysqli_query($link, "UPDATE $t_recipes_weekly_subscriptions SET
								subscription_user_email=$inp_my_user_email_mysql, 
								subscription_user_name=$inp_my_user_name_mysql, 
								subscription_language=$inp_language_mysql, 
								subscription_send_email=$inp_send_email_mysql, 
								subscription_post_blog=$inp_post_blog_mysql
								WHERE subscription_id=$get_current_subscription_id") or die(mysqli_error($link));

				}
				// Header
				$url = "subscribe_to_weekly_recipes_suggestions.php?l=$l&ft=info&fm=subscription_updated";
				header("Location: $url");
				exit;
				
			} // Insert or update

		}
		echo"
		<h1>$l_subscribe_to_weekly_recipes_suggestions</h1>
	
		<!-- Where am I? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"subscribe_to_weekly_recipes_suggestions.php?l=$l\">$l_subscribe_to_weekly_recipes_suggestions</a>
			</p>
		<!-- Where am I? -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Form -->

			<form method=\"post\" action=\"subscribe_to_weekly_recipes_suggestions.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<h2>$l_subscribe_to_recipes</h2>
			<p><b>$l_get_weekly_emails_with_recipes</b><br />
			<input type=\"radio\" name=\"inp_send_email\" value=\"1\""; if($get_current_subscription_send_email == "1"){ echo" checked=\"checked\""; } echo" />
			$l_yes
			&nbsp;
			<input type=\"radio\" name=\"inp_send_email\" value=\"0\""; if($get_current_subscription_send_email == "0" OR $get_current_subscription_user_email == ""){ echo" checked=\"checked\""; } echo" />
			$l_no
			</p>
	
			<p><b>$l_post_weekly_blog_posts_with_recipes</b><br />
			<input type=\"radio\" name=\"inp_post_blog\" value=\"1\""; if($get_current_subscription_post_blog == "1"){ echo" checked=\"checked\""; } echo" />
			$l_yes
			&nbsp;
			<input type=\"radio\" name=\"inp_post_blog\" value=\"0\""; if($get_current_subscription_post_blog == "0" OR $get_current_subscription_post_blog == ""){ echo" checked=\"checked\""; } echo" />
			$l_no
			</p>
	
			<p><b>$l_language:</b><br />
			<select name=\"inp_language\">";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active ORDER BY language_active_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
				echo"			";
				echo"<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_subscription_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p>
			<input type=\"submit\" value=\"$l_update\" class=\"btn\" />
			</p>

			";
			if($get_current_subscription_send_email == "1" OR $get_current_subscription_post_blog == "1"){ 
				echo"
				<hr />
				<h2>$l_customization</h2>

				";
				$days_array = array("$l_monday", "$l_tuesday", "$l_wednesday", "$l_thursday", "$l_friday", "$l_saturday", "$l_sunday");
				for($x=0;$x<sizeof($days_array);$x++){
					$day_no = $x+1;
					echo"
					<table class=\"hor-zebra\">
					 <thead>
					  <tr>  
					   <th>  
						<h3 style=\"color: blue\">$days_array[$x]</h3>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					<!-- Categories -->
					";
					$query_r = "SELECT category_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_translation_language=$l_mysql ORDER BY category_translation_title ASC";
					$result_r = mysqli_query($link, $query_r);
					while($row_r = mysqli_fetch_row($result_r)) {
						list($get_category_id, $get_category_translation_title) = $row_r;
						echo"
						  <tr>
						   <td>
						
							<p><em>$get_category_translation_title</em><br />
						</p>
							<div class=\"row\">
						";

						// All main ingredients
						$query_m = "SELECT translation_ingredient_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_language=$l_mysql ORDER BY translation_value ASC";
						$result_m = mysqli_query($link, $query_m);
						while($row_m = mysqli_fetch_row($result_m)) {
							list($get_translation_ingredient_id, $get_translation_value) = $row_m;

							$query = "SELECT ingredient_id, ingredient_category_id FROM $t_recipes_main_ingredients WHERE ingredient_id=$get_translation_ingredient_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_ingredient_id, $get_ingredient_category_id) = $row;
	
							if($get_ingredient_category_id == "$get_category_id"){
								$inp_text_field = "inp_day_" . $day_no . "_category_" . $get_category_id . "_ingredient_" . $get_translation_ingredient_id;
								$inp_feedback_field = "inp_day_" . $day_no . "_category_" . $get_category_id . "_ingredient_" . $get_translation_ingredient_id . "_feedback";

								// Se if it is checked
								$query = "SELECT checked_id, checked_subscription_id, checked_user_id, checked_day_no, checked_category_id, checked_category_name, checked_ingredient_id, checked_ingredient_title FROM $t_recipes_weekly_subscriptions_checked_ingredients WHERE checked_subscription_id=$get_current_subscription_id AND checked_day_no=$day_no AND checked_category_id=$get_category_id AND checked_ingredient_id=$get_ingredient_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_checked_id, $get_checked_subscription_id, $get_checked_user_id, $get_checked_day_no, $get_checked_category_id, $get_checked_category_name, $get_checked_ingredient_id, $get_checked_ingredient_title) = $row;


								echo"
								<div class=\"columns_two\">
									<span>
									<input type=\"checkbox\" name=\"$inp_text_field\" class=\"update_customization_field\" "; if($get_checked_id != ""){ echo" checked=\"checked\""; } echo" /> $get_translation_value
									</span>
									<span class=\"$inp_feedback_field\" style=\"color:grey;\"></span>
								</div> <!-- //column -->
								";
							}

						}
						echo"
							</div> <!-- //row -->
						   </td>
						  </tr>";
					}
					echo"
					<!-- //Categories -->
					 </tbody>
					</table>
					<div style=\"height: 10px;\"></div>
					";
				} // for days
			} // customization
			echo"


			<!-- Update customization script -->
				<script language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('.update_customization_field').change(function() {

							var name = \$(this).attr('name');
							var value = 0;
							if(this.checked) {
								var value = 1;
							}

							var feedbackField = name + '_feedback';

							
 							// forming the queryString
      							var data            = 'l=$l&inp_name='+ name + '&inp_value=' + value;

        						// if searchString is not empty
        						// ajax call
            						\$.ajax({
                						type: \"POST\",
               							url: \"subscribe_to_weekly_recipes_suggestions_jquery_update_checked.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
									\$(\".\" + feedbackField).html(''); 
								},
               							success: function(html){
                    							\$(\".\" + feedbackField).html(html);
              							}
            						});
							\$(this).css(\"color\", \"green\");
							\$(this).css(\"border-color\", \"green\");
        						return false;
            					});
         				   });
				</script>
			<!-- //Update customization script -->


		<!-- //Form -->
		";
	} // mode == ""
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=recipes/subscribe_to_weekly_recipes_suggestions.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>