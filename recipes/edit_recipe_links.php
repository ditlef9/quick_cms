<?php 
/**
*
* File: recipes/edit_recipe_links.php
* Version 1.0.0
* Date 13:43 18.11.2017
* Copyright (c) 2011-2017 Localhost
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
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

$l_mysql = quote_smart($link, $l);



/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

// Translations
include("$root/_admin/_translations/site/$l/recipes/ts_edit_recipe.php");

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$l_edit_recipe $get_recipe_title - $l_my_recipes";
}

include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{
	if(isset($_SESSION['user_id'])){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

		// Access to recipe edit
		if($get_recipe_user_id == "$my_user_id" OR $get_user_rank == "admin"){


			if($process == 1){
				// Delete all old links
				$result = mysqli_query($link, "DELETE FROM $t_recipes_links WHERE link_recipe_id=$get_recipe_id");
				
				// Lang
				$inp_language_mysql = quote_smart($link, $get_recipe_language);

		$inp_title_a = $_POST['inp_title_a'];
		$inp_title_a = output_html($inp_title_a);
		$inp_title_a_mysql = quote_smart($link, $inp_title_a);

		$inp_url_a = $_POST['inp_url_a'];
		$inp_url_a = output_html($inp_url_a);
		$size = strlen($inp_url_a);
		if($size > 3){
			$check = substr($inp_url_a, 0, 3);
			if($check != "htt"){
				$inp_url_a = "http://" . $inp_url_a;
			}
		}
		$inp_url_a_mysql = quote_smart($link, $inp_url_a);

		if($inp_title_a != "" && $inp_url_a != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_a_mysql, $inp_url_a_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}


		$inp_title_b = $_POST['inp_title_b'];
		$inp_title_b = output_html($inp_title_b);
		$inp_title_b_mysql = quote_smart($link, $inp_title_b);

		$inp_url_b = $_POST['inp_url_b'];
		$inp_url_b = output_html($inp_url_b);
		$size = strlen($inp_url_b);
		if($size > 3){
			$check = substr($inp_url_b, 0, 3);
			if($check != "htt"){
				$inp_url_b = "http://" . $inp_url_b;
			}
		}
		$inp_url_b_mysql = quote_smart($link, $inp_url_b);

		if($inp_title_b != "" && $inp_url_b != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_b_mysql, $inp_url_b_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}

		$inp_title_c = $_POST['inp_title_c'];
		$inp_title_c = output_html($inp_title_c);
		$inp_title_c_mysql = quote_smart($link, $inp_title_c);

		$inp_url_c = $_POST['inp_url_c'];
		$inp_url_c = output_html($inp_url_c);
		$size = strlen($inp_url_c);
		if($size > 3){
			$check = substr($inp_url_c, 0, 3);
			if($check != "htt"){
				$inp_url_c = "http://" . $inp_url_c;
			}
		}
		$inp_url_c_mysql = quote_smart($link, $inp_url_c);

		if($inp_title_c != "" && $inp_url_c != ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_links 
			(link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) 
			VALUES 
			(NULL, $inp_language_mysql, $get_recipe_id, $inp_title_c_mysql, $inp_url_c_mysql, '0', '', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}

		// Header
		$url = "edit_recipe_links.php?recipe_id=$recipe_id&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}



	echo"
	<h1>$get_recipe_title</h1>

	
	<!-- You are here -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"my_recipes.php?l=$l#recipe_id=$recipe_id\">$l_my_recipes</a>
			&gt;
			<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$get_recipe_title</a>
			&gt;
			<a href=\"edit_recipe_links.php?recipe_id=$recipe_id&amp;l=$l\">$l_links</a>
			</p>
	<!-- //You are here -->


	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"edit_recipe.php?recipe_id=$recipe_id&amp;l=$l\">$l_general</a></li>
				<li><a href=\"edit_recipe_ingredients.php?recipe_id=$recipe_id&amp;l=$l\">$l_ingredients</a></li>
				<li><a href=\"edit_recipe_categorization.php?recipe_id=$recipe_id&amp;l=$l\">$l_categorization</a></li>
				<li><a href=\"edit_recipe_image.php?recipe_id=$recipe_id&amp;l=$l\">$l_image</a></li>
				<li><a href=\"edit_recipe_video.php?recipe_id=$recipe_id&amp;l=$l\">$l_video</a></li>
				<li><a href=\"edit_recipe_tags.php?recipe_id=$recipe_id&amp;l=$l\">$l_tags</a></li>
				<li><a href=\"edit_recipe_links.php?recipe_id=$recipe_id&amp;l=$l\" class=\"active\">$l_links</a></li>
			</ul>
		</div><p>&nbsp;</p>
	<!-- //Menu -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->



	<!-- Form -->
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title_a\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"edit_recipe_links.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		";
				// Fetch links
				$y = 1;
				$query = "SELECT link_id, link_title, link_url FROM $t_recipes_links WHERE link_recipe_id=$get_recipe_id ORDER BY link_id ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_link_id, $get_link_title, $get_link_url) = $row;
				
					if($y == "1"){
						$letter = "_a";
					}
					elseif($y == "2"){
						$letter = "_b";
					}
					elseif($y == "3"){
						$letter = "_c";
					}
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link $y</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title$letter\" value=\"$get_link_title\" size=\"20\" />
					</p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url$letter\" value=\"$get_link_url\" size=\"20\" /></p>
					";
					$y++;
				}
				
				
				if($y == 1){
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 1</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_a\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_a\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 2</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_b\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_b\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 3</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 2){
					echo"

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 2</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_b\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_b\" value=\"\" size=\"20\" /></p>

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 3</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 3){
					echo"

					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_link 3</b></p>
					<p>$l_title:<br />
					<input type=\"text\" name=\"inp_title_c\" value=\"\" size=\"20\" /></p>
					<p>$l_url:<br />
					<input type=\"text\" name=\"inp_url_c\" value=\"\" size=\"20\" /></p>
					";

				}
				echo"


		<p>
		<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>

	<!-- //Form -->


	<!-- Buttons -->
		<p style=\"margin-top: 20px;\">
		<a href=\"my_recipes.php?l=$l#recipe$recipe_id\" class=\"btn btn_default\">$l_my_recipes</a>
		<a href=\"view_recipe.php?recipe_id=$recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_view_recipe</a>

		</p>
	<!-- //Buttons -->
	";


		} // is owner or admin
		else{
			echo"<p>Server error 403</p>
			<p>Only the owner and admin can edit the recipe</p>
			";
		}
	} // Isset user id
	else{
		echo"
		<h1>Log in</h1>
		<p><a href=\"$root/users/login.php?l=$l\">Please log in</a>
		</p>
		";
	}
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>