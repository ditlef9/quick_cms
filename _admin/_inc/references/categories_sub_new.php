<?php
/**
*
* File: _admin/_inc/courses/categories_sub_new.php
* Version 1.0.0
* Date 22:12 12.09.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
$main_category_id_mysql = quote_smart($link, $main_category_id);


if($action == ""){
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{


		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_language_mysql = quote_smart($link, $get_current_main_category_language);

			$datetime = date("Y-m-d H:i:s");

			$inp_main_category_title_mysql = quote_smart($link, $get_current_main_category_title);
		
			mysqli_query($link, "INSERT INTO $t_references_categories_sub
			(sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated) 
			VALUES 
			(NULL, $inp_title_mysql, $inp_title_clean_mysql, '', $get_current_main_category_id, $inp_main_category_title_mysql, $inp_language_mysql, '$datetime', '$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT sub_category_id FROM $t_references_categories_sub WHERE sub_category_created='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id) = $row;



			// Header
			$url = "index.php?open=$open&page=categories_main_open&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=sub_category_" . $inp_title_clean . "_created";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>New sub category</h1>
				

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




		<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=references&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">References</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main Categories</a>
		&gt;
		<a href=\"index.php?open=references&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=categories_sub_new&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">New sub category</a>
		</p>
		<!-- //Where am I? -->


		<!-- New sub category -->
		
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

			</form>
		<!-- //New sub category form -->
		";
	} // action == ""
} // main category found
?>