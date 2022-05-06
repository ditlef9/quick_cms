<?php
/**
*
* File: _admin/_inc/notes_new_category.php
* Version 1
* Date 14:58 02.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_notes_categories   = $mysqlPrefixSav . "notes_categories";
$t_notes_pages	      = $mysqlPrefixSav . "notes_pages";
$t_notes_pages_images = $mysqlPrefixSav . "notes_pages_images";
$t_notes_pages_files  = $mysqlPrefixSav . "notes_pages_files";


/*- Setup ----------------------------------------------------------------------------- */
if($process == "1"){
	$datetime = date("Y-m-d H:i:s");

	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	$inp_title_mysql = quote_smart($link, $inp_title);

	$inp_color_schema = $_POST['inp_color_schema'];
	$inp_color_schema = output_html($inp_color_schema);

	if($inp_color_schema == "green"){
		$inp_category_bg_color		= "#8ad293";
		$inp_category_border_color	= "#8ad293";
		$inp_category_title_color	= "#000000";
		$inp_pages_bg_color		= "#e4f4e6";
		$inp_pages_bg_color_hover	= "#f1faf2";
		$inp_pages_bg_color_active	= "#ffffff";
		$inp_pages_border_color		= "#8ad293";
		$inp_pages_border_color_hover	= "#8ad293";
		$inp_pages_border_color_active	= "#8ad293";
		$inp_pages_title_color		= "#595959";
		$inp_pages_title_color_hover	= "#000000";
		$inp_pages_title_color_active	= "#595959";
	}
	else{
		$inp_category_bg_color		= "#8ad293";
		$inp_category_border_color	= "#8ad293";
		$inp_category_title_color	= "#000000";
		$inp_pages_bg_color		= "#e4f4e6";
		$inp_pages_bg_color_hover	= "#f1faf2";
		$inp_pages_bg_color_active	= "#ffffff";
		$inp_pages_border_color		= "#8ad293";
		$inp_pages_border_color_hover	= "#8ad293";
		$inp_pages_border_color_active	= "#8ad293";
		$inp_pages_title_color		= "#595959";
		$inp_pages_title_color_hover	= "#000000";
		$inp_pages_title_color_active	= "#595959";
	}


	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	mysqli_query($link, "INSERT INTO $t_notes_categories
	(category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id) 
	VALUES 
	(NULL, $inp_title_mysql, 1, '$inp_category_bg_color', '$inp_category_border_color', '$inp_category_title_color', 
	'$inp_pages_bg_color', '$inp_pages_bg_color_hover', '$inp_pages_bg_color_active', '$inp_pages_border_color', '$inp_pages_border_color_hover', 
	'$inp_pages_border_color_active', '$inp_pages_title_color', '$inp_pages_title_color_hover', '$inp_pages_title_color_active', '$datetime',
	$my_user_id_mysql)")
	or die(mysqli_error($link));

	// Get ID
	$query = "SELECT category_id FROM $t_notes_categories WHERE category_created_datetime='$datetime'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id) = $row;

	// Header
	$url = "index.php?open=dashboard&page=notes_open_category&category_id=$get_current_category_id&editor_language=$editor_language&l=$l&ft=success&fm=category_created";
	header("Location: $url");
	exit;
	
}

echo"
<h1>New category</h1>

<!-- Where am I ? -->
	<p><b>$l_you_are_here</b><br />
	<a href=\"index.php?open=$open&amp;page=notes&amp;editor_language=$editor_language&amp;l=$l\">Notes</a>
	&gt;
	<a href=\"index.php?open=$open&amp;page=notes_new_category&amp;editor_language=$editor_language&amp;l=$l\">New category</a>
	</p>
<!-- //Where am I ? -->


	<!-- Categories -->
		<div class=\"tabs\">
			<ul>";
			$query_u = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories ORDER BY category_weight ASC";
			$result_u = mysqli_query($link, $query_u);
			while($row_u = mysqli_fetch_row($result_u)) {
				list($get_category_id, $get_category_title, $get_category_weight, $get_category_bg_color, $get_category_border_color, $get_category_title_color, $get_category_pages_bg_color, $get_category_pages_bg_color_hover, $get_category_pages_bg_color_active, $get_category_pages_border_color, $get_category_pages_border_color_hover, $get_category_pages_border_color_active, $get_category_pages_title_color, $get_category_pages_title_color_hover, $get_category_pages_title_color_active, $get_category_created_datetime, $get_category_created_by_user_id, $get_category_updated_datetime, $get_category_updated_by_user_id) = $row_u;
				echo"				";
				echo"<li><a href=\"index.php?open=$open&amp;page=notes_open_category&amp;category_id=$get_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_category_title</a>\n";
			}
			echo"
				<li><a href=\"index.php?open=$open&amp;page=notes_new_category&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">+</a>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Categories -->

<!-- New form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Color schema:</p>";
		$random = rand(1,8);
		echo"<div style=\"background: #8ad293;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"green\""; if($random == "1"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #9ac0e6;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"blue\""; if($random == "2"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #f3d275;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"orange\""; if($random == "3"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #f4a6a6;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"pink\""; if($random == "4"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #f1b87f;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"brown\""; if($random == "5"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #b4d367;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"light_green\""; if($random == "6"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #b9c0c7;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"grey\""; if($random == "7"){ echo" checked=\"checked\""; } echo" /></div>
		<div style=\"background: #d399cf;\"><input type=\"radio\" name=\"inp_color_schema\" value=\"purple\""; if($random == "8"){ echo" checked=\"checked\""; } echo" /></div>


	<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

	</form>
<!-- //New form -->
";

?>