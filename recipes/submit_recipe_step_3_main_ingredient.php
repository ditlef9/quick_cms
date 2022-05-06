<?php 
/**
*
* File: recipes/submit_recipe_step_3_main_ingredient.php
* Version 1.0.0
* Date 2022
* Copyright (c) 2022 Ditlefsen
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
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
	if(!(is_numeric($recipe_id))){
		echo"Recipe id not numeric";
		die;
	}
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_main_ingredient - $l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	

	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);

	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_ingredient_id, recipe_ingredient_title FROM $t_recipes WHERE recipe_user_id=$my_user_id_mysql AND recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_recipe_id, $get_current_recipe_title, $get_current_recipe_category_id, $get_current_recipe_ingredient_id, $get_current_recipe_ingredient_title) = $row;

	if($get_current_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		if($action == ""){
			if($process == 1){

				if(isset($_GET['ingredient_id'])) {
					$ingredient_id = $_GET['ingredient_id'];
					$ingredient_id = output_html($ingredient_id);
					if(!(is_numeric($ingredient_id))){
						echo"ingredient is not numeric";
						die;
					}
				}
				else{
					echo"Missing ingredient id";
					die;
				}
				$ingredient_id_mysql = quote_smart($link, $ingredient_id);	
			
				$query = "SELECT ingredient_id, ingredient_title FROM $t_recipes_main_ingredients WHERE ingredient_id=$ingredient_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_ingredient_id, $get_ingredient_title) = $row;
	
				if($get_ingredient_id == ""){
					$url = "submit_recipe_step_3_main_ingredients.php?recipe_id=$get_current_recipe_id&l=$l&ft=error&fm=not_found";
					header("Location: $url");
					exit;
				}
			
				// Get translation
				$l_mysql = quote_smart($link, $l);
				$query_translation = "SELECT translation_id, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_ingredient_id=$get_ingredient_id AND translation_language=$l_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_translation_id, $get_translation_value) = $row_translation;
				
				// Inputs
				$inp_ingredient_id_mysql = quote_smart($link, $get_ingredient_id);
				$inp_ingredient_title_mysql = quote_smart($link, $get_translation_value);
				
				// Update
				mysqli_query($link, "UPDATE $t_recipes SET 
							recipe_ingredient_id=$inp_ingredient_id_mysql, 
							recipe_ingredient_title=$inp_ingredient_title_mysql
							WHERE recipe_id=$get_current_recipe_id") or die(mysqli_error($link));


				// Header
				$url = "submit_recipe_step_4_directions.php?recipe_id=$get_current_recipe_id&l=$l";
				header("Location: $url");
				exit;
			}


			echo"
			<h1>$l_main_ingredient</h1>
	
		

			<!-- Feedback -->
			";
			if($ft != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Select main ingredient -->

				<p>
				<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_current_recipe_id&amp;action=suggest_new_main_ingredient&amp;l=$l\">$l_suggest_new_main_ingredient</a>
				</p>

				<div class=\"vertical\">
					<ul>";


					$l_mysql = quote_smart($link, $l);
					$query = "SELECT translation_id, translation_ingredient_id, translation_language, translation_value FROM $t_recipes_main_ingredients_translations WHERE translation_language=$l_mysql ORDER BY translation_value ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_translation_id, $get_translation_ingredient_id, $get_translation_language, $get_translation_value) = $row;

						// Get icon
						$query_m = "SELECT ingredient_id, ingredient_icon_path, ingredient_icon_18x18_inactive, ingredient_icon_18x18_active, ingredient_category_id FROM $t_recipes_main_ingredients WHERE ingredient_id=$get_translation_ingredient_id";
						$result_m = mysqli_query($link, $query_m);
						$row_m = mysqli_fetch_row($result_m);
						list($get_ingredient_id, $get_ingredient_icon_path, $get_ingredient_icon_18x18_inactive, $get_ingredient_icon_18x18_active, $get_ingredient_category_id) = $row_m;
						if($get_ingredient_id == ""){
							// Delete translation
							// mysqli_query($link, "DELETE FROM $t_recipes_main_ingredients_translations WHERE translation_id=$get_translation_id") or die(mysqli_error($link));
							echo"<li><p><b>DELETED TRANSLATION</b></p></li>\n";
						}

						if($get_ingredient_category_id == "$get_current_recipe_category_id"){
							if($get_translation_ingredient_id == "$get_current_recipe_ingredient_id"){
								echo"<li><a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_current_recipe_id&amp;ingredient_id=$get_translation_ingredient_id&amp;l=$l&amp;process=1\" style=\"font-weight: bold;\">";
								if(file_exists("$root/$get_ingredient_icon_path/$get_ingredient_icon_18x18_active") && $get_ingredient_icon_18x18_active != ""){
									echo"<img src=\"$root/$get_ingredient_icon_path/$get_ingredient_icon_18x18_active\" alt=\"$get_ingredient_icon_18x18_active\" />\n";
								}
							}
							else{
								echo"<li><a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_current_recipe_id&amp;ingredient_id=$get_translation_ingredient_id&amp;l=$l&amp;process=1\">";
								if(file_exists("$root/$get_ingredient_icon_path/$get_ingredient_icon_18x18_inactive") && $get_ingredient_icon_18x18_inactive != ""){
									echo"<img src=\"$root/$get_ingredient_icon_path/$get_ingredient_icon_18x18_inactive\" alt=\"$get_ingredient_icon_18x18_inactive\" />\n";
								}
							}
							echo"$get_translation_value</a>\n";
						}
					}
					echo"
					</ul>
				</div>
			<!-- //Select main ingredient -->


			";
	
		} // action == ""
		elseif($action == "suggest_new_main_ingredient"){
			if($process == "1"){
				$inp_suggestion = $_POST['inp_suggestion'];
				$inp_suggestion = output_html($inp_suggestion);
				if(empty($inp_suggestion)){
					$url = "submit_recipe_step_3_main_ingredient.php?recipe_id=$get_current_recipe_id&action=suggest_new_main_ingredient&l=$l&ft=error&fm=missing_suggestion";
					header("Location: $url");
					exit;
				}


				// Dates
				$week = date("W");
				$year = date("Y");

				// Who is moderator of the week?
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

				// Mail
				$subject = "New main ingredient suggestion $inp_suggestion";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p>Dear $get_moderator_user_name,</p>\n\n";
				$message = $message . "<p>User $my_user_id has suggested a new main ingredient.</p>\n";
				$message = $message . "<table>\n";
				$message = $message . " <tr>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span><b>Suggestion:</b></span>\n";
				$message = $message . "  </td>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span>$inp_suggestion</span>\n";
				$message = $message . "  </td>\n";
				$message = $message . " </tr>\n";
				$message = $message . " <tr>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span><b>Language:</b></span>\n";
				$message = $message . "  </td>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span>$l</span>\n";
				$message = $message . "  </td>\n";
				$message = $message . " </tr>\n";
				$message = $message . " <tr>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span><b>Recipe:</b></span>\n";
				$message = $message . "  </td>\n";
				$message = $message . "  <td>\n";
				$message = $message . "		<span><a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_current_recipe_id&amp;l=$l\">$get_current_recipe_title</a></span>\n";
				$message = $message . "  </td>\n";
				$message = $message . " </tr>\n";
				$message = $message . "</table>\n\n";

				$message = $message . "<p><a href=\"$configControlPanelURLSav/index.php?open=recipes&amp;page=main_ingredients&amp;action=add\">Add main ingredient</a></p>\n\n";

				$message = $message . "<p>\n\n";
				$message = $message . "--<br />\n";
				$message = $message . "Yours sincerely<br />\n";
				$message = $message . "$configWebsiteTitleSav<br />";
				$message = $message . "<a href=\"$configSiteURLSav\">$configWebsiteTitleSav</a></p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";


				// Preferences for Subject field
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromEmailSav <" . $configFromEmailSav . ">";
				mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));

				// Header
				$url = "submit_recipe_step_4_directions.php?recipe_id=$get_current_recipe_id&l=$l&ft=success&fm=suggestion_sent_to_moderator";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$l_suggest_new_main_ingredient</h1>

			<!-- Form -->

				<!-- Focus -->
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_suggestion\"]').focus();
					});
				</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_current_recipe_id&amp;action=suggest_new_main_ingredient&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


				<p><b>$l_suggestion:</b><br />
				<input type=\"text\" name=\"inp_suggestion\" value=\"\" size=\"25\" />
				</p>

				<p>
				<input type=\"submit\" value=\"$l_send\" class=\"btn_default\" />
				</p>
				</form>

			<!-- //Form -->
			";
		}
	}// recipe found

}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/submit_recipe.php\">
	";
}
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>