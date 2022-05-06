<?php
/**
*
* File: _admin/_inc/muscles/open_main_muscle_group.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ----------------------------------------------------------------------------- */
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";


/*- Variables -------------------------------------------------------------------------- */
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$id = strip_tags(stripslashes($id));
}
else{
	$id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}


/*- Scriptstart ---------------------------------------------------------------------- */
echo"

<h1>Muscles</h1>


<p>
<a href=\"index.php?open=$open&amp;page=muscle_new&amp;main_muscle_group_id=$main_muscle_group_id&amp;editor_language=$editor_language\">New</a>
</p>
	
<!-- Left and right -->
	<div style=\"float: left;\">
		<!-- Main muscle groups -->
			<table class=\"hor-zebra\">
			 <tr>
			  <td class=\"odd\">
				<p>\n";
				// Get all main
				$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
			
					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_group_translation_id, $get_muscle_group_translation_name) = $row_translation;
					echo"					";
					echo"<a href=\"index.php?open=$open&amp;page=open_main_muscle_group&amp;main_muscle_group_id=$get_muscle_group_id&amp;editor_language=$editor_language\""; if($get_muscle_group_id == "$main_muscle_group_id"){ echo" style=\"font-weight: bold\""; } echo">$get_muscle_group_translation_name</a><br />\n";

					if($get_muscle_group_id == "$main_muscle_group_id"){
						// Get sub
						$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_muscle_group_id'";
						$result_sub = mysqli_query($link, $query_sub);
						while($row_sub = mysqli_fetch_row($result_sub)) {
							list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
			
							// Translation
							$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
							$result_translation = mysqli_query($link, $query_translation);
							$row_translation = mysqli_fetch_row($result_translation);
							list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;
							echo"					";
							echo"&nbsp; &nbsp; <a href=\"index.php?open=$open&amp;page=open_sub_muscle_group&amp;main_muscle_group_id=$get_muscle_group_id&amp;sub_muscle_group_id=$get_sub_muscle_group_id&amp;editor_language=$editor_language\">$get_sub_muscle_group_translation_name</a><br />\n";
						}
					}
				}
			echo"
			  </td>
			 </tr>
			</table>
		<!-- //Main categories -->
	</div>
	<div style=\"float: left;padding: 0px 0px 0px 20px;\">
		<!-- All muscles -->
		<!-- //All muscles -->
	</div>
<!-- //Left and right -->
";
?>