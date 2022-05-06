<?php
/**
*
* File: _admin/_inc/exercises/levels.php
* Version 1.0
* Date 12:23 10.02.2019
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
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles			= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images		= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_index_tags				= $mysqlPrefixSav . "exercise_index_tags";
$t_exercise_tags_cloud				= $mysqlPrefixSav . "exercise_tags_cloud";
$t_exercise_index_translations_relations	= $mysqlPrefixSav . "exercise_index_translations_relations";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['level_id'])) {
	$level_id= $_GET['level_id'];
	$level_id = strip_tags(stripslashes($level_id));
}
else{
	$level_id = "";
}


/*- Script start --------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Levels</h1>


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

	<!-- Add -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=add&amp;editor_language=$editor_language\" class=\"btn\">Add</a>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\" class=\"btn\">Translations</a>
		</p>
	<!-- //Add -->


	<!-- List types -->
		<div class=\"vertical\">
			<ul>
		";
		$query = "SELECT level_id, level_title FROM $t_exercise_levels ORDER BY level_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_level_id, $get_level_title) = $row;
		
			echo"			";
			echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=view&amp;level_id=$get_level_id&amp;editor_language=$editor_language\">$get_level_title</a></li>\n";
		}
		echo"
			</ul>
		</div>
	<!-- //List types -->
 	";
} // action == "";
elseif($action == "add"){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);


		mysqli_query($link, "INSERT INTO $t_exercise_levels 
		(level_id, level_title) 
		VALUES 
		(NULL, $inp_title_mysql)")
		or die(mysqli_error($link));

		$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$l_add</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Exercises</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Levels</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language\">Add</a>
			</p>
		<!-- //Where am I ? -->

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


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->


	<!-- Form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p><input type=\"submit\" value=\"Save\" class=\"btn\" /></p>
		</form>
	<!-- //Form -->
	

	";
} // add
elseif($action == "view"){
	// Find
	$level_id_mysql = quote_smart($link, $level_id);
	$query = "SELECT level_id, level_title FROM $t_exercise_levels WHERE level_id=$level_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_level_id, $get_level_title) = $row;
	
	if($get_level_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a>
		</p>
		";
	} // not found
	else{
		if($process == 1){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$result = mysqli_query($link, "UPDATE $t_exercise_levels SET level_title=$inp_title_mysql WHERE level_id=$get_level_id") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&action=$action&level_id=$level_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>$get_level_title</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Exercises</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Levels</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;level_id=$level_id&amp;editor_language=$editor_language\">$get_level_title</a>
			</p>
		<!-- //Where am I ? -->

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
	

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

	
		<!-- Form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;level_id=$level_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_level_title\" size=\"25\" />
			</p>

			<p><input type=\"submit\" value=\"Save\" class=\"btn\" /></p>
			</form>
		<!-- //Form -->

		<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;level_id=$level_id&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/delete.png\" alt=\"delete.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;level_id=$level_id&amp;editor_language=$editor_language\">Delete</a>
			</p>
		<!-- //Actions -->
		";
	} // found
	
} // edit
elseif($action == "delete"){
	// Find
	$level_id_mysql = quote_smart($link, $level_id);
	$query = "SELECT level_id, level_title FROM $t_exercise_levels WHERE level_id=$level_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_level_id, $get_level_title) = $row;

	if($get_level_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found in database.</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a>
		</p>
		";
	} // not found
	else{
		if($process == 1){
			$result = mysqli_query($link, "DELETE FROM $t_exercise_levels WHERE level_id=$level_id_mysql") or die(mysqli_error($link));


			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}


		echo"
		<h1>Delete</h1>

		<!-- Where am I ? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;editor_language=$editor_language\">Exercises</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Levels</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=view&amp;level_id=$level_id&amp;editor_language=$editor_language\">$get_level_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;level_id=$level_id&amp;editor_language=$editor_language\">Delete</a>
			</p>
		<!-- //Where am I ? -->


		<p>Are you sure you want to delete $get_level_title?</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;level_id=$level_id&amp;editor_language=$editor_language&amp;process=1\">Delete</a>
		&middot;
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Cancel</a>
		</p>
		";
	} // found
	
} // delete
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT level_id, level_title FROM $t_exercise_levels ORDER BY level_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_level_id, $get_level_title) = $row;

			$inp_translation_value = $_POST["inp_translation_value_$get_level_id"];
			$inp_translation_value = output_html($inp_translation_value);
			$inp_translation_value_mysql = quote_smart($link, $inp_translation_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_exercise_levels_translations SET level_translation_value=$inp_translation_value_mysql WHERE level_id=$get_level_id AND level_translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>Translations</h1>


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

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	

	<!-- Translate form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			<span>Translation</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	

		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT level_id, level_title FROM $t_exercise_levels ORDER BY level_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_current_level_id, $get_current_level_title) = $row;


			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}	

			// Translation
			$query_translation = "SELECT level_translation_id, level_id, level_translation_language, level_translation_value FROM $t_exercise_levels_translations WHERE level_id=$get_current_level_id AND level_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_level_translation_id, $get_level_id, $get_level_translation_language, $get_level_translation_value) = $row_translation;
			if($get_level_translation_id == ""){
				// It doesnt exists, create it.

				mysqli_query($link, "INSERT INTO $t_exercise_levels_translations 
				(level_translation_id, level_id, level_translation_language, level_translation_value) 
				VALUES 
				(NULL, '$get_current_level_id', $editor_language_mysql, '')")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"1;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_current_level_title</span>
			  </td>
			  <td class=\"$style\">
				<span><input type=\"text\" name=\"inp_translation_value_$get_current_level_id\" value=\"$get_level_translation_value\" size=\"40\" /></span>
			  </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn\" />
		</p>
		</form>

	<!-- //List all categories -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">Back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>