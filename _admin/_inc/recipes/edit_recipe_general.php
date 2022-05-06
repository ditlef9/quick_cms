<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe_general.php
* Version 1.0.0
* Date 11:43 12.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables --------------------------------------------------------------------------- */
include("_inc/recipes/_tables.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";

/*- Functions --------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
/*- Translations --------------------------------------------------------------------- */
	include("_translations/admin/$l/recipes/t_view_recipe.php");

// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to edit was not found.
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Back</a>
	</p>
	";
}
else{
	// Get number of servings
	$query = "SELECT number_servings, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_servings, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs) = $row;

	if($process == 1){
		$inp_recipe_title = $_POST['inp_recipe_title'];
		$inp_recipe_title = output_html($inp_recipe_title);
		$inp_recipe_title_mysql = quote_smart($link, $inp_recipe_title);

		$inp_recipe_introduction = $_POST['inp_recipe_introduction'];
		$inp_recipe_introduction = output_html($inp_recipe_introduction);
		$inp_recipe_introduction = str_replace("<br />", "\n", $inp_recipe_introduction);
		$inp_recipe_introduction_mysql = quote_smart($link, $inp_recipe_introduction);


		// Update MySQL
		$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_title=$inp_recipe_title_mysql, recipe_introduction=$inp_recipe_introduction_mysql WHERE recipe_id=$recipe_id_mysql");

		// Directions

		$inp_recipe_directions = $_POST['inp_recipe_directions'];
		require_once "_functions/htmlpurifier/HTMLPurifier.auto.php";
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$clean_html = $purifier->purify($inp_recipe_directions);
		//$inp_recipe_directions_mysql = quote_smart($link, $inp_recipe_directions);

		$sql = "UPDATE $t_recipes SET recipe_directions=? WHERE recipe_id=$get_recipe_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_recipe_directions);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		// Search engine
		include("edit_recipe_include_update_search_engine.php");




		// Header
		$url = "index.php?open=$open&page=$page&recipe_id=$recipe_id&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<!-- Headline -->
		<div class=\"recipes_headline\">
			<h1>$get_recipe_title</h1>
		</div>
		<div class=\"recipes_buttons\">
			<p>
			<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$get_recipe_language\" class=\"btn_default\">View</a>
			</p>
		</div>
		<div class=\"clear\"></div>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?open=recipes&amp;page=default&amp;editor_language=$editor_language&amp;l=$l#recipe$recipe_id\">Recipes</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">$l_general</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_general&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\" class=\"active\">General</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_ingredients&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Ingredients</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_categorization&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Categorization</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_image&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Image</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_video&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Video</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_tags&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Tags</a></li>
				<li><a href=\"index.php?open=$open&amp;page=edit_recipe_links&amp;recipe_id=$recipe_id&amp;&amp;editor_language=$editor_language\">Links</a></li>
				<li><a href=\"index.php?open=$open&amp;page=delete_recipe&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language\">Delete</a>
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



	<!-- Edit selection -->


		<!-- TinyMCE -->
			<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
			<script>
			tinymce.init({
				mode : \"specific_textareas\",
        			editor_selector : \"myTextEditor\",
				plugins: \"image\",
				menubar: \"insert\",
				toolbar: \"image\",
				height: 500,
				menubar: false,
				plugins: [
				    'advlist autolink lists link image charmap print preview anchor textcolor',
				    'searchreplace visualblocks code fullscreen',
				    'insertdatetime media table contextmenu paste code help'
				  ],
				  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
				  content_css: [
				    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
				    '//www.tinymce.com/css/codepen.min.css']
			});
			</script>
		<!-- //TinyMCE -->


	<!-- Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;recipe_id=$recipe_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	

		<p><b>$l_title</b><br />
		<input type=\"text\" name=\"inp_recipe_title\" value=\"$get_recipe_title\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_introduction</b><br />
		<textarea name=\"inp_recipe_introduction\" rows=\"2\" cols=\"60\">$get_recipe_introduction</textarea>
		</p>

		<p><b>$l_directions</b><br />
		<textarea name=\"inp_recipe_directions\" rows=\"15\" cols=\"80\" class=\"myTextEditor\">$get_recipe_directions</textarea>
		</p>

		<!-- Buttons -->
			<p>
			<input type=\"submit\" value=\"$l_save_changes\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
		<!-- //Buttons -->


		</form>

	<!-- //Form -->

	";
} // recipe found
?>