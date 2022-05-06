<?php
/**
*
* File: _admin/_inc/references/categories_main_open.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
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
		echo"
		<h1>$get_current_main_category_title</h1>
			

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
			
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=references&amp;page=categories_sub_new&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New sub category</a>
			</p>
		<!-- //Menu -->

		<!-- List sub categories -->
		
        	
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Sub category title</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
	


			$editor_language_mysql = quote_smart($link, $editor_language);
			$query = "SELECT sub_category_id, sub_category_title FROM $t_references_categories_sub WHERE sub_category_main_category_id=$get_current_main_category_id ORDER BY sub_category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_sub_category_id, $get_sub_category_title) = $row;

				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>$get_sub_category_title</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					<a href=\"index.php?open=$open&amp;page=categories_sub_edit&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=categories_sub_delete&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language\">Delete</a>
					</span>
				 </td>
				</tr>
				";
			}
			echo"
			 </tbody>
			</table>
		<!-- //List all courses -->
		";
	} // category found
} // action
?>