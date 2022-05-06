<?php 
/**
*
* File: recipes/step_2_directions.php
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


	$query = "SELECT recipe_id, recipe_video_h, recipe_video_v FROM $t_recipes WHERE recipe_user_id=$inp_recipe_user_id_mysql AND recipe_id=$recipe_id_mysql AND recipe_user_id=$inp_recipe_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_video_h, $get_recipe_video_v) = $row;

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
			
			$inp_recipe_video_h = $_POST['inp_recipe_video_h'];
			$inp_recipe_video_h = output_html($inp_recipe_video_h);
			$inp_recipe_video_h_mysql = quote_smart($link, $inp_recipe_video_h);

			$inp_recipe_video_v = $_POST['inp_recipe_video_v'];
			$inp_recipe_video_v = output_html($inp_recipe_video_v);
			$inp_recipe_video_v_mysql = quote_smart($link, $inp_recipe_video_v);


			// Update MySQL
			$result = mysqli_query($link, "UPDATE $t_recipes SET 
							recipe_video_h=$inp_recipe_video_h_mysql,
							recipe_video_v=$inp_recipe_video_v_mysql
							 WHERE recipe_id=$recipe_id_mysql");



			// Header
			$url = "submit_recipe_step_6_video.php?&recipe_id=$recipe_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$l_video</h1>

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
			<h2>$l_enter_url_to_embeded_video</h2>
			<span class=\"smal\">
			($l_example_video_url)</span>

			<script>
			window.onload = function() {
				document.getElementById(\"inp_recipe_video_h\").focus();
			}
			</script>

			<form method=\"post\" action=\"submit_recipe_step_6_video.php?recipe_id=$recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

			<!-- Video H -->
				<p><b>$l_desktop:</b><br />
				<input type=\"text\" name=\"inp_recipe_video_h\" id=\"inp_recipe_video_h\" value=\"$get_recipe_video_h\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 99%;\" />
				</p>
				";
				if($get_recipe_video_h != ""){
					echo"
					<iframe width=\"847\" height=\"476\" src=\"$get_recipe_video_h\" frameborder=\"0\" allowfullscreen></iframe>
					<p><hr /></p>";
				}
				echo"
			<!-- //Video H -->

			<!-- Video V -->
				<p><b>$l_mobile:</b><br />
				<input type=\"text\" name=\"inp_recipe_video_v\" value=\"$get_recipe_video_v\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 99%;\" />
				</p>
				";
				if($get_recipe_video_v != ""){
					echo"
					<iframe width=\"476\" height=\"847\" src=\"$get_recipe_video_v\" frameborder=\"0\" allowfullscreen></iframe>
					<p><hr /></p>";
				}
				echo"
			<!-- //Video V -->

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /><br />
			
			</p>

	
			
			</form>

		<!-- //Form -->

		<p style=\"margin-top: 20px;\">
		<a href=\"submit_recipe_step_7_tags.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
		</p>
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