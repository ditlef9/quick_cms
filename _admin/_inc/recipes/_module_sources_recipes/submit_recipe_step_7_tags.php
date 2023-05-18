<?php 
/**
*
* File: recipes/step_6_tags.php
* Version 1.0.0
* Date 23:59 27.11.2017
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
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	

	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);
	$inp_recipe_user_id = $_SESSION['user_id'];
	$inp_recipe_user_id = output_html($inp_recipe_user_id);
	$inp_recipe_user_id_mysql = quote_smart($link, $inp_recipe_user_id);


	$query = "SELECT recipe_id, recipe_language FROM $t_recipes WHERE recipe_user_id=$inp_recipe_user_id_mysql AND recipe_id=$recipe_id_mysql AND recipe_user_id=$inp_recipe_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_language) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		if($process == 1){
			// Delete all old tags
			$result = mysqli_query($link, "DELETE FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id");
				
			// Lang
			$inp_tag_language_mysql = quote_smart($link, $get_recipe_language);

			$inp_tag_a = $_POST['inp_tag_a'];
			$inp_tag_a = output_html($inp_tag_a);
			$inp_tag_a_mysql = quote_smart($link, $inp_tag_a);
	
			$inp_tag_a_clean = clean($inp_tag_a);
			$inp_tag_a_clean = strtolower($inp_tag_a);
			$inp_tag_a_clean_mysql = quote_smart($link, $inp_tag_a_clean);

			if($inp_tag_a != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_recipes_tags 
				(tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_recipe_id, $inp_tag_a_mysql, $inp_tag_a_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}

			$inp_tag_b = $_POST['inp_tag_b'];
			$inp_tag_b = output_html($inp_tag_b);
			$inp_tag_b_mysql = quote_smart($link, $inp_tag_b);

			$inp_tag_b_clean = clean($inp_tag_b);
			$inp_tag_b_clean = strtolower($inp_tag_b);
			$inp_tag_b_clean_mysql = quote_smart($link, $inp_tag_b_clean);

			if($inp_tag_b != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_recipes_tags 
				(tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_recipe_id, $inp_tag_b_mysql, $inp_tag_b_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}

			$inp_tag_c = $_POST['inp_tag_c'];
			$inp_tag_c = output_html($inp_tag_c);
			$inp_tag_c_mysql = quote_smart($link, $inp_tag_c);

			$inp_tag_c_clean = clean($inp_tag_c);
			$inp_tag_c_clean = strtolower($inp_tag_c);
			$inp_tag_c_clean_mysql = quote_smart($link, $inp_tag_c_clean);

			if($inp_tag_c != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_recipes_tags 
				(tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_recipe_id, $inp_tag_c_mysql, $inp_tag_c_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}


			// Header
			$url = "submit_recipe_step_8_links.php?&recipe_id=$recipe_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$l_tags</h1>

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


		<!-- Form -->
	
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_tag_a\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"submit_recipe_step_7_tags.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				";
				// Fetch tags
				$y = 1;
				$query = "SELECT tag_id, tag_title FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id ORDER BY tag_id ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_tag_id, $get_tag_title) = $row;
				
					if($y == "1"){
						$name = "inp_tag_a";
					}
					elseif($y == "2"){
						$name = "inp_tag_b";
					}
					elseif($y == "3"){
						$name = "inp_tag_c";
					}
					echo"
					<p><b>$l_tag $y:</b><br />
					<input type=\"text\" name=\"$name\" value=\"$get_tag_title\" size=\"20\" /></p>
					";
					$y++;
				}
				
				
				if($y == 1){
					echo"
					<p><b>$l_tag 1:</b><br />
					<input type=\"text\" name=\"inp_tag_a\" value=\"\" size=\"20\" /></p>
					
					<p><b>$l_tag 2:</b><br />
					<input type=\"text\" name=\"inp_tag_b\" value=\"\" size=\"20\" /></p>
					
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 2){
					echo"
					
					<p><b>$l_tag 2:</b><br />
					<input type=\"text\" name=\"inp_tag_b\" value=\"\" size=\"20\" /></p>
					
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" value=\"\" size=\"20\" /></p>
					";

				}
				elseif($y == 3){
					echo"
					
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" value=\"\" size=\"20\" /></p>
					";

				}
				echo"

	
			
			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>

		<!-- //Form -->

		";
	} // recipe found
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