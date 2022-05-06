<?php
/**
*
* File: _admin/_inc/courses/categories_sub_edit.php
* Version 
* Date 11:39 15.09.2019
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
if(isset($_GET['sub_category_id'])){
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
$sub_category_id_mysql = quote_smart($link, $sub_category_id);


if($action == ""){
	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	if($get_current_sub_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		// Find main category
		$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$get_current_sub_category_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;


		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_main_category_id = $_POST['inp_main_category_id'];
			$inp_main_category_id = output_html($inp_main_category_id);
			$inp_main_category_id_mysql = quote_smart($link, $inp_main_category_id);

			// Find (new) main category
			$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_id=$inp_main_category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_new_main_category_id, $get_new_main_category_title) = $row;

			$inp_main_category_title_mysql = quote_smart($link, $get_new_main_category_title);


			$datetime = date("Y-m-d H:i:s");
			
			$result = mysqli_query($link, "UPDATE $t_references_categories_sub SET 
					sub_category_title=$inp_title_mysql, 
					sub_category_title_clean=$inp_title_clean_mysql, 
					sub_category_main_category_id=$inp_main_category_id_mysql,
					sub_category_main_category_title=$inp_main_category_title_mysql,
					sub_category_updated='$datetime'
					WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));


			// Header
			$url = "index.php?open=$open&page=categories_main_open&main_category_id=$inp_main_category_id&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Edit sub category</h1>
					

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
			<a href=\"index.php?open=references&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">References</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main categories</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_sub_edit&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_sub_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Edit course form -->
		
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_sub_category_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Main category:</b><br />
		<select name=\"inp_main_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$language_mysql = quote_smart($link, $get_current_sub_category_language);
		$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_language=$language_mysql ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title) = $row;
			echo"	<option value=\"$get_main_category_id\""; if($get_main_category_id == "$get_current_sub_category_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_title</option>\n";
		}
		echo"
		</select>


		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
		<!-- //Edit course form -->
		";
	} // found
} // action == 
?>