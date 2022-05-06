<?php
/**
*
* File: _admin/_inc/references/categories_main.php
* Version 2.0.0
* Date 21:32 12.09.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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

if(isset($_GET['where'])){
	$where = $_GET['where'];
	$where = output_html($where);
}
else {
	$where = "comment_approved != '-1'";
}

if($action == ""){
	echo"
	<h1>Categories</h1>
				

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
		<a href=\"index.php?open=references&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
		</p>
	<!-- //Where am I? -->

	<!-- Menu -->
		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		<a href=\"index.php?open=references&amp;page=categories_main_new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New category</a>
		<!-- Select language -->
			<script>
			\$(function(){
				// bind change event to select
				\$('#inp_l').on('change', function () {
					var url = \$(this).val(); // get selected value
					if (url) { // require a URL
 						window.location = url; // redirect
					}
					return false;
				});
			});
			</script>

			<select id=\"inp_l\">
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					// No language selected?
					if($editor_language == ""){
							$editor_language = "$get_language_active_iso_two";
					}
					echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
		<!-- //Select language -->
		</p>
		</form>
	<!-- //Menu -->

	<!-- List all categories -->
		
        	
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_language=$editor_language_mysql ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			echo"
			<tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<a href=\"index.php?open=$open&amp;page=categories_main_open&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_title</a>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=categories_main_edit&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language\">Edit</a>
				&middot;
				<a href=\"index.php?open=$open&amp;page=categories_main_delete&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language\">Delete</a>
				</span>
			 </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //List all categories -->
	";
}
?>